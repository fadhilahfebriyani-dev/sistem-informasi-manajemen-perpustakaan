<?php
namespace App\Http\Controllers;

use App\Models\BebasPustaka;
use App\Models\Anggota;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BebasPustakaController extends Controller
{
    // -------------------------------------------------------
    // INDEX — daftar semua bebas pustaka
    // -------------------------------------------------------
    public function index(Request $request)
    {
        $search     = $request->search;
        $filterStat = $request->status;

        $bebas = BebasPustaka::with(['anggota'])
            ->when($search, fn($q) => $q->whereHas('anggota', fn($a) =>
                $a->where('nama',  'like', "%$search%")
                  ->orWhere('kelas','like', "%$search%")))
            ->when($filterStat, fn($q) => $q->where('status', $filterStat))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Statistik ringkasan
        $totalAktif    = BebasPustaka::where('status', 'aktif')->count();
        $totalNonaktif = BebasPustaka::where('status', 'nonaktif')->count();
        $totalAnggota  = Anggota::count();
        $belumBebas    = $totalAnggota - BebasPustaka::distinct('anggota_id')->count('anggota_id');

        return view('bebaspustaka.index', compact(
            'bebas', 'search', 'filterStat',
            'totalAktif', 'totalNonaktif', 'totalAnggota', 'belumBebas'
        ));
    }

    // -------------------------------------------------------
    // CREATE — form tambah
    // -------------------------------------------------------
    public function create()
    {
        $anggota      = Anggota::orderBy('kelas')->orderBy('nama')->get();
        $tahunAjaran  = $this->listTahunAjaran();
        $keperluan    = $this->listKeperluan();
        return view('bebaspustaka.create', compact('anggota', 'tahunAjaran', 'keperluan'));
    }

    // -------------------------------------------------------
    // STORE
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'anggota_id'   => 'required|exists:anggotas,id',
            'nomor_surat'  => 'nullable|string|max:100',
            'tanggal'      => 'required|date',
            'tahun_ajaran' => 'required|string|max:20',
            'keperluan'    => 'required|string|max:255',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        // Cek peminjaman aktif
        $pinjamAktif = Peminjaman::where('anggota_id', $request->anggota_id)
            ->where('status', 'dipinjam')
            ->count();

        if ($pinjamAktif > 0) {
            return back()
                ->withErrors(['anggota_id' => "Anggota masih memiliki $pinjamAktif buku yang belum dikembalikan."])
                ->withInput();
        }

        // Auto-nomor surat
        $nomor = $request->nomor_surat;
        if (empty($nomor)) {
            $urut  = str_pad(BebasPustaka::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT);
            $nomor = "$urut/BP/SMAN5TBO/" . now()->format('m') . "/" . date('Y');
        }

        BebasPustaka::updateOrCreate(
            ['anggota_id' => $request->anggota_id],
            array_merge($request->only('tanggal','tahun_ajaran','keperluan','status'), ['nomor_surat' => $nomor])
        );

        return redirect()->route('bebaspustaka.index')
            ->with('success', 'Bebas pustaka berhasil dicatat.');
    }

    // -------------------------------------------------------
    // SHOW — detail anggota + riwayat peminjaman
    // -------------------------------------------------------
    public function show(BebasPustaka $bebaspustaka)
    {
        $bebaspustaka->load('anggota');
        $anggota = $bebaspustaka->anggota;

        $riwayatPinjam = Peminjaman::with('details.buku', 'details.kodeBuku')
            ->where('anggota_id', $anggota->id)
            ->latest('tanggal_pinjam')
            ->get();

        $pinjamAktif = $riwayatPinjam->where('status', 'dipinjam');
        $sudahKembali = $riwayatPinjam->where('status', 'dikembalikan');

        return view('bebaspustaka.show', compact(
            'bebaspustaka', 'anggota', 'riwayatPinjam', 'pinjamAktif', 'sudahKembali'
        ));
    }

    // -------------------------------------------------------
    // EDIT
    // -------------------------------------------------------
    public function edit(BebasPustaka $bebaspustaka)
    {
        $anggota     = Anggota::orderBy('kelas')->orderBy('nama')->get();
        $tahunAjaran = $this->listTahunAjaran();
        $keperluan   = $this->listKeperluan();
        return view('bebaspustaka.edit', compact('bebaspustaka', 'anggota', 'tahunAjaran', 'keperluan'));
    }

    // -------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------
    public function update(Request $request, BebasPustaka $bebaspustaka)
    {
        $request->validate([
            'anggota_id'   => 'required|exists:anggotas,id',
            'nomor_surat'  => 'nullable|string|max:100',
            'tanggal'      => 'required|date',
            'tahun_ajaran' => 'required|string|max:20',
            'keperluan'    => 'required|string|max:255',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        $bebaspustaka->update($request->only(
            'anggota_id','nomor_surat','tanggal','tahun_ajaran','keperluan','status'
        ));

        return redirect()->route('bebaspustaka.index')
            ->with('success', 'Data bebas pustaka berhasil diupdate.');
    }

    // -------------------------------------------------------
    // DESTROY
    // -------------------------------------------------------
    public function destroy(BebasPustaka $bebaspustaka)
    {
        $bebaspustaka->delete();
        return redirect()->route('bebaspustaka.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    // -------------------------------------------------------
    // CETAK — halaman print surat
    // -------------------------------------------------------
    public function cetak(BebasPustaka $bebaspustaka)
    {
        $bebaspustaka->load('anggota');
        return view('bebaspustaka.cetak', compact('bebaspustaka'));
    }

    // -------------------------------------------------------
    // API — cek status peminjaman anggota (AJAX)
    // -------------------------------------------------------
    public function cekStatus($anggotaId)
    {
        $anggota = Anggota::findOrFail($anggotaId);

        $pinjamAktif = Peminjaman::with('details.buku')
            ->where('anggota_id', $anggotaId)
            ->where('status', 'dipinjam')
            ->get();

        $sudahAda = BebasPustaka::where('anggota_id', $anggotaId)->first();

        return response()->json([
            'anggota'     => $anggota,
            'aman'        => $pinjamAktif->isEmpty(),
            'jumlah'      => $pinjamAktif->count(),
            'buku_pinjam' => $pinjamAktif->map(fn($p) => [
            'judul'          => $p->details->first()?->buku?->judul ?? '-',
            'tanggal_pinjam' => $p->tanggal_pinjam,
            ]),
            'sudah_bebas' => $sudahAda ? [
                'nomor'    => $sudahAda->nomor_surat,
                'tanggal'  => $sudahAda->tanggal,
                'status'   => $sudahAda->status,
            ] : null,
        ]);
    }

    // -------------------------------------------------------
    // HELPERS
    // -------------------------------------------------------
    private function listTahunAjaran(): array
    {
        $y = (int) date('Y');
        return [
            ($y-1)."/$y",
            "$y/".($y+1),
            ($y+1)."/".($y+2),
        ];
    }

    private function listKeperluan(): array
    {
        return [
            'Ujian Akhir Semester',
            'Ujian Tengah Semester',
            'Kelulusan / Ijazah',
            'Pindah Sekolah',
            'Administrasi Sekolah',
            'Lainnya',
        ];
    }
}
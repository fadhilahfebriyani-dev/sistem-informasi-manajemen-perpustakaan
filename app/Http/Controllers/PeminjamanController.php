<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Buku;
use App\Models\KodeBuku;
use App\Models\Anggota;
use App\Services\DendaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    protected DendaService $dendaService;

    public function __construct(DendaService $dendaService)
    {
        $this->dendaService = $dendaService;
    }

    // ─── INDEX ───────────────────────────────────────────────────────────────
    public function index()
    {
        // Sinkronkan denda otomatis untuk peminjaman yang masih dipinjam & overdue,
        // supaya badge "Denda ..." langsung muncul tanpa admin input manual.
        $this->dendaService->syncOverdue();

        // Perbaiki data lama: denda sudah lunas tapi status peminjaman
        // belum ikut berubah jadi 'dikembalikan'.
        $this->dendaService->fixLunasBelumDikembalikan();

        $peminjaman = Peminjaman::with([
            'anggota',
            'details.buku',
            'details.kodeBuku',
            'denda',
        ])->latest()->paginate(10);

        return view('peminjaman.index', compact('peminjaman'));
    }

    // ─── CREATE ──────────────────────────────────────────────────────────────
    public function create()
    {
        $buku    = Buku::where('stok', '>', 0)->orderBy('judul')->get();
        $anggota = Anggota::orderBy('nama')->get();
        return view('peminjaman.create', compact('buku', 'anggota'));
    }

    // ─── STORE ───────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'anggota_id'         => 'required|exists:anggotas,id',
            'tanggal_pinjam'     => 'required|date',
            'tanggal_kembali'    => 'nullable|date|after_or_equal:tanggal_pinjam',
            'status'             => 'required|in:dipinjam,dikembalikan,terlambat',
            'buku_ids'           => 'required|array|min:1',
            'buku_ids.*'         => 'exists:bukus,id',
            'kode_buku_ids'      => 'nullable|array',
            'kode_buku_ids.*'    => 'nullable|exists:kodebukus,id',
        ], [
            'buku_ids.required' => 'Pilih minimal 1 buku.',
            'buku_ids.min'      => 'Pilih minimal 1 buku.',
        ]);

        DB::transaction(function () use ($request) {
            $peminjaman = Peminjaman::create([
                'anggota_id'      => $request->anggota_id,
                'tanggal_pinjam'  => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status'          => $request->status,
            ]);

            foreach ($request->buku_ids as $index => $bukuId) {
                $buku        = Buku::findOrFail($bukuId);
                $kodeBukuId  = $request->kode_buku_ids[$index] ?? null;
                $kodeBukuStr = null;

                if ($kodeBukuId) {
                    $kb          = KodeBuku::find($kodeBukuId);
                    $kodeBukuStr = $kb?->kode_buku;
                    $kb?->update(['status' => 'dipinjam']);
                }

                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'buku_id'       => $bukuId,
                    'kode_buku_id'  => $kodeBukuId,
                    'kode_buku'     => $kodeBukuStr,
                ]);

                $buku->decrement('stok');
            }
        });

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dicatat.');
    }

    // ─── EDIT ────────────────────────────────────────────────────────────────
    public function edit(Peminjaman $peminjaman)
    {
        $peminjaman->load(['details.buku', 'details.kodeBuku']);
        $buku    = Buku::orderBy('judul')->get();
        $anggota = Anggota::orderBy('nama')->get();
        return view('peminjaman.edit', compact('peminjaman', 'buku', 'anggota'));
    }

    // ─── UPDATE ──────────────────────────────────────────────────────────────
    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'anggota_id'      => 'required|exists:anggotas,id',
            'tanggal_pinjam'  => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'status'          => 'required|in:dipinjam,dikembalikan,terlambat',
        ]);

        DB::transaction(function () use ($request, $peminjaman) {
            $statusLama = $peminjaman->status;
            $statusBaru = $request->status;

            // Stok & kode buku hanya dikembalikan SEKALI, yaitu saat transisi
            // dari 'dipinjam' -> ('dikembalikan' atau 'terlambat').
            // Jika statusnya sudah 'terlambat' atau 'dikembalikan' sebelumnya,
            // stok sudah pernah dikembalikan, jangan diulang.
            if ($statusLama === 'dipinjam' && in_array($statusBaru, ['dikembalikan', 'terlambat'])) {
                foreach ($peminjaman->details as $detail) {
                    $detail->buku?->increment('stok');

                    if ($detail->kode_buku_id) {
                        KodeBuku::find($detail->kode_buku_id)
                            ?->update(['status' => 'tersedia']);
                    }
                }
            }

            $peminjaman->update([
                'anggota_id'      => $request->anggota_id,
                'tanggal_pinjam'  => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status'          => $statusBaru,
            ]);
        });

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    // ─── DESTROY ─────────────────────────────────────────────────────────────
    public function destroy(Peminjaman $peminjaman)
    {
        DB::transaction(function () use ($peminjaman) {
            // Stok hanya perlu dikembalikan jika peminjaman masih berstatus
            // 'dipinjam' (belum pernah fisik dikembalikan). Jika sudah
            // 'terlambat' atau 'dikembalikan', stok sudah dikembalikan sebelumnya.
            if ($peminjaman->status === 'dipinjam') {
                foreach ($peminjaman->details as $detail) {
                    $detail->buku?->increment('stok');

                    if ($detail->kode_buku_id) {
                        KodeBuku::find($detail->kode_buku_id)
                            ?->update(['status' => 'tersedia']);
                    }
                }
            }

            $peminjaman->delete();
        });

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }

    // ─── KEMBALIKAN ──────────────────────────────────────────────────────────
    /**
     * Proses pengembalian buku (fisik).
     * - Jika TIDAK terlambat -> status langsung 'dikembalikan', selesai.
     * - Jika terlambat -> status jadi 'terlambat' (buku fisik sudah kembali,
     *   stok & kode buku sudah tersedia lagi), TAPI peminjaman baru dianggap
     *   'dikembalikan' penuh setelah dendanya dibayar lunas
     *   (lihat DendaService::bayarDenda()).
     */
    public function kembalikan(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Peminjaman ini sudah selesai.');
        }

        $terlambat = $peminjaman->isTerlambat();

        DB::transaction(function () use ($peminjaman, $terlambat) {
            // Buku fisik sudah diterima kembali -> stok & kode buku dikembalikan
            foreach ($peminjaman->details as $detail) {
                $detail->buku?->increment('stok');

                if ($detail->kode_buku_id) {
                    KodeBuku::find($detail->kode_buku_id)
                        ?->update(['status' => 'tersedia']);
                }
            }

            $peminjaman->update([
                'status'                 => $terlambat ? 'terlambat' : 'dikembalikan',
                'tanggal_kembali_aktual' => now(),
                'terlambat'              => $terlambat,
            ]);
        });

        if ($terlambat) {
            $denda = $this->dendaService->hitungDenda($peminjaman);

            return redirect()
                ->route('denda.show', $denda)
                ->with('warning',
                    'Buku terlambat ' . $denda->hari_terlambat .
                    ' hari! Peminjaman baru akan tercatat "Dikembalikan" setelah denda sebesar ' .
                    $denda->total_denda_format . ' dibayar lunas.'
                );
        }

        return redirect()
            ->route('peminjaman.index')
            ->with('success', 'Buku berhasil dikembalikan tepat waktu.');
    }

    // ─── AJAX: ambil kode buku tersedia ──────────────────────────────────────
    public function getKodeBuku(Request $request)
    {
        $kodeBukus = KodeBuku::where('buku_id', $request->buku_id)
            ->where('status', 'tersedia')
            ->orderBy('kode_buku')
            ->get(['id', 'kode_buku']);

        return response()->json([
            'kode_bukus' => $kodeBukus,
            'auto_pilih' => $kodeBukus->first()?->id,
        ]);
    }
}
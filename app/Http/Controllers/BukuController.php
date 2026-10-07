<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KodeBuku;
use App\Models\Kategori;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    // ================================================================
    // INDEX
    // ================================================================
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Tambah 'kodeBuku' agar kolom Label bisa dihitung di view
        $query = Buku::with(['kategori', 'kodeBuku'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('pengarang', 'like', "%{$search}%");
            });
        }

        $buku          = $query->paginate(15)->withQueryString();
        $totalBuku     = Buku::count();
        $totalStok     = Buku::sum('stok');
        $totalTersedia = Buku::where('stok', '>', 0)->count();
        $totalHabis    = Buku::where('stok', 0)->count();

        // Jumlah label belum dicetak → notif di header tabel
        $belumDicetak  = KodeBuku::where('label_dicetak', false)->count();

        return view('buku.index', compact(
            'buku', 'search',
            'totalBuku', 'totalStok', 'totalTersedia', 'totalHabis',
            'belumDicetak'
        ));
    }

    // ================================================================
    // CREATE
    // ================================================================
    public function create()
    {
        $kategori = Kategori::orderBy('nama')->get();

        return view('buku.create', compact('kategori'));
    }

    // ================================================================
    // STORE
    // ================================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'pengarang'    => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategoris,id',
            'stok'         => 'required|integer|min:1|max:999',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|string|max:4',
            'sampul'       => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('sampul')) {
            $validated['sampul'] = $request->file('sampul')->store('sampul-buku', 'public');
        }

        $buku = Buku::create($validated);

        $this->buatKodeBuku($buku, 1, $validated['stok']);

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil ditambahkan beserta ' . $validated['stok'] . ' kode buku.');
    }

    // ================================================================
    // SHOW
    // ================================================================
    public function show(Buku $buku)
    {
        $buku->load(['kategori', 'kodeBuku']);

        // Ambil semua detail peminjaman untuk buku ini, beserta data peminjaman & anggota
        $detailPeminjaman = DetailPeminjaman::with(['peminjaman.anggota'])
            ->where('buku_id', $buku->id)
            ->latest()
            ->get();

        // Sedang dipinjam = status peminjaman induk belum "dikembalikan"
        $pinjamAktif = $detailPeminjaman->filter(function ($d) {
            return $d->peminjaman && $d->peminjaman->status !== 'dikembalikan';
        })->values();

        // Riwayat = semua transaksi peminjaman untuk buku ini
        $riwayat = $detailPeminjaman;

        $totalDipinjam = $pinjamAktif->count();
        $totalRiwayat  = $riwayat->count();

        return view('buku.show', compact(
            'buku', 'pinjamAktif', 'riwayat', 'totalDipinjam', 'totalRiwayat'
        ));
    }

    // ================================================================
    // EDIT
    // ================================================================
    public function edit(Buku $buku)
    {
        $buku->load(['kategori', 'kodeBuku']);
        $kategori = Kategori::orderBy('nama')->get();

        return view('buku.edit', compact('buku', 'kategori'));
    }

    // ================================================================
    // UPDATE
    // ================================================================
    public function update(Request $request, Buku $buku)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'pengarang'    => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategoris,id',
            'stok'         => 'required|integer|min:0|max:999',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|string|max:4',
            'sampul'       => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'hapus_sampul' => 'nullable|boolean',
        ]);

        // Urus sampul: ganti baru, atau hapus jika dicentang
        if ($request->hasFile('sampul')) {
            $buku->hapusSampul();
            $validated['sampul'] = $request->file('sampul')->store('sampul-buku', 'public');
        } elseif ($request->boolean('hapus_sampul')) {
            $buku->hapusSampul();
            $validated['sampul'] = null;
        }
        unset($validated['hapus_sampul']);

        $stokLama = $buku->stok;
        $stokBaru = (int) $validated['stok'];

        $buku->update($validated);

        if ($stokBaru > $stokLama) {
            // Tambah kode buku baru, lanjut dari nomor urut tertinggi + 1
            $nomorMulai = $stokLama + 1;
            $this->buatKodeBuku($buku, $nomorMulai, $stokBaru);
        } elseif ($stokBaru < $stokLama) {
            // Hapus kode dengan nomor urut tertinggi, tapi jangan hapus yang sedang dipinjam
            $jumlahHapus = $stokLama - $stokBaru;

            $kandidat = $buku->kodeBuku()
                ->orderByDesc('nomor_urut')
                ->take($jumlahHapus)
                ->get();

            $tidakBisaDihapus = $kandidat->where('status', '!=', 'tersedia')->count();

            if ($tidakBisaDihapus > 0) {
                return back()
                    ->withInput()
                    ->with('error', 'Tidak bisa mengurangi stok: ada ' . $tidakBisaDihapus . ' kode buku dengan nomor urut tertinggi yang masih dipinjam/tidak tersedia.');
            }

            KodeBuku::whereIn('id', $kandidat->pluck('id'))->delete();
        }

        return redirect()->route('buku.show', $buku->id)
            ->with('success', 'Buku berhasil diperbarui.');
    }

    // ================================================================
    // DESTROY
    // ================================================================
    public function destroy(Buku $buku)
    {
        $adaDipinjam = $buku->kodeBuku()->where('status', 'dipinjam')->exists();

        if ($adaDipinjam) {
            return back()->with('error', 'Buku tidak bisa dihapus karena masih ada eksemplar yang sedang dipinjam.');
        }

        $buku->hapusSampul();
        $buku->kodeBuku()->delete();
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }

    // ================================================================
    // CETAK LABEL — halaman cetak semua / filter
    // ================================================================
    public function cetak(Request $request)
    {
        $filter  = $request->input('filter', 'belum');
        $dari    = $request->input('dari');
        $sampai  = $request->input('sampai');
        $bukuIds = $request->input('buku_id', []);

        $query = Buku::with(['kategori', 'kodeBuku'])
                     ->orderBy('created_at', 'desc');

        if ($dari)         $query->whereDate('created_at', '>=', $dari);
        if ($sampai)       $query->whereDate('created_at', '<=', $sampai);
        if (!empty($bukuIds)) $query->whereIn('id', $bukuIds);

        $bukus = $query->get();

        $bukus = $bukus->map(function ($buku) use ($filter) {
            if ($filter === 'belum') {
                $buku->setRelation(
                    'kodeBuku',
                    $buku->kodeBuku->where('label_dicetak', false)->values()
                );
            }
            return $buku;
        })->filter(fn($b) => $b->kodeBuku->isNotEmpty());

        $semuaBuku         = Buku::with('kodeBuku')->orderBy('judul')->get(['id', 'judul', 'created_at']);
        $totalBelumDicetak = KodeBuku::where('label_dicetak', false)->count();
        $totalSudahDicetak = KodeBuku::where('label_dicetak', true)->count();

        return view('buku.cetak', compact(
            'bukus', 'filter', 'dari', 'sampai', 'bukuIds',
            'semuaBuku', 'totalBelumDicetak', 'totalSudahDicetak'
        ));
    }

    // ================================================================
    // TANDAI DICETAK
    // ================================================================
    public function tandaiDicetak(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'Tidak ada id yang dikirim.'], 422);
        }

        KodeBuku::whereIn('id', $ids)->update([
            'label_dicetak'    => true,
            'label_dicetak_at' => now(),
        ]);

        return response()->json([
            'message' => 'Berhasil ditandai.',
            'jumlah'  => count($ids),
        ]);
    }

    // ================================================================
    // CETAK LABEL — satu buku (dari halaman detail)
    // ================================================================
    public function cetakLabel(Buku $buku)
    {
        $buku->load(['kategori', 'kodeBuku']);
        $kodeList = $buku->kodeBuku;

        return view('buku.cetak-label', compact('buku', 'kodeList'));
    }

    // ================================================================
    // SCAN — cari kode buku, kembalikan info status
    // ================================================================
    public function scan($kodeBuku)
    {
        $kode = KodeBuku::with('buku')->where('kode_buku', $kodeBuku)->first();

        if (!$kode) {
            return response()->json(['message' => 'Kode buku tidak ditemukan.'], 404);
        }

        return response()->json([
            'kode_buku' => $kode->kode_buku,
            'status'    => $kode->status,
            'buku'      => [
                'id'        => $kode->buku->id ?? null,
                'judul'     => $kode->buku->judul ?? null,
                'pengarang' => $kode->buku->pengarang ?? null,
            ],
        ]);
    }

    // ================================================================
    // UPDATE STATUS KODE — tersedia / dipinjam
    // ================================================================
    public function updateStatusKode(Request $request, KodeBuku $kodeBuku)
    {
        $validated = $request->validate([
            'status' => 'required|in:tersedia,dipinjam',
        ]);

        $kodeBuku->update(['status' => $validated['status']]);

        return back()->with('success', "Status kode {$kodeBuku->kode_buku} diubah menjadi {$validated['status']}.");
    }

    // ================================================================
    // HELPER — generate kode buku otomatis
    // ================================================================
    private function buatKodeBuku(Buku $buku, int $mulai, int $sampai): void
    {
        $kodeKategori = $buku->kategori->kode ?? 'GEN';

        for ($i = $mulai; $i <= $sampai; $i++) {
            KodeBuku::create([
                'buku_id'    => $buku->id,
                'kode_buku'  => sprintf('%s-%d-%04d', $kodeKategori, $buku->id, $i),
                'nomor_urut' => $i,
                'status'     => 'tersedia',
            ]);
        }
    }
}
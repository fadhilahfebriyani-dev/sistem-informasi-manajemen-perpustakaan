<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;
use App\Models\Pengunjung;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // ── Helper: data per bulan ──────────────────────────────
    private function getDataPerBulan(int $tahun): array
    {
        $bulanNames = [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember',
        ];

        $pinjam = DB::table('peminjamen')
            ->selectRaw('MONTH(tanggal_pinjam) as bln, COUNT(*) as total')
            ->whereYear('tanggal_pinjam', $tahun)
            ->groupBy('bln')
            ->pluck('total', 'bln');

        $kunjung = DB::table('pengunjung')
            ->selectRaw('MONTH(tanggal_kunjungan) as bln, COUNT(*) as total')
            ->whereYear('tanggal_kunjungan', $tahun)
            ->groupBy('bln')
            ->pluck('total', 'bln');

        $data = [];
        foreach ($bulanNames as $no => $nama) {
            $data[$no] = [
                'bulan'      => $nama,
                'peminjaman' => $pinjam[$no] ?? 0,
                'pengunjung' => $kunjung[$no] ?? 0,
            ];
        }

        return $data;
    }

    // ── Helper: siapkan semua variabel view ─────────────────
    private function prepareData(Request $request): array
    {
        $tahun = (int) $request->get('tahun', now()->year);

        $tahunList = [];
        for ($y = now()->year; $y >= now()->year - 5; $y--) {
            $tahunList[] = $y;
        }

        $dataPerBulan = $this->getDataPerBulan($tahun);

        $query = Peminjaman::with(['anggota', 'details.buku'])
            ->whereYear('tanggal_pinjam', $tahun);

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_akhir);
        }

        $peminjaman      = $query->latest()->get();
        $totalStokBuku   = Buku::sum('stok');
        $totalPengunjung = Pengunjung::count();
        $totalPeminjaman = Peminjaman::count();
        $totalPinjam     = collect($dataPerBulan)->sum('peminjaman');
        $totalKunjung    = collect($dataPerBulan)->sum('pengunjung');
        $rataPinjam      = round($totalPinjam / 12);
        $rataKunjung     = round($totalKunjung / 12);

        // --- FIX: Tentukan layout di sini agar tidak pernah kosong ---
        $user = Auth::user();
        $layout = ($user && $user->isAdmin()) ? 'layouts.admin' : 'layouts.app';

        return compact(
            'tahun','tahunList','dataPerBulan','peminjaman',
            'totalStokBuku','totalPengunjung','totalPeminjaman',
            'totalPinjam','totalKunjung','rataPinjam','rataKunjung',
            'layout'
        );
    }

    // ── INDEX ───────────────────────────────────────────────
    public function index(Request $request)
    {
        $data = $this->prepareData($request);
        
        // Menggunakan folder 'Laporan' (Sesuaikan case dengan folder di resources/views)
        return view('Laporan.index', $data); 
    }

    // ── EXPORT WORD ─────────────────────────────────────────
    public function exportWord(Request $request)
    {
        $data  = $this->prepareData($request);
        $tahun = $data['tahun'];

        return response(
            view('Laporan.export-word', $data)->render()
        )
        ->header('Content-Type', 'application/msword')
        ->header('Content-Disposition', 'attachment; filename="Laporan-Perpustakaan-'.$tahun.'.doc"');
    }
}
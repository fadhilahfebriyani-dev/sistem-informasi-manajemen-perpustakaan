<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $chartData = DB::table('peminjamen')
            ->selectRaw('YEAR(tanggal_pinjam) as tahun, MONTH(tanggal_pinjam) as bulan, COUNT(*) as total')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        return view('admin.dashboard', [
            'totalPetugas'    => User::where('role', 'petugas')->count(),
            'totalStokBuku'   => \App\Models\Buku::sum('stok'),
            'totalAnggota'    => \App\Models\Anggota::count(),
            'totalPeminjaman' => \App\Models\Peminjaman::count(),
            'chartData'       => $chartData,
        ]);
    }
}
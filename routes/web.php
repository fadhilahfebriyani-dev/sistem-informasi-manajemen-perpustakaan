<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\BebasPustakaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\PublikController;
use App\Http\Controllers\SurveyAdminController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }
    return Auth::user()->isAdmin()
        ? redirect('/admin/dashboard')
        : redirect('/dashboard');
});

// ================================================================
// ADMIN — Kepala Perpustakaan
// ================================================================
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('petugas', PetugasController::class)->names('admin.petugas');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPDF'])->name('admin.laporan.export');
        Route::get('/laporan/export-word', [LaporanController::class, 'exportWord'])->name('admin.laporan.word');
    });

// ================================================================
// PETUGAS — Petugas Perpustakaan
// ================================================================
Route::middleware(['auth', 'role:petugas'])->group(function () {

    // Dashboard petugas
    Route::get('/dashboard', function () {
        $chartPeminjaman = DB::table('peminjamen')->selectRaw('YEAR(tanggal_pinjam) as tahun, MONTH(tanggal_pinjam) as bulan, COUNT(*) as total')->whereRaw('tanggal_pinjam >= DATE_SUB(NOW(), INTERVAL 6 MONTH)')->groupBy('tahun', 'bulan')->orderBy('tahun')->orderBy('bulan')->get();
        $chartPengunjung = DB::table('pengunjung')->selectRaw('YEAR(tanggal_kunjungan) as tahun, MONTH(tanggal_kunjungan) as bulan, COUNT(*) as total')->whereRaw('tanggal_kunjungan >= DATE_SUB(NOW(), INTERVAL 6 MONTH)')->groupBy('tahun', 'bulan')->orderBy('tahun')->orderBy('bulan')->get();
        $pengunjungHariIni = DB::table('pengunjung')->whereDate('tanggal_kunjungan', today())->count();
        $peminjamanAktif = \App\Models\Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanTerlambat = \App\Models\Peminjaman::where('status', 'terlambat')->count();

        return view('dashboard', [
            'totalStokBuku'       => \App\Models\Buku::sum('stok'),
            'totalAnggota'        => \App\Models\Anggota::count(),
            'totalPengunjung'     => \App\Models\Pengunjung::count(),
            'totalPeminjaman'     => \App\Models\Peminjaman::count(),
            'pengunjungHariIni'   => $pengunjungHariIni,
            'peminjamanAktif'     => $peminjamanAktif,
            'peminjamanTerlambat' => $peminjamanTerlambat,
            'chartData'           => $chartPeminjaman,
            'chartPengunjung'     => $chartPengunjung,
        ]);
    })->name('dashboard');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/export-word', [LaporanController::class, 'exportWord'])->name('laporan.export-word');

    // Buku
    // PENTING: route statis harus di atas Route::resource agar tidak
    // bentrok dengan route /buku/{buku} (show) milik resource.
    Route::get('/buku/cetak', [BukuController::class, 'cetak'])->name('buku.cetak');
    Route::post('/buku/tandai-dicetak', [BukuController::class, 'tandaiDicetak'])->name('buku.tandai-dicetak'); // ← BARU
    Route::resource('buku', BukuController::class);
    Route::get('/buku/{buku}/cetak-label', [BukuController::class, 'cetakLabel'])->name('buku.cetak-label');
    Route::patch('/buku/kode/{kodeBuku}/status', [BukuController::class, 'updateStatusKode'])->name('buku.kode.status');
    Route::get('/scan/buku/{kodeBuku}', [BukuController::class, 'scan'])->name('buku.scan');

    // Kategori
    Route::get('/scan/kategori/{kode}', [KategoriController::class, 'scan'])->name('kategori.scan');
    Route::get('/kategori/cetak', [KategoriController::class, 'cetak'])->name('kategori.cetak');
    Route::resource('kategori', KategoriController::class);

    // Anggota & Pengunjung
    Route::resource('anggota', AnggotaController::class);
    Route::resource('pengunjung', PengunjungController::class);

    // Peminjaman
    Route::get('/peminjaman/kode-buku', [PeminjamanController::class, 'getKodeBuku'])->name('peminjaman.kodebuku');
    Route::post('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::resource('peminjaman', PeminjamanController::class);

    // Denda
    Route::prefix('denda')->name('denda.')->group(function () {
        Route::get('/',               [DendaController::class, 'index'])->name('index');
        Route::get('/{denda}',        [DendaController::class, 'show'])->name('show');
        Route::post('/{denda}/bayar', [DendaController::class, 'bayar'])->name('bayar');
    });

    // Bebas Pustaka
    Route::get('/bebaspustaka/cek/{anggota_id}', [BebasPustakaController::class, 'cekStatus'])->name('bebaspustaka.cek');
    Route::get('/bebaspustaka/{bebaspustaka}/cetak', [BebasPustakaController::class, 'cetak'])->name('bebaspustaka.cetak');
    Route::resource('bebaspustaka', BebasPustakaController::class);
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/survey', [SurveyAdminController::class, 'index'])->name('survey.index');
    Route::get('/survey/export', [SurveyAdminController::class, 'export'])->name('survey.export');
});

Route::get('/', [PublikController::class, 'index'])->name('publik.index');

Route::get('/survey', [PublikController::class, 'surveyForm'])->name('publik.survey');
Route::post('/survey', [PublikController::class, 'surveySimpan'])->name('publik.survey.simpan');
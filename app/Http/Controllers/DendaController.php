<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Services\DendaService;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    protected DendaService $dendaService;

    public function __construct(DendaService $dendaService)
    {
        $this->dendaService = $dendaService;
    }

    // Daftar semua denda
    public function index(Request $request)
    {
        // Sinkronkan dulu peminjaman yang overdue -> otomatis masuk tabel denda
        $this->dendaService->syncOverdue();

        // Perbaiki data lama: denda sudah lunas tapi status peminjaman
        // belum ikut berubah jadi 'dikembalikan'.
        $this->dendaService->fixLunasBelumDikembalikan();

        $query = Denda::with([
            'peminjaman.anggota',
            'peminjaman.details.buku',
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dendas = $query->paginate(15)->withQueryString();

        return view('denda.index', compact('dendas'));
    }

    // Detail satu denda
    public function show(Denda $denda)
    {
        $denda->load([
            'peminjaman.anggota',
            'peminjaman.details.buku',
        ]);

        return view('denda.show', compact('denda'));
    }

    // Konfirmasi bayar denda -> otomatis menyelesaikan peminjaman terkait
    public function bayar(Denda $denda)
    {
        if ($denda->isLunas()) {
            return back()->with('info', 'Denda ini sudah lunas.');
        }

        $this->dendaService->bayarDenda($denda);

        return back()->with(
            'success',
            'Pembayaran denda sebesar ' . $denda->total_denda_format .
            ' berhasil dikonfirmasi! Status peminjaman otomatis diperbarui menjadi "Dikembalikan".'
        );
    }
}
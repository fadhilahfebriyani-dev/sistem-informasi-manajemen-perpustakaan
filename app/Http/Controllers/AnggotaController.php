<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
{
        $search = $request->search;
        $anggotum = Anggota::when($search, fn($q) => $q
            ->where('nama',  'like', "%$search%")
            ->orWhere('kelas', 'like', "%$search%")
            ->orWhere('nisn',  'like', "%$search%"))
        ->latest()->paginate(10)->withQueryString();

    $totalAnggota  = Anggota::count();
    $totalMeminjam = Anggota::whereHas('peminjaman', fn($q) =>
        $q->where('status', 'dipinjam'))->count();
    $totalTerlambat = Anggota::whereHas('peminjaman', fn($q) =>
        $q->where('status', 'terlambat'))->count();
    $totalLunas = $totalAnggota - $totalMeminjam;

    return view('anggota.index', compact(
        'anggotum', 'search',
        'totalAnggota', 'totalMeminjam', 'totalTerlambat', 'totalLunas'
    ));
}

    public function create()
    {
        return view('anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'nisn'  => 'nullable|string|max:20',
            'kelas' => 'required|string|max:100',
            //'no_hp' => 'required|string|max:20',
        ]);

        Anggota::create($request->only('nama', 'nisn', 'kelas'));

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Anggota $anggotum)
    {
        return view('anggota.edit', compact('anggotum'));
    }

    public function update(Request $request, Anggota $anggotum)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'nisn'  => 'nullable|string|max:20',
            'kelas' => 'required|string|max:100',
            //'no_hp' => 'required|string|max:20',
        ]);

        $anggotum->update($request->only('nama', 'nisn', 'kelas'));

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil diupdate.');
    }

    public function destroy(Anggota $anggotum)
    {
        $anggotum->delete();
        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
    public function show(Anggota $anggotum)
    {
        $anggotum->load([
        'peminjaman.details.buku',
    ]);

        $pinjamAktif  = $anggotum->peminjaman->where('status', 'dipinjam');
        $riwayat      = $anggotum->peminjaman->where('status', '!=', 'dipinjam');
        $bebasPustaka = \App\Models\BebasPustaka::where('anggota_id', $anggotum->id)->first();

        return view('anggota.show', compact('anggotum', 'pinjamAktif', 'riwayat', 'bebasPustaka'));
    }
}
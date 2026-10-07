<?php

namespace App\Http\Controllers;

use App\Models\Pengunjung;
use Illuminate\Http\Request;

class PengunjungController extends Controller
{
    public function index()
    {
        $pengunjung = Pengunjung::latest()->paginate(10);
        return view('pengunjung.index', compact('pengunjung'));
    }

    public function create()
    {
        return view('pengunjung.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'               => 'required|string|max:255',
            'tanggal_kunjungan'  => 'required|date',
            'keperluan'          => 'required|string|max:500',
        ]);

        Pengunjung::create($request->only('nama', 'tanggal_kunjungan', 'keperluan'));

        return redirect()->route('pengunjung.index')->with('success', 'Data pengunjung berhasil ditambahkan.');
    }

    public function edit(Pengunjung $pengunjung)
    {
        return view('pengunjung.edit', compact('pengunjung'));
    }

    public function update(Request $request, Pengunjung $pengunjung)
    {
        $request->validate([
            'nama'               => 'required|string|max:255',
            'tanggal_kunjungan'  => 'required|date',
            'keperluan'          => 'required|string|max:500',
        ]);

        $pengunjung->update($request->only('nama', 'tanggal_kunjungan', 'keperluan'));

        return redirect()->route('pengunjung.index')->with('success', 'Data pengunjung berhasil diupdate.');
    }

    public function destroy(Pengunjung $pengunjung)
    {
        $pengunjung->delete();
        return redirect()->route('pengunjung.index')->with('success', 'Data pengunjung berhasil dihapus.');
    }
}

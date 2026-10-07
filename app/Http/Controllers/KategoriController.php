<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;

    $kategori = \App\Models\Kategori::withCount('bukus')
        ->when($search, fn($q) => $q
            ->where('nama',  'like', "%$search%")
            ->orWhere('kode', 'like', "%$search%"))
        ->orderBy('kode')
        ->paginate(15)
        ->withQueryString();

    $totalKategori = \App\Models\Kategori::count();
    $totalBuku     = \App\Models\Buku::count();
    $totalStok     = \App\Models\Buku::sum('stok');

    return view('kategori.index', compact(
        'kategori', 'search',
        'totalKategori', 'totalBuku', 'totalStok'
    ));
}

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:kategoris,kode',
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        Kategori::create($request->only('kode', 'nama', 'deskripsi'));

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:kategoris,kode,' . $kategori->id,
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($request->only('kode', 'nama', 'deskripsi'));

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
    public function cetak()
{
    $kategoris = \App\Models\Kategori::orderBy('kode')->get();
    return view('kategori.cetak', compact('kategoris'));
}
public function scan($kode)
{
    $kategori = \App\Models\Kategori::with('buku')
        ->where('kode', $kode)
        ->firstOrFail();

    return view('scan.kategori', compact('kategori'));
}
}
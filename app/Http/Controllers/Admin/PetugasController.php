<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = User::where('role', 'petugas')->latest()->get();
        return view('admin.petugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('admin.petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'petugas',
        ]);

        return redirect()->route('admin.petugas.index')
            ->with('success', 'Akun petugas berhasil ditambahkan.');
    }

    public function edit(User $petugas)
    {
        abort_if($petugas->role !== 'petugas', 403);
        return view('admin.petugas.edit', compact('petugas'));
    }

    public function update(Request $request, User $petugas)
    {
        abort_if($petugas->role !== 'petugas', 403);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $petugas->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $petugas->update([
            'name'  => $request->name,
            'email' => $request->email,
            // Hanya update password jika diisi
            ...($request->filled('password')
                ? ['password' => Hash::make($request->password)]
                : []),
        ]);

        return redirect()->route('admin.petugas.index')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy(User $petugas)
    {
        abort_if($petugas->role !== 'petugas', 403);
        $petugas->delete();

        return redirect()->route('admin.petugas.index')
            ->with('success', 'Akun petugas berhasil dihapus.');
    }
}
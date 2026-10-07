<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Proteksi route berdasarkan role.
     *
     * Penggunaan di routes:
     *   ->middleware('role:admin')          // hanya admin (kepala perpustakaan)
     *   ->middleware('role:petugas')        // hanya petugas perpustakaan
     *   ->middleware('role:admin,petugas')  // keduanya boleh
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            // Arahkan kembali ke dashboard sesuai role masing-masing
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect('/dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
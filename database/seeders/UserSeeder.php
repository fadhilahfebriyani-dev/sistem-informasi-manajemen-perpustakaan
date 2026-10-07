<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // ADMIN = Kepala Perpustakaan (pengurus sistem)
        // =============================================
        User::updateOrCreate(
            ['email' => 'admin@simperpus.com'],
            [
                'name'     => 'Kepala Perpustakaan',
                'email'    => 'admin@simperpus.com',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // ==========================================================
        // PETUGAS = Petugas Perpustakaan (pengelola buku, anggota dll)
        // ==========================================================
        User::updateOrCreate(
            ['email' => 'petugas@simperpus.com'],
            [
                'name'     => 'Petugas Perpustakaan',
                'email'    => 'petugas@simperpus.com',
                'password' => Hash::make('petugas123'),
                'role'     => 'petugas',
            ]
        );
    }
}
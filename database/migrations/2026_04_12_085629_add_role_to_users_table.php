<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom role ke tabel users.
     * - 'admin'   = Kepala Perpustakaan (pengurus sistem)
     * - 'petugas' = Petugas Perpustakaan (pengelola data)
     *
     * Jalankan: php artisan migrate
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu agar tidak error jika kolom sudah ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('petugas')->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
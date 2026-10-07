<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konfigurasi_denda', function (Blueprint $table) {
            $table->id();
            $table->string('kunci')->unique();
            $table->string('nilai');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        DB::table('konfigurasi_denda')->insert([
            [
                'kunci'      => 'denda_per_hari',
                'nilai'      => '3000',
                'keterangan' => 'Nominal denda keterlambatan per hari (Rupiah)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kunci'      => 'maksimal_hari_pinjam',
                'nilai'      => '7',
                'keterangan' => 'Batas maksimal hari peminjaman buku',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_denda');
    }
};
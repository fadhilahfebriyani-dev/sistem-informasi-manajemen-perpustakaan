<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel ini menyimpan detail buku yang dipinjam per transaksi.
     * Satu transaksi peminjaman bisa memiliki banyak buku (detail).
     * Jalankan: php artisan migrate
     */
    public function up(): void
    {
        // Hapus kolom buku_id dari peminjamen karena dipindah ke detail
        if (Schema::hasColumn('peminjamen', 'buku_id')) {
            Schema::table('peminjamen', function (Blueprint $table) {
                $table->dropForeign(['buku_id']);
                $table->dropColumn('buku_id');
            });
        }

        // Buat tabel detail peminjaman
        Schema::create('detail_peminjamen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')
                  ->constrained('peminjamen')
                  ->onDelete('cascade');
            $table->foreignId('buku_id')
                  ->constrained('bukus')
                  ->onDelete('cascade');
            $table->foreignId('kode_buku_id')
                  ->nullable()
                  ->constrained('kodebukus')
                  ->onDelete('set null');
            $table->string('kode_buku')->nullable(); // simpan kode sebagai string untuk histori
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjamen');

        // Kembalikan kolom buku_id ke peminjamen jika di-rollback
        Schema::table('peminjamen', function (Blueprint $table) {
            $table->dropForeignIfExists('peminjamen_buku_id_foreign');
        });
    }
};
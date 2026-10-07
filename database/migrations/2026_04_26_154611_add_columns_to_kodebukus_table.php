<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kodebukus', function (Blueprint $table) {
            $table->unsignedBigInteger('buku_id')->after('id');
            $table->string('kode_buku')->after('buku_id');
            $table->integer('nomor_urut')->after('kode_buku');
            $table->enum('status', ['tersedia', 'dipinjam'])->default('tersedia')->after('nomor_urut');

            $table->foreign('buku_id')->references('id')->on('bukus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('kodebukus', function (Blueprint $table) {
            $table->dropForeign(['buku_id']);
            $table->dropColumn(['buku_id', 'kode_buku', 'nomor_urut', 'status']);
        });
    }
};
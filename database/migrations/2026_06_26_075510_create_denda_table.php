<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('denda', function (Blueprint $table) {
            $table->id();

            // FK ke tabel peminjamen (nama tabel asli di project)
            $table->unsignedBigInteger('peminjaman_id');
            $table->foreign('peminjaman_id')
                  ->references('id')
                  ->on('peminjamen')
                  ->onDelete('cascade');

            $table->integer('hari_terlambat')->unsigned();
            $table->decimal('denda_per_hari', 10, 2)->default(3000.00);
            $table->decimal('total_denda', 10, 2);
            $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->text('catatan')->nullable();
            $table->timestamp('dibayar_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
};
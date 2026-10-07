<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('survey_kepuasan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_responden')->nullable();
            $table->string('kelas')->nullable();
            $table->enum('jenis_responden', ['siswa','guru','umum'])->default('siswa');
            $table->tinyInteger('q_layanan_petugas');
            $table->tinyInteger('q_kemudahan_akses');
            $table->tinyInteger('q_kelengkapan_koleksi');
            $table->tinyInteger('q_kondisi_ruang');
            $table->tinyInteger('q_manfaat');
            $table->tinyInteger('q_rekomendasi');
            $table->text('saran')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('survey_kepuasan'); }
};

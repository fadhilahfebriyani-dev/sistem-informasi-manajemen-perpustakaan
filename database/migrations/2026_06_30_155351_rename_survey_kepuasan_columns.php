<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Mapping kolom lama -> baru:
     *   q_layanan_petugas     -> q_kecepatan_layanan
     *   q_kelengkapan_koleksi -> q_akurasi_ketersediaan
     *   q_manfaat             -> q_efektivitas_belajar
     *   q_kemudahan_akses     -> (tetap sama, tidak berubah)
     *   q_rekomendasi         -> (tetap sama, tidak berubah)
     *   q_kondisi_ruang       -> dihapus (pertanyaan sudah tidak dipakai di form baru)
     */
    public function up(): void
    {
        Schema::table('survey_kepuasan', function (Blueprint $table) {
            $table->renameColumn('q_layanan_petugas', 'q_kecepatan_layanan');
            $table->renameColumn('q_kelengkapan_koleksi', 'q_akurasi_ketersediaan');
            $table->renameColumn('q_manfaat', 'q_efektivitas_belajar');
        });

        Schema::table('survey_kepuasan', function (Blueprint $table) {
            if (Schema::hasColumn('survey_kepuasan', 'q_kondisi_ruang')) {
                $table->dropColumn('q_kondisi_ruang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_kepuasan', function (Blueprint $table) {
            if (!Schema::hasColumn('survey_kepuasan', 'q_kondisi_ruang')) {
                $table->unsignedTinyInteger('q_kondisi_ruang')->nullable()->after('q_kemudahan_akses');
            }
        });

        Schema::table('survey_kepuasan', function (Blueprint $table) {
            $table->renameColumn('q_kecepatan_layanan', 'q_layanan_petugas');
            $table->renameColumn('q_akurasi_ketersediaan', 'q_kelengkapan_koleksi');
            $table->renameColumn('q_efektivitas_belajar', 'q_manfaat');
        });
    }
};
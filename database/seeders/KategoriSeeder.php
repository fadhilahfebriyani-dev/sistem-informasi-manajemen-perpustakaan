<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['kode' => '000', 'nama' => 'Karya Umum',               'deskripsi' => 'Ensiklopedia, majalah, jurnal, karya umum lainnya'],
            ['kode' => '100', 'nama' => 'Filsafat & Psikologi',     'deskripsi' => 'Buku pengembangan diri, filsafat, psikologi'],
            ['kode' => '200', 'nama' => 'Agama',                    'deskripsi' => 'Buku-buku keagamaan dan kerohanian'],
            ['kode' => '300', 'nama' => 'Ilmu Sosial',              'deskripsi' => 'PKN, sosiologi, ekonomi, ilmu sosial lainnya'],
            ['kode' => '400', 'nama' => 'Bahasa',                   'deskripsi' => 'Bahasa Inggris, Bahasa Indonesia, kamus, linguistik'],
            ['kode' => '500', 'nama' => 'Sains & Matematika',       'deskripsi' => 'Fisika, biologi, kimia, matematika, astronomi'],
            ['kode' => '600', 'nama' => 'Kesehatan & Ilmu Terapan', 'deskripsi' => 'Kesehatan, teknik, pertanian, manajemen'],
            ['kode' => '700', 'nama' => 'Seni & Rekreasi',          'deskripsi' => 'Semua yang berhubungan dengan kesenian dan rekreasi'],
            ['kode' => '800', 'nama' => 'Sastra',                   'deskripsi' => 'Novel, puisi, drama, karya sastra'],
            ['kode' => '900', 'nama' => 'Geografi & Sejarah',       'deskripsi' => 'Peta, biografi, sejarah dunia, geografi'],
            ['kode' => 'FIK', 'nama' => 'Fiksi',                    'deskripsi' => 'Koleksi buku fiksi perpustakaan'],
            ['kode' => 'REF', 'nama' => 'Referensi',                'deskripsi' => 'Koleksi referensi perpustakaan'],
        ];

        foreach ($kategoris as $k) {
            DB::table('kategoris')->insertOrIgnore(array_merge($k, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
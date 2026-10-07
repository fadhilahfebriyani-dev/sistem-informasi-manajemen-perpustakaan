<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = ['kode', 'nama', 'deskripsi'];

    // Relasi ke buku (jika kolom kategori di tabel bukus menyimpan kode DDC)
    public function bukus()
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}
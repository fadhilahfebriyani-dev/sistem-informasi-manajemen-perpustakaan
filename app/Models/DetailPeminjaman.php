<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjamen';

    protected $fillable = [
        'peminjaman_id',
        'buku_id',
        'kode_buku_id',
        'kode_buku',
    ];

    // Relasi ke peminjaman (induk)
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    // Relasi ke buku
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    // Relasi ke kode buku
    public function kodeBuku()
    {
        return $this->belongsTo(KodeBuku::class, 'kode_buku_id');
    }
}
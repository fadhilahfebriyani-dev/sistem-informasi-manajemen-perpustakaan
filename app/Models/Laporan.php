<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $fillable = [
        'jumlah_buku',
        'jumlah_pengunjung',
        'jumlah_peminjaman',
        'tanggal_laporan'
    ];
}
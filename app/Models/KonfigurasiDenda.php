<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonfigurasiDenda extends Model
{
    protected $table = 'konfigurasi_denda';

    protected $fillable = [
        'kunci',
        'nilai',
        'keterangan',
    ];
}
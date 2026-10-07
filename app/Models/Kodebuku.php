<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeBuku extends Model
{
    protected $table = 'kodebukus'; // ← sesuai nama tabel asli

    protected $fillable = [
        'buku_id',
        'kode_buku',
        'nomor_urut',
        'status',
        'label_dicetak',
        'label_dicetak_at',
    ];

    protected $casts = [
        'label_dicetak'    => 'boolean',
        'label_dicetak_at' => 'datetime',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
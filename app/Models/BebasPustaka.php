<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BebasPustaka extends Model
{
    protected $table = 'bebas_pustakas';
    protected $fillable = [
        'anggota_id', 'nomor_surat', 'tanggal',
        'tahun_ajaran', 'keperluan', 'status',
    ];
    protected $casts = ['tanggal' => 'date'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
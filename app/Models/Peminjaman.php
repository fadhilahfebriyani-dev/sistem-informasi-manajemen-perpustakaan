<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamen';

    protected $fillable = [
        'anggota_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_kembali_aktual',
        'status',
        'terlambat',
    ];

    protected $casts = [
        'tanggal_pinjam'         => 'date',
        'tanggal_kembali'        => 'date',
        'tanggal_kembali_aktual' => 'date',
        'terlambat'              => 'boolean',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id');
    }

    public function bukus()
    {
        return $this->hasManyThrough(
            Buku::class,
            DetailPeminjaman::class,
            'peminjaman_id',
            'id',
            'id',
            'buku_id'
        );
    }

    public function getBukuPertamaAttribute()
    {
        return $this->details->first()?->buku;
    }

    public function denda(): HasOne
    {
        return $this->hasOne(Denda::class, 'peminjaman_id');
    }

    // ─── Status keterlambatan ────────────────────────────────────────────────

    /**
     * Cek apakah peminjaman ini terlambat.
     * - status 'dipinjam'  : terlambat jika sudah lewat tanggal_kembali (masih di tangan peminjam)
     * - status 'terlambat' : buku sudah fisik dikembalikan tapi denda belum lunas -> selalu true
     * - status 'dikembalikan' : sudah selesai (denda sudah lunas atau memang tidak terlambat) -> false
     */
    public function isTerlambat(): bool
    {
        if ($this->status === 'dipinjam') {
            if ($this->tanggal_kembali === null) {
                return false;
            }

            return now()->startOfDay()->gt(
                $this->tanggal_kembali->copy()->startOfDay()
            );
        }

        return $this->status === 'terlambat';
    }

    /**
     * Hitung jumlah hari keterlambatan (integer, selalu >= 0).
     * - status 'dipinjam'  : dihitung dari tanggal_kembali s/d hari ini (terus berjalan)
     * - status 'terlambat' : dihitung dari tanggal_kembali s/d tanggal_kembali_aktual (dibekukan,
     *                        karena buku sudah dikembalikan, hanya menunggu pembayaran denda)
     * - lainnya            : 0
     */
    public function getHariTerlambat(): int
    {
        if ($this->tanggal_kembali === null) {
            return 0;
        }

        if ($this->status === 'dipinjam') {
            if (! $this->isTerlambat()) {
                return 0;
            }
            $acuan = now()->startOfDay();
        } elseif ($this->status === 'terlambat' && $this->tanggal_kembali_aktual) {
            $acuan = $this->tanggal_kembali_aktual->copy()->startOfDay();
        } else {
            return 0;
        }

        $batas        = $this->tanggal_kembali->copy()->startOfDay();
        $selisihDetik = abs($acuan->diffInSeconds($batas, true));

        return max(0, (int) floor($selisihDetik / 86400));
    }
}
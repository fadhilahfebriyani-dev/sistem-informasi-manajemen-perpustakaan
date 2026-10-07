<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    protected $table = 'denda';

    protected $fillable = [
        'peminjaman_id',   // FK ke tabel peminjamen (kolom bernama peminjaman_id)
        'hari_terlambat',
        'denda_per_hari',
        'total_denda',
        'status',
        'catatan',
        'dibayar_at',
    ];

    protected $casts = [
        'dibayar_at'     => 'datetime',
        'denda_per_hari' => 'decimal:2',
        'total_denda'    => 'decimal:2',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────────────

    // Ke model Peminjaman (yang $table = 'peminjamen')
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    // ─── Accessor ─────────────────────────────────────────────────────────────

    public function getTotalDendaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total_denda, 0, ',', '.');
    }

    public function getDendaPerHariFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->denda_per_hari, 0, ',', '.');
    }

    // ─── Helper ───────────────────────────────────────────────────────────────

    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }
}
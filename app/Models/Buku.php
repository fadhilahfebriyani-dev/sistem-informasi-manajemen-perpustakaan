<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Buku extends Model
{
    protected $table    = 'bukus';
    protected $fillable = [
        'judul', 'pengarang', 'kategori_id',
        'stok', 'penerbit', 'tahun_terbit',
        'sampul',   // ← kolom baru
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function kodeBuku()
    {
        return $this->hasMany(KodeBuku::class, 'buku_id');
    }

    public function getTersediaAttribute(): int
    {
        return $this->kodeBuku()->where('status', 'tersedia')->count();
    }

    /**
     * URL sampul buku.
     * Jika ada file → URL dari storage.
     * Jika tidak ada → null (view akan tampilkan placeholder).
     */
    public function getSampulUrlAttribute(): ?string
    {
        if ($this->sampul && Storage::disk('public')->exists($this->sampul)) {
            return Storage::url($this->sampul);
        }
        return null;
    }

    /**
     * Hapus file sampul lama dari storage.
     */
    public function hapusSampul(): void
    {
        if ($this->sampul) {
            Storage::disk('public')->delete($this->sampul);
        }
    }
}
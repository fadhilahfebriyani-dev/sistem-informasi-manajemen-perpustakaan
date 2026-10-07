<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SurveyKepuasan extends Model
{
    protected $table    = 'survey_kepuasan';
    protected $fillable = [
        'nama_responden','kelas','jenis_responden',
        'q_kecepatan_layanan','q_kemudahan_akses','q_akurasi_ketersediaan',
        'q_efektivitas_belajar','q_rekomendasi','saran',
    ];
    protected $casts = [
        'q_kecepatan_layanan'=>'integer','q_kemudahan_akses'=>'integer',
        'q_akurasi_ketersediaan'=>'integer','q_efektivitas_belajar'=>'integer',
        'q_rekomendasi'=>'integer',
    ];

    public static function pertanyaan(): array
    {
        return [
            'q_kecepatan_layanan'    => 'Kecepatan dan efisiensi pelayanan petugas setelah penerapan SIMPERPUS',
            'q_kemudahan_akses'      => 'Kemudahan pencarian (OPAC) dan peminjaman buku via SIMPERPUS',
            'q_akurasi_ketersediaan' => 'Keakuratan informasi ketersediaan koleksi buku di SIMPERPUS',
            'q_efektivitas_belajar'  => 'Manfaat dan efektivitas SIMPERPUS untuk kegiatan belajar',
            'q_rekomendasi'          => 'Kemungkinan merekomendasikan SIMPERPUS kepada pengguna lain',
        ];
    }

    public function getRataRataAttribute(): float
    {
        $fields = array_keys(self::pertanyaan());
        $total  = array_sum(array_map(fn($f) => $this->$f ?? 0, $fields));
        return round($total / count($fields), 2);
    }

    public static function labelNilai(float $n): string
    {
        return match(true) {
            $n >= 4.5 => 'Sangat Puas',
            $n >= 3.5 => 'Puas',
            $n >= 2.5 => 'Cukup',
            $n >= 1.5 => 'Tidak Puas',
            default   => 'Sangat Tidak Puas',
        };
    }
}
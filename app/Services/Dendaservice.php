<?php

namespace App\Services;

use App\Models\Denda;
use App\Models\KonfigurasiDenda;
use App\Models\Peminjaman;

class DendaService
{
    /**
     * Ambil tarif denda per hari dari tabel konfigurasi_denda.
     * Fallback ke 3000 jika belum di-setting.
     */
    protected function tarifPerHari(): float
    {
        $konfigurasi = KonfigurasiDenda::where('kunci', 'denda_per_hari')->first();
        return $konfigurasi ? (float) $konfigurasi->nilai : 3000;
    }

    /**
     * Cari peminjaman yang masih di tangan peminjam (status 'dipinjam')
     * dan sudah lewat tanggal_kembali, lalu buat/update baris Denda-nya.
     * Peminjaman berstatus 'terlambat' (sudah fisik dikembalikan, tinggal
     * menunggu pembayaran) TIDAK disentuh di sini, karena hari
     * keterlambatannya sudah dibekukan saat buku dikembalikan.
     */
    public function syncOverdue(): void
    {
        $overdue = Peminjaman::where('status', 'dipinjam')
            ->whereNotNull('tanggal_kembali')
            ->whereDate('tanggal_kembali', '<', now()->startOfDay())
            ->get();

        foreach ($overdue as $peminjaman) {
            $this->hitungDenda($peminjaman);
        }
    }

    /**
     * Hitung & simpan/update denda untuk satu peminjaman.
     * Aman dipanggil berkali-kali karena pakai updateOrCreate.
     */
    public function hitungDenda(Peminjaman $peminjaman): Denda
    {
        $hariTerlambat = max(0, $peminjaman->getHariTerlambat());
        $tarif         = $this->tarifPerHari();
        $totalDenda    = $hariTerlambat * $tarif;

        $statusDendaLama = Denda::where('peminjaman_id', $peminjaman->id)->value('status');

        return Denda::updateOrCreate(
            ['peminjaman_id' => $peminjaman->id],
            [
                'hari_terlambat' => $hariTerlambat,
                'denda_per_hari' => $tarif,
                'total_denda'    => $totalDenda,
                // Jangan timpa balik ke belum_bayar kalau ternyata sudah lunas
                'status'         => $statusDendaLama === 'lunas' ? 'lunas' : 'belum_bayar',
            ]
        );
    }

    /**
     * Perbaikan otomatis: cari semua denda yang sudah 'lunas' tapi
     * peminjaman terkait masih tersangkut di status 'terlambat'
     * (misalnya data lama yang dibayar sebelum logika auto-update ini ada),
     * lalu samakan statusnya jadi 'dikembalikan'.
     */
    public function fixLunasBelumDikembalikan(): void
    {
        Denda::where('status', 'lunas')
            ->whereHas('peminjaman', fn ($q) => $q->where('status', 'terlambat'))
            ->with('peminjaman')
            ->get()
            ->each(function (Denda $denda) {
                $denda->peminjaman?->update(['status' => 'dikembalikan']);
            });
    }

    /**
     * Tandai denda lunas, dan otomatis selesaikan peminjaman terkait
     * (status berubah jadi 'dikembalikan') karena buku dianggap
     * benar-benar selesai begitu dendanya dibayar.
     */
    public function bayarDenda(Denda $denda): Denda
    {
        $denda->update([
            'status'     => 'lunas',
            'dibayar_at' => now(),
        ]);

        $peminjaman = $denda->peminjaman;

        if ($peminjaman && $peminjaman->status === 'terlambat') {
            $peminjaman->update([
                'status' => 'dikembalikan',
            ]);
        }

        return $denda;
    }
}
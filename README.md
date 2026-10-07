# Sistem Informasi Manajemen Perpustakaan

Sistem informasi perpustakaan berbasis website dengan standar ISO 9001:2015 di SMA Negeri 5 Tebo. Dikembangkan sebagai proyek skripsi Sistem Informasi, UIN Sulthan Thaha Saifuddin Jambi.

## Teknologi
- Laravel
- PHP
- MySQL

## Fitur Utama
- Dashboard admin
- Manajemen data petugas
- Manajemen data buku dan anggota
- Peminjaman dan pengembalian buku
- Perhitungan denda keterlambatan otomatis
- Laporan perpustakaan

## Cara Menjalankan
1. Clone repository ini
2. Jalankan `composer install`
3. Salin `.env.example` menjadi `.env`, lalu atur koneksi database
4. Jalankan `php artisan key:generate`
5. Jalankan `php artisan migrate --seed`
6. Jalankan `php artisan serve`

## Catatan
Repository ini tidak menyertakan data asli. Seluruh data yang digunakan adalah data contoh.

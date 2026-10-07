<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Bebas Pustaka — {{ $bebaspustaka->anggota->nama }}</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Times New Roman',Times,serif;font-size:12pt;color:#000;background:#f5f5f5}
.page{width:210mm;min-height:297mm;margin:10mm auto;padding:20mm 25mm 20mm 30mm;background:#fff;box-shadow:0 2px 12px rgba(0,0,0,.1);position:relative}

/* KOP */
.kop{display:flex;align-items:center;gap:14px;padding-bottom:10px;border-bottom:3px solid #000}
.kop img{width:72px;height:72px;object-fit:contain}
.kop-teks{flex:1;text-align:center}
.kop-instansi{font-size:10pt;line-height:1.4}
.kop-sekolah{font-size:16pt;font-weight:bold;letter-spacing:.5px;margin:2px 0}
.kop-alamat{font-size:8.5pt;line-height:1.5;color:#333}
.garis2{height:2px;background:#000;margin:3px 0 16px}

/* JUDUL */
.judul{text-align:center;margin:16px 0 4px}
.judul h2{font-size:14pt;font-weight:bold;text-decoration:underline;letter-spacing:1px}
.nomor{text-align:center;font-size:11pt;margin-bottom:18px}

/* BODY */
.paragraf{text-indent:1.5cm;text-align:justify;line-height:1.9;margin-bottom:12px;font-size:12pt}
.data-tabel{margin:8px 0 12px 1.5cm;width:calc(100% - 1.5cm)}
.data-tabel td{padding:2px 0;vertical-align:top;font-size:12pt;line-height:1.7}
.data-tabel td.lbl{width:130px}
.data-tabel td.sep{width:14px}

/* TANDA TANGAN */
.ttd-wrap{display:flex;justify-content:flex-end;margin-top:28px}
.ttd-box{text-align:center;width:240px}
.ttd-ruang{height:72px}
.ttd-nama{font-weight:bold;text-decoration:underline;font-size:12pt}
.ttd-nip{font-size:10pt}

/* FOOTER */
.footer-note{position:absolute;bottom:18mm;left:30mm;right:25mm;font-size:8pt;color:#888;border-top:1px solid #ccc;padding-top:6px}

/* PRINT */
@media print{
    body{background:#fff}
    .page{margin:0;padding:20mm 25mm 20mm 30mm;box-shadow:none}
    .no-print{display:none!important}
}
</style>
</head>
<body>

{{-- TOMBOL --}}
<div class="no-print" style="text-align:center;padding:16px;display:flex;gap:10px;justify-content:center">
    <button onclick="window.print()" style="background:#2563eb;color:#fff;border:none;padding:10px 28px;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer">
        Cetak / Simpan PDF
    </button>
    <a href="{{ route('bebaspustaka.show', $bebaspustaka->id) }}"
        style="background:#f3f4f6;color:#374151;border:none;padding:10px 20px;border-radius:8px;font-size:14px;text-decoration:none;display:inline-flex;align-items:center">
        Kembali
    </a>
</div>

<div class="page">
    {{-- KOP SURAT --}}
    <div class="kop">
        <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo"
            onerror="this.style.display='none'">
        <div class="kop-teks">
            <div class="kop-instansi">PEMERINTAH KABUPATEN TEBO<br>DINAS PENDIDIKAN DAN KEBUDAYAAN</div>
            <div class="kop-sekolah">SMA NEGERI 5 TEBO</div>
            <div class="kop-alamat">
                Jl. Anggrek, Desa Suka Damai, Kec. Rimbo Ulu, Kab. Tebo, Provinsi Jambi<br>
                Email: sman5tebo@gmail.com &nbsp;|&nbsp; Akreditasi: A
            </div>
        </div>
    </div>
    <div class="garis2"></div>

    {{-- JUDUL --}}
    <div class="judul"><h2>SURAT KETERANGAN BEBAS PUSTAKA</h2></div>
    <div class="nomor">Nomor: {{ $bebaspustaka->nomor_surat ?? '............/BP/SMAN5TBO/........' }}</div>

    {{-- ISI --}}
    <div class="paragraf">
        Yang bertanda tangan di bawah ini, Kepala Perpustakaan SMA Negeri 5 Tebo,
        Kabupaten Tebo, Provinsi Jambi, dengan ini menerangkan bahwa:
    </div>

    <table class="data-tabel">
        <tr><td class="lbl">Nama Lengkap</td><td class="sep">:</td>
            <td><strong>{{ $bebaspustaka->anggota->nama }}</strong></td></tr>
        <tr><td class="lbl">Kelas</td><td class="sep">:</td>
            <td>{{ $bebaspustaka->anggota->kelas }}</td></tr>
        <tr><td class="lbl">No. Telepon</td><td class="sep">:</td>
            <td>{{ $bebaspustaka->anggota->no_hp }}</td></tr>
        <tr><td class="lbl">Tahun Ajaran</td><td class="sep">:</td>
            <td>{{ $bebaspustaka->tahun_ajaran }}</td></tr>
    </table>

    <div class="paragraf">
        Adalah benar bahwa siswa/i tersebut di atas <strong>tidak memiliki tanggungan
        peminjaman buku</strong> dan telah memenuhi seluruh kewajiban sebagai anggota
        Perpustakaan SMA Negeri 5 Tebo. Dengan demikian, yang bersangkutan dinyatakan
        <strong>BEBAS PUSTAKA</strong>.
    </div>

    <div class="paragraf">
        Surat keterangan ini dibuat dengan sesungguhnya untuk digunakan sebagai
        persyaratan <strong>{{ $bebaspustaka->keperluan }}</strong> dan dapat
        dipergunakan sebagaimana mestinya.
    </div>

    {{-- TTD --}}
    <div class="ttd-wrap">
        <div class="ttd-box">
            <div style="margin-bottom:6px">
                Tebo, {{ \Carbon\Carbon::parse($bebaspustaka->tanggal)->translatedFormat('d F Y') }}
            </div>
            <div style="margin-bottom:6px">Kepala Perpustakaan,</div>
            <div class="ttd-ruang"></div>
            <div class="ttd-nama">(______________________)</div>
            <div class="ttd-nip">NIP. _____________________</div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer-note">
        * Diterbitkan melalui Sistem Informasi Manajemen Perpustakaan SIMPERPUS pada
        {{ now()->translatedFormat('d F Y') }}. Dokumen ini sah apabila ditandatangani oleh pejabat yang berwenang.
    </div>
</div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Label — {{ $buku->judul }}</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Arial,sans-serif;background:#f3f4f6;padding:16px}

.no-print{background:#fff;border-radius:10px;padding:12px 16px;margin-bottom:16px;
    display:flex;align-items:center;justify-content:space-between;
    box-shadow:0 1px 4px rgba(0,0,0,.08)}
.no-print h2{font-size:14px;font-weight:600;color:#111827}
.no-print p{font-size:12px;color:#9ca3af;margin-top:2px}
.btn-cetak{background:#1a237e;color:#fff;border:none;padding:8px 18px;
    border-radius:8px;font-size:13px;font-weight:600;cursor:pointer}
.btn-back{background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;
    padding:8px 12px;border-radius:8px;font-size:13px;
    cursor:pointer;text-decoration:none;display:inline-flex;align-items:center}

/* GRID */
.label-grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:8px;
    max-width:860px;
    margin:0 auto;
}

/* LABEL SIMPEL */
.label{
    background:#fff;
    border:1px solid #d1d5db;
    border-radius:6px;
    display:flex;
    flex-direction:column;
    align-items:center;
    padding:8px 6px 6px;
    gap:4px;
    page-break-inside:avoid;
    break-inside:avoid;
}

/* Header kecil */
.label-header{
    display:flex;
    align-items:center;
    gap:4px;
}
.label-header img{
    width:14px;
    height:14px;
    border-radius:50%;
}
.label-header span{
    font-size:6px;
    color:#9ca3af;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:.05em;
}

/* DDC pill */
.label-ddc{
    font-size:11px;
    font-weight:700;
    padding:1px 8px;
    border-radius:100px;
    letter-spacing:.04em;
}

/* QR */
.label-qr canvas{
    width:80px !important;
    height:80px !important;
    display:block;
}

/* Kode buku */
.label-kode{
    font-size:7.5px;
    font-family:monospace;
    font-weight:700;
    color:#1a237e;
    letter-spacing:.04em;
}

/* Divider */
.label-line{
    width:100%;
    border:none;
    border-top:1px dashed #e5e7eb;
}

/* Judul */
.label-judul{
    font-size:6.5px;
    color:#374151;
    text-align:center;
    line-height:1.4;
    font-weight:600;
    max-width:90px;
    overflow:hidden;
    display:-webkit-box;
    -webkit-line-clamp:2;
    -webkit-box-orient:vertical;
}

@media print {
    body{background:#fff;padding:0}
    .no-print{display:none!important}
    .label-grid{
        grid-template-columns:repeat(5,1fr);
        gap:6px;
        max-width:100%;
    }
    .label{border:1px solid #000;}
    @page{size:A4 portrait;margin:8mm}
}
</style>
</head>
<body>

<div class="no-print">
    <div>
        <h2>{{ Str::limit($buku->judul, 50) }}</h2>
        <p>{{ $kodeList->count() }} label &nbsp;·&nbsp; eks. {{ $kodeList->first()?->nomor_urut ?? '-' }} s/d {{ $kodeList->last()?->nomor_urut ?? '-' }}</p>
    </div>
    <div style="display:flex;gap:8px">
        <a href="{{ route('buku.show', $buku->id) }}" class="btn-back">← Kembali</a>
        <button onclick="window.print()" class="btn-cetak">🖨 Cetak</button>
    </div>
</div>

@php
$warna = [
    '000'=>['bg'=>'#f3f4f6','color'=>'#374151'],
    '100'=>['bg'=>'#ede9fe','color'=>'#7c3aed'],
    '200'=>['bg'=>'#fef9c3','color'=>'#a16207'],
    '300'=>['bg'=>'#dbeafe','color'=>'#1d4ed8'],
    '400'=>['bg'=>'#dcfce7','color'=>'#15803d'],
    '500'=>['bg'=>'#e0f2fe','color'=>'#0369a1'],
    '600'=>['bg'=>'#fce7f3','color'=>'#be185d'],
    '700'=>['bg'=>'#ffedd5','color'=>'#c2410c'],
    '800'=>['bg'=>'#f0fdf4','color'=>'#166534'],
    '900'=>['bg'=>'#fef2f2','color'=>'#991b1b'],
];
$kode = $buku->kategori?->kode ?? '000';
$w    = $warna[$kode] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];

// Data rincian buku untuk disimpan di dalam QR
$dataQR = [
    'judul'       => $buku->judul,
    'pengarang'   => $buku->pengarang,
    'penerbit'    => $buku->penerbit ?? '-',
    'tahun'       => $buku->tahun_terbit ?? '-',
    'kategori'    => ($buku->kategori?->kode ?? '') . ' - ' . ($buku->kategori?->nama ?? ''),
    'sekolah'     => 'SMAN 5 Tebo',
];
@endphp

<div class="label-grid">
    @foreach($kodeList as $k)
    @php
        $qrData = json_encode(array_merge($dataQR, ['kode_buku' => $k->kode_buku, 'eks' => $k->nomor_urut . '/' . $buku->stok]));
    @endphp
    <div class="label">
        {{-- Header logo + nama sekolah --}}
        <div class="label-header">
            <img src="{{ asset('images/logo_sma.png') }}" alt="Logo">
            <span>SMAN 5 Tebo</span>
        </div>

        {{-- DDC kecil --}}
        <div class="label-ddc" style="background:{{ $w['bg'] }};color:{{ $w['color'] }};">
            {{ $kode }}
        </div>

        {{-- QR Code --}}
        <div id="qr-{{ $k->id }}"></div>

        {{-- Kode buku --}}
        <div class="label-kode">{{ $k->kode_buku }}</div>

        <hr class="label-line">

        {{-- Judul singkat --}}
        <div class="label-judul">{{ Str::limit($buku->judul, 30) }}</div>
    </div>
    @endforeach
</div>

<script>
const logoUrl = '{{ asset('images/logo_sma.png') }}';

function buatQR(elId, teks) {
    const el = document.getElementById(elId);
    if (!el) return;

    new QRCode(el, {
        text: teks,
        width: 80,
        height: 80,
        colorDark: '#1a237e',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H,
    });

    // Tambah logo di tengah QR
    setTimeout(() => {
        const canvas = el.querySelector('canvas');
        if (!canvas) return;
        const ctx   = canvas.getContext('2d');
        const logo  = new Image();
        logo.crossOrigin = 'anonymous';
        logo.src = logoUrl;
        logo.onload = () => {
            const size = canvas.width * 0.22;
            const x    = (canvas.width  - size) / 2;
            const y    = (canvas.height - size) / 2;
            // Lingkaran putih di belakang logo
            ctx.fillStyle = '#fff';
            ctx.beginPath();
            ctx.arc(canvas.width/2, canvas.height/2, size/2 + 3, 0, Math.PI*2);
            ctx.fill();
            ctx.drawImage(logo, x, y, size, size);
        };
    }, 200);
}

// Data JSON rincian buku langsung di dalam QR
@foreach($kodeList as $k)
buatQR('qr-{{ $k->id }}', '{{ url('/scan/buku/' . $k->kode_buku) }}');
@endforeach
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Label Kategori DDC — SMAN 5 Tebo</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 20px; }

        .toolbar {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 20px; background: #fff;
            padding: 12px 16px; border-radius: 10px; border: 1px solid #e5e7eb;
        }
        .toolbar-title { font-size:14px; font-weight:600; color:#111827; flex:1; }
        .toolbar-sub { font-size:12px; color:#9ca3af; }
        .btn-print {
            padding: 8px 18px; background: #1a237e; color: #fff;
            border: none; border-radius: 8px; font-size: 13px;
            font-weight: 500; cursor: pointer;
            display: inline-flex; align-items:center; gap:6px;
        }
        .btn-back {
            padding: 8px 14px; background: #f3f4f6; color: #374151;
            border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px;
            cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap:5px;
        }

        .label-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .label {
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .label-header {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .label-header span {
            font-size: 6px; color: #9ca3af; font-weight: 600;
            text-transform: uppercase; letter-spacing: .05em;
        }

        .label-divider { width:100%; border:none; border-top:1px dashed #e5e7eb; }

        .label-kode {
            font-size: 16px; font-weight: 700;
            padding: 2px 10px; border-radius: 100px;
            letter-spacing: .04em; line-height: 1;
        }

        .label-nama {
            font-size: 7.5px; font-weight: 600;
            color: #374151; text-align: center;
            line-height: 1.4; max-width: 90px;
        }

        .label-qr canvas {
            width: 80px !important;
            height: 80px !important;
            display: block;
        }

        .label-deskripsi {
            font-size: 6.5px; color: #9ca3af;
            text-align: center; line-height: 1.4;
            max-width: 100px;
        }

        @media print {
            body { background: #fff; padding: 8px; }
            .toolbar { display: none; }
            .label-grid {
                grid-template-columns: repeat(5, 1fr);
                gap: 6px;
            }
            .label { border: 1px solid #000; }
            @page { size: A4 portrait; margin: 8mm; }
        }
    </style>
</head>
<body>

<div class="toolbar">
    <div>
        <div class="toolbar-title">Label Kategori DDC</div>
        <div class="toolbar-sub">SMAN 5 Tebo — {{ $kategoris->count() }} kategori</div>
    </div>
    <a href="{{ route('kategori.index') }}" class="btn-back">← Kembali</a>
    <button class="btn-print" onclick="window.print()">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
            <path d="M4 1h8v3H4V1zM1 5h14v7H1V5zm3 2v4h8V7H4zM2 6v1h1V6H2zm11 0v1h1V6h-1z"/>
        </svg>
        Cetak Semua
    </button>
</div>

@php
$warna = [
    '000' => ['bg'=>'#f3f4f6','color'=>'#374151'],
    '100' => ['bg'=>'#ede9fe','color'=>'#7c3aed'],
    '200' => ['bg'=>'#fef9c3','color'=>'#a16207'],
    '300' => ['bg'=>'#dbeafe','color'=>'#1d4ed8'],
    '400' => ['bg'=>'#dcfce7','color'=>'#15803d'],
    '500' => ['bg'=>'#e0f2fe','color'=>'#0369a1'],
    '600' => ['bg'=>'#fce7f3','color'=>'#be185d'],
    '700' => ['bg'=>'#ffedd5','color'=>'#c2410c'],
    '800' => ['bg'=>'#f0fdf4','color'=>'#166534'],
    '900' => ['bg'=>'#fef2f2','color'=>'#991b1b'],
    'FIK' => ['bg'=>'#fdf4ff','color'=>'#86198f'],
    'REF' => ['bg'=>'#f8fafc','color'=>'#475569'],
];
@endphp

<div class="label-grid">
    @foreach($kategoris as $k)
    @php $w = $warna[$k->kode] ?? ['bg'=>'#f3f4f6','color'=>'#374151']; @endphp
    <div class="label">

        {{-- Header logo + sekolah --}}
        <div class="label-header">
            <span>SMAN 5 Tebo</span>
        </div>

        <hr class="label-divider">

        {{-- Kode DDC --}}
        <div class="label-kode" style="background:{{ $w['bg'] }};color:{{ $w['color'] }};">
            {{ $k->kode }}
        </div>

        {{-- Nama kategori --}}
        <div class="label-nama">{{ $k->nama }}</div>

        {{-- QR Code --}}
        <div class="label-qr" id="qr-{{ $k->id }}"></div>

        <hr class="label-divider">

        {{-- Deskripsi --}}
        @if($k->deskripsi)
        <div class="label-deskripsi">{{ Str::limit($k->deskripsi, 40) }}</div>
        @endif

    </div>
    @endforeach
</div>

<script>
const logoUrl = '{{ asset('public/images/logo sma.png') }}';

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

    setTimeout(() => {
        const canvas = el.querySelector('canvas');
        if (!canvas) return;
        const ctx  = canvas.getContext('2d');
        const logo = new Image();
        logo.crossOrigin = 'anonymous';
        logo.src = logoUrl;
        logo.onload = () => {
            const size = canvas.width * 0.22;
            const x    = (canvas.width  - size) / 2;
            const y    = (canvas.height - size) / 2;
            ctx.fillStyle = '#fff';
            ctx.beginPath();
            ctx.arc(canvas.width/2, canvas.height/2, size/2 + 3, 0, Math.PI*2);
            ctx.fill();
            ctx.drawImage(logo, x, y, size, size);
        };
    }, 200);
}

@foreach($kategoris as $k)
buatQR('qr-{{ $k->id }}', '{{ url('/scan/kategori/' . $k->kode) }}');
@endforeach
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori {{ $kategori->kode }} — Perpustakaan SMAN 5 Tebo</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f3f4f6; min-height:100vh;
            display:flex; align-items:center; justify-content:center; padding:16px;
        }
        .card {
            background: #fff; border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            width: 100%; max-width: 400px; overflow: hidden;
        }
        .card-header {
            background: #1a237e; padding: 20px 20px 16px; text-align: center;
        }
        .card-header img {
            width: 56px; height: 56px; border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.3); margin-bottom: 8px;
        }
        .card-header h3 { font-size:13px; color:rgba(255,255,255,0.8); font-weight:400; }
        .card-header h2 { font-size:15px; color:#fff; font-weight:600; margin-top:2px; }
        .ddc-box {
            display: inline-block; margin-top: 10px;
            padding: 4px 16px; border-radius: 100px;
            font-size: 22px; font-weight: 700; letter-spacing: .04em;
        }
        .card-body { padding: 20px; }
        .info-row {
            display: flex; justify-content: space-between;
            align-items: flex-start; padding: 10px 0;
            border-bottom: 1px solid #f3f4f6; gap: 12px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-size:12px; color:#9ca3af; white-space:nowrap; flex-shrink:0; }
        .info-value { font-size:13px; color:#111827; font-weight:500; text-align:right; }
        .buku-list { margin-top: 16px; }
        .buku-list-title {
            font-size: 11px; font-weight: 600; color: #6b7280;
            text-transform: uppercase; letter-spacing: .05em;
            margin-bottom: 8px;
        }
        .buku-item {
            padding: 8px 10px; background: #f9fafb;
            border: 1px solid #f3f4f6; border-radius: 8px;
            margin-bottom: 6px; font-size: 12px; color: #374151;
        }
        .buku-item-pengarang { font-size: 11px; color: #9ca3af; margin-top: 2px; }
        .footer {
            background: #f9fafb; padding: 12px 20px;
            text-align: center; font-size: 11px; color: #9ca3af;
            border-top: 1px solid #f3f4f6;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <img src="{{ asset('images/logosma.png') }}" alt="Logo SMAN 5 Tebo">
        <h3>Perpustakaan SMAN 5 Tebo</h3>
        <h2>Klasifikasi DDC</h2>
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
        $w = $warna[$kategori->kode] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
        @endphp
        <div class="ddc-box" style="background:{{ $w['bg'] }};color:{{ $w['color'] }};">
            {{ $kategori->kode }}
        </div>
    </div>

    <div class="card-body">
        <div class="info-row">
            <span class="info-label">Nama Kategori</span>
            <span class="info-value">{{ $kategori->nama }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Deskripsi</span>
            <span class="info-value" style="font-size:12px;">{{ $kategori->deskripsi ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jumlah Buku</span>
            <span class="info-value">{{ $kategori->buku->count() }} judul</span>
        </div>

        {{-- Daftar buku dalam kategori ini --}}
        @if($kategori->buku->count() > 0)
        <div class="buku-list">
            <div class="buku-list-title">Koleksi Buku</div>
            @foreach($kategori->buku->take(10) as $b)
            <div class="buku-item">
                <div>{{ $b->judul }}</div>
                <div class="buku-item-pengarang">{{ $b->pengarang }}</div>
            </div>
            @endforeach
            @if($kategori->buku->count() > 10)
            <div style="font-size:11px;color:#9ca3af;text-align:center;padding:6px 0;">
                dan {{ $kategori->buku->count() - 10 }} buku lainnya...
            </div>
            @endif
        </div>
        @endif
    </div>

    <div class="footer">
        Sistem Informasi Manajemen Perpustakaan · SMAN 5 Tebo
    </div>
</div>
</body>
</html>
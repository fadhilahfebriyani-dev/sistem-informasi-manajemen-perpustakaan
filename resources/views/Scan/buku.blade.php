<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kb->buku->judul }} — Perpustakaan SMAN 5 Tebo</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
        }
        .card-header {
            background: #1a237e;
            padding: 20px 20px 16px;
            text-align: center;
        }
        .card-header img {
            width: 56px; height: 56px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.3);
            margin-bottom: 8px;
        }
        .card-header h3 {
            font-size: 13px;
            color: rgba(255,255,255,0.8);
            font-weight: 400;
        }
        .card-header h2 {
            font-size: 15px;
            color: #fff;
            font-weight: 600;
            margin-top: 2px;
        }
        .card-body { padding: 20px; }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
            gap: 12px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label {
            font-size: 12px;
            color: #9ca3af;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .info-value {
            font-size: 13px;
            color: #111827;
            font-weight: 500;
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 500;
        }
        .badge-tersedia { background: #f0fdf4; color: #16a34a; }
        .badge-dipinjam { background: #fffbeb; color: #d97706; }
        .badge-rusak { background: #fef2f2; color: #dc2626; }
        .badge-hilang { background: #f3f4f6; color: #6b7280; }
        .ddc-box {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 4px 10px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
        }
        .footer {
            background: #f9fafb;
            padding: 12px 20px;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            border-top: 1px solid #f3f4f6;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <img src="{{ asset('images/logo_sma.png') }}" alt="Logo SMAN 5 Tebo">
        <h3>Perpustakaan SMAN 5 Tebo</h3>
        <h2>Informasi Buku</h2>
    </div>

    <div class="card-body">
        <div class="info-row">
            <span class="info-label">Kode Buku</span>
            <span class="info-value" style="font-family:monospace;color:#2563eb;">
                {{ $kb->kode_buku }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Judul</span>
            <span class="info-value">{{ $kb->buku->judul }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Pengarang</span>
            <span class="info-value">{{ $kb->buku->pengarang }}</span>
        </div>
        @if($kb->buku->penerbit)
        <div class="info-row">
            <span class="info-label">Penerbit</span>
            <span class="info-value">{{ $kb->buku->penerbit }}</span>
        </div>
        @endif
        @if($kb->buku->tahun_terbit)
        <div class="info-row">
            <span class="info-label">Tahun Terbit</span>
            <span class="info-value">{{ $kb->buku->tahun_terbit }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Kategori</span>
            <span class="info-value">
                <span class="ddc-box">
                    {{ $kb->buku->kategori->kode ?? '-' }}
                    &nbsp;·&nbsp;
                    {{ $kb->buku->kategori->nama ?? '-' }}
                </span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">No. Eksemplar</span>
            <span class="info-value">{{ $kb->nomor_urut }} dari {{ $kb->buku->stok }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value">
                <span class="badge badge-{{ $kb->status }}">
                    {{ ucfirst($kb->status) }}
                </span>
            </span>
        </div>
    </div>

    <div class="footer">
        Sistem Informasi Manajemen Perpustakaan · SMAN 5 Tebo
    </div>
</div>
</body>
</html>
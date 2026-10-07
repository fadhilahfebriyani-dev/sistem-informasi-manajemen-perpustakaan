@extends('layouts.app')

@push('styles')
<style>
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; margin-bottom:1rem; }
    .section-title { padding:0.75rem 1rem; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; border-bottom:1px solid #e5e7eb; background:#f9fafb; }
    table { width:100%; border-collapse:collapse; }
    th { padding:0.65rem 1rem; font-size:12px; font-weight:600; color:#6b7280; text-align:left; border-bottom:1px solid #e5e7eb; background:#f9fafb; }
    td { padding:0.75rem 1rem; font-size:13px; color:#374151; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
    tr:last-child td { border-bottom:none; }
    tr:hover td { background:#f9fafb; }
    .btn { padding:0.55rem 1rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-sm { padding:0.35rem 0.75rem; font-size:12px; border-radius:6px; }
    .btn-edit { background:#f0fdf4; color:#16a34a; }
    .btn-edit:hover { background:#dcfce7; }
    .btn-del { background:#fef2f2; color:#dc2626; }
    .btn-del:hover { background:#fee2e2; }
    .btn-gray { background:#f3f4f6; color:#374151; }
    .btn-gray:hover { background:#e5e7eb; }
    .badge { display:inline-block; padding:3px 10px; border-radius:100px; font-size:11px; font-weight:500; }
    .badge-green { background:#f0fdf4; color:#16a34a; }
    .badge-red { background:#fef2f2; color:#dc2626; }
    .badge-amber { background:#fffbeb; color:#d97706; }
    .badge-blue { background:#eff6ff; color:#2563eb; }
    .badge-gray { background:#f3f4f6; color:#6b7280; }
    .hero { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.25rem 1.5rem; margin-bottom:1rem; display:flex; align-items:center; justify-content:space-between; }
    .empty-row { text-align:center; padding:1.5rem; color:#9ca3af; font-size:13px; }
    .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:1rem; }
    .stat-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:14px 16px; }
    .stat-val { font-size:22px; font-weight:600; }
    .stat-lbl { font-size:12px; color:#6b7280; margin-top:2px; }
    .kode-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:8px; padding:1rem; }
    .kode-chip { display:flex; align-items:center; justify-content:space-between; gap:8px; padding:.5rem .65rem; border-radius:8px; font-family:monospace; font-size:12px; border:1px solid #e5e7eb; }
    .kode-chip.tersedia { background:#f0fdf4; border-color:#bbf7d0; color:#15803d; }
    .kode-chip.dipinjam { background:#fffbeb; border-color:#fde68a; color:#92400e; }
    .kode-chip.rusak, .kode-chip.hilang { background:#fef2f2; border-color:#fecaca; color:#b91c1c; }
    .kode-chip .dot { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
    .kode-chip.tersedia .dot { background:#16a34a; }
    .kode-chip.dipinjam .dot { background:#d97706; }
    .kode-chip.rusak .dot, .kode-chip.hilang .dot { background:#dc2626; }
</style>
@endpush

@section('content')

{{-- Tombol kembali --}}
<div style="margin-bottom:1rem;">
    <a href="{{ route('buku.index') }}" class="btn btn-gray">← Kembali</a>
</div>

{{-- HERO: Judul buku --}}
<div class="hero">
    <div>
        <div style="font-size:18px;font-weight:600;color:#111827;">{{ $buku->judul }}</div>
        <div style="font-size:12px;color:#6b7280;margin-top:4px;">
            {{ $buku->pengarang }}
            @if($buku->penerbit) &nbsp;·&nbsp; {{ $buku->penerbit }} @endif
            @if($buku->tahun_terbit) &nbsp;·&nbsp; {{ $buku->tahun_terbit }} @endif
        </div>
        <div style="margin-top:8px;display:flex;gap:6px;flex-wrap:wrap;">
            <span class="badge badge-blue">{{ $buku->kategori->nama ?? '-' }}</span>
            <span class="badge {{ $buku->stok > 0 ? 'badge-green' : 'badge-red' }}">
                Stok: {{ $buku->stok }}
            </span>
        </div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-edit">Edit</a>
        <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" style="display:inline">
            @csrf @method('DELETE')
            <button onclick="return confirm('Yakin hapus buku ini?')" class="btn btn-sm btn-del">Hapus</button>
        </form>
    </div>
</div>

{{-- REKAP BUKU --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-val" style="color:#2563eb;">{{ $buku->stok }}</div>
        <div class="stat-lbl">Stok Tersedia</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#d97706;">{{ $totalDipinjam }}</div>
        <div class="stat-lbl">Sedang Dipinjam</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#16a34a;">{{ $totalRiwayat }}</div>
        <div class="stat-lbl">Total Pernah Dipinjam</div>
    </div>
</div>

{{-- TABEL: Semua Kode Buku --}}
<div class="card" id="semua-kode">
    <div class="section-title">
        Semua Kode Buku
        <span style="font-weight:400;color:#9ca3af;margin-left:6px;">({{ $buku->kodeBuku->count() }} kode)</span>
    </div>
    @if($buku->kodeBuku->isEmpty())
    <div class="empty-row">Belum ada kode buku untuk judul ini</div>
    @else
    <div class="kode-grid">
        @foreach($buku->kodeBuku as $k)
        <div class="kode-chip {{ $k->status }}" title="Status: {{ ucfirst($k->status) }}">
            <span class="dot"></span>
            <span>{{ $k->kode_buku }}</span>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- TABEL: Sedang Dipinjam --}}
<div class="card">
    <div class="section-title">
        Sedang Dipinjam
        <span style="font-weight:400;color:#9ca3af;margin-left:6px;">({{ $totalDipinjam }} peminjam)</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Anggota</th>
                <th>Kelas</th>
                <th>Kode Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pinjamAktif as $detail)
            <tr>
                <td style="font-weight:500;">{{ $detail->peminjaman->anggota->nama ?? '-' }}</td>
                <td>{{ $detail->peminjaman->anggota->kelas ?? '-' }}</td>
                <td style="font-family:monospace;font-size:12px;color:#2563eb;">{{ $detail->kode_buku ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($detail->peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                <td>{{ $detail->peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($detail->peminjaman->tanggal_kembali)->format('d M Y') : '-' }}</td>
                <td><span class="badge badge-amber">Dipinjam</span></td>
            </tr>
            @empty
            <tr><td colspan="6" class="empty-row">Tidak ada yang sedang meminjam buku ini</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- TABEL: Riwayat Peminjaman --}}
<div class="card">
    <div class="section-title">
        Riwayat Peminjaman
        <span style="font-weight:400;color:#9ca3af;margin-left:6px;">({{ $totalRiwayat }} transaksi)</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Anggota</th>
                <th>Kelas</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Durasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $detail)
            <tr>
                <td style="font-weight:500;">{{ $detail->peminjaman->anggota->nama ?? '-' }}</td>
                <td>{{ $detail->peminjaman->anggota->kelas ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($detail->peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                <td>{{ $detail->peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($detail->peminjaman->tanggal_kembali)->format('d M Y') : '-' }}</td>
                <td style="color:#6b7280;">
                    @if($detail->peminjaman->tanggal_kembali)
                        {{ \Carbon\Carbon::parse($detail->peminjaman->tanggal_pinjam)->diffInDays($detail->peminjaman->tanggal_kembali) }} hari
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($detail->peminjaman->status === 'dikembalikan')
                        <span class="badge badge-green">Dikembalikan</span>
                    @elseif($detail->peminjaman->status === 'terlambat')
                        <span class="badge badge-red">Terlambat</span>
                    @else
                        <span class="badge badge-gray">{{ ucfirst($detail->peminjaman->status) }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="empty-row">Belum ada riwayat peminjaman</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
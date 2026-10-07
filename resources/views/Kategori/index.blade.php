@extends('layouts.app')

@push('styles')
<style>
    .btn { padding:0.55rem 1rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#2563eb; color:#fff; }
    .btn-primary:hover { background:#1d4ed8; }
    .btn-success { background:#16a34a; color:#fff; }
    .btn-success:hover { background:#15803d; }
    .btn-gray { background:#f3f4f6; color:#374151; border:1px solid #e5e7eb; }
    .btn-gray:hover { background:#e5e7eb; }
    .btn-sm { padding:0.35rem 0.75rem; font-size:12px; border-radius:6px; }
    .btn-edit { background:#eff6ff; color:#2563eb; }
    .btn-edit:hover { background:#dbeafe; }
    .btn-del { background:#fef2f2; color:#dc2626; }
    .btn-del:hover { background:#fee2e2; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; }
    table { width:100%; border-collapse:collapse; }
    thead tr { background:#f9fafb; }
    th { padding:0.65rem 1rem; font-size:12px; font-weight:600; color:#6b7280; text-align:left; border-bottom:1px solid #e5e7eb; }
    td { padding:0.75rem 1rem; font-size:13px; color:#374151; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
    tr:last-child td { border-bottom:none; }
    tr:hover td { background:#f9fafb; }
    .actions { display:flex; gap:6px; align-items:center; }
    .pagination-wrap { padding:0.875rem 1.25rem; border-top:1px solid #e5e7eb; }
    .kode-badge {
        display:inline-flex; align-items:center; justify-content:center;
        width:44px; height:44px; border-radius:8px;
        font-size:13px; font-weight:700; letter-spacing:0.02em;
        flex-shrink:0;
    }
    .deskripsi-text { font-size:12px; color:#9ca3af; margin-top:2px; }
    .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:1.25rem; }
    .stat-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:14px 16px; }
    .stat-val { font-size:22px; font-weight:600; }
    .stat-lbl { font-size:12px; color:#6b7280; margin-top:2px; }
</style>
@endpush

@section('content')
<x-alert />

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
    <div>
        <h1 style="font-size:20px;font-weight:600;color:#111827;">Kategori Buku</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:2px;">Klasifikasi DDC (Dewey Decimal Classification)</p>
    </div>
    <div style="display:flex;gap:8px;align-items:center;">
        <form method="GET" style="display:flex;gap:8px;">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari kategori..."
                style="padding:0.55rem 0.875rem;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-family:inherit;outline:none;width:220px;">
            <button class="btn btn-primary">Cari</button>
        </form>
        <a href="{{ route('kategori.cetak') }}" target="_blank" class="btn btn-gray">
            <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
                <path d="M4 1h8v3H4V1zM1 5h14v7H1V5zm3 2v4h8V7H4zM2 6v1h1V6H2zm11 0v1h1V6h-1z"/>
            </svg>
            Cetak Label
        </a>
        <a href="{{ route('kategori.create') }}" class="btn btn-success">+ Kategori</a>
    </div>
</div>

{{-- REKAP --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-val" style="color:#2563eb;">{{ $totalKategori }}</div>
        <div class="stat-lbl">Total Kategori</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#16a34a;">{{ $totalBuku }}</div>
        <div class="stat-lbl">Total Buku</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#d97706;">{{ $totalStok }}</div>
        <div class="stat-lbl">Total Stok</div>
    </div>
</div>

{{-- TABEL --}}
<div class="card">
    <table>
        <thead>
            <tr>
                <th style="width:80px;">Kode</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th style="width:70px;text-align:center;">Buku</th>
                <th style="width:120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
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

            @forelse($kategori as $k)
            @php $w = $warna[$k->kode] ?? ['bg'=>'#f3f4f6','color'=>'#374151']; @endphp
            <tr>
                <td>
                    <div class="kode-badge" style="background:{{ $w['bg'] }};color:{{ $w['color'] }};">
                        {{ $k->kode }}
                    </div>
                </td>
                <td>
                    <span style="font-weight:500;color:#111827;">{{ $k->nama }}</span>
                </td>
                <td>
                    <span class="deskripsi-text">{{ $k->deskripsi ?? '-' }}</span>
                </td>
                <td style="text-align:center;">
                    <span style="font-size:13px;font-weight:500;color:#374151;">
                        {{ $k->bukus_count ?? 0 }}
                    </span>
                </td>
                <td>
                    <div class="actions">
                        <a href="{{ route('kategori.edit', $k->id) }}" class="btn btn-sm btn-edit">Edit</a>
                        <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Yakin hapus kategori ini?')" class="btn btn-sm btn-del">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:2rem;color:#9ca3af;">Tidak ada data kategori</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $kategori->links() }}</div>
</div>

@endsection
@extends('layouts.app')

@push('styles')
<style>
    .btn { padding:0.55rem 1rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#2563eb; color:#fff; }
    .btn-primary:hover { background:#1d4ed8; }
    .btn-success { background:#16a34a; color:#fff; }
    .btn-success:hover { background:#15803d; }
    .btn-sm { padding:0.35rem 0.75rem; font-size:12px; border-radius:6px; }
    .btn-detail { background:#eff6ff; color:#2563eb; }
    .btn-detail:hover { background:#dbeafe; }
    .btn-edit { background:#f0fdf4; color:#16a34a; }
    .btn-edit:hover { background:#dcfce7; }
    .btn-del { background:#fef2f2; color:#dc2626; }
    .btn-del:hover { background:#fee2e2; }
    .btn-label { background:#fef9c3; color:#a16207; }
    .btn-label:hover { background:#fef08a; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; }
    table { width:100%; border-collapse:collapse; }
    thead tr { background:#f9fafb; }
    th { padding:0.65rem 1rem; font-size:12px; font-weight:600; color:#6b7280; text-align:left; border-bottom:1px solid #e5e7eb; }
    td { padding:0.75rem 1rem; font-size:13px; color:#374151; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
    tr:last-child td { border-bottom:none; }
    tr:hover td { background:#f9fafb; }
    .actions { display:flex; gap:6px; align-items:center; flex-wrap:wrap; }
    .badge { display:inline-block; padding:2px 8px; border-radius:100px; font-size:11px; font-weight:500; }
    .badge-blue  { background:#eff6ff; color:#2563eb; }
    .badge-green { background:#f0fdf4; color:#16a34a; }
    .badge-red   { background:#fef2f2; color:#dc2626; }
    .badge-warn  { background:#fef9c3; color:#a16207; }
    .pagination-wrap { padding:0.875rem 1.25rem; border-top:1px solid #e5e7eb; }
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:1.25rem; }
    .stat-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:14px 16px; }
    .stat-val { font-size:22px; font-weight:600; }
    .stat-lbl { font-size:12px; color:#6b7280; margin-top:2px; }

    /* Notif label belum dicetak di header */
    .notif-label {
        display:inline-flex; align-items:center; gap:6px;
        background:#fef9c3; color:#a16207;
        border:1px solid #fde047;
        border-radius:8px; padding:6px 12px;
        font-size:12px; font-weight:600;
        text-decoration:none;
        transition:background .15s;
    }
    .notif-label:hover { background:#fef08a; }
    .notif-label .dot {
        width:7px; height:7px; border-radius:50%;
        background:#a16207; flex-shrink:0;
        animation:blink 1.2s ease-in-out infinite;
    }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }
</style>
@endpush

@section('content')
<x-alert />

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:10px;">
    <div>
        <h1 style="font-size:20px;font-weight:600;color:#111827;">Data Buku</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:2px;">Kelola koleksi buku perpustakaan</p>
    </div>

    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
        {{-- Search --}}
        <form method="GET" style="display:flex;gap:8px;">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                   placeholder="Cari judul, pengarang..."
                   style="padding:0.55rem 0.875rem;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-family:inherit;outline:none;width:220px;">
            <button class="btn btn-primary">Cari</button>
        </form>

        {{-- Notif cetak label jika ada yang belum dicetak --}}
        @if($belumDicetak > 0)
        <a href="{{ route('buku.cetak', ['filter' => 'belum']) }}" class="notif-label">
            <span class="dot"></span>
            {{ $belumDicetak }} label belum dicetak
        </a>
        @endif

        {{-- Tombol cetak label --}}
        <a href="{{ route('buku.cetak') }}"
           style="padding:0.55rem 1rem;border-radius:8px;font-size:13px;font-weight:500;border:1px solid #e5e7eb;background:#f9fafb;color:#374151;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
            <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
                <path d="M4 1h8v3H4V1zM1 5h14v7H1V5zm3 2v4h8V7H4zM2 6v1h1V6H2zm11 0v1h1V6h-1z"/>
            </svg>
            Cetak Label
        </a>

        <a href="{{ route('buku.create') }}" class="btn btn-success">+ Buku</a>
    </div>
</div>

{{-- REKAP --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-val" style="color:#2563eb;">{{ $totalBuku }}</div>
        <div class="stat-lbl">Total Judul Buku</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#111827;">{{ $totalStok }}</div>
        <div class="stat-lbl">Total Stok</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#16a34a;">{{ $totalTersedia }}</div>
        <div class="stat-lbl">Judul Tersedia</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#dc2626;">{{ $totalHabis }}</div>
        <div class="stat-lbl">Stok Habis</div>
    </div>
</div>

{{-- TABEL --}}
<div class="card">
    <table>
        <thead>
            <tr>
                <th style="width:50px;">No</th>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Kategori</th>
                <th style="width:70px;text-align:center;">Stok</th>
                <th style="width:70px;text-align:center;">Label</th>
                <th style="width:160px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($buku as $b)
            @php
                $belumCetak = $b->kodeBuku->where('label_dicetak', false)->count();
                $totalKode  = $b->kodeBuku->count();
            @endphp
            <tr>
                <td style="color:#9ca3af;">{{ $buku->firstItem() + $loop->index }}</td>
                <td style="font-weight:500;color:#111827;">{{ $b->judul }}</td>
                <td style="color:#6b7280;">{{ $b->pengarang }}</td>
                <td><span class="badge badge-blue">{{ $b->kategori->nama ?? '-' }}</span></td>
                <td style="text-align:center;">
                    <span class="badge {{ $b->stok > 0 ? 'badge-green' : 'badge-red' }}">{{ $b->stok }}</span>
                </td>
                <td style="text-align:center;">
                    @if($totalKode === 0)
                        <span class="badge" style="background:#f3f4f6;color:#9ca3af;">—</span>
                    @elseif($belumCetak > 0)
                        {{-- Ada label yang belum dicetak --}}
                        <a href="{{ route('buku.cetak', ['filter'=>'manual', 'buku_id[]'=>$b->id]) }}"
                           class="badge badge-warn"
                           title="{{ $belumCetak }} dari {{ $totalKode }} label belum dicetak"
                           style="cursor:pointer;text-decoration:none;">
                            ⚠ {{ $belumCetak }}/{{ $totalKode }}
                        </a>
                    @else
                        <span class="badge badge-green" title="Semua label sudah dicetak">✓ Semua</span>
                    @endif
                </td>
                <td>
                    <div class="actions">
                        <a href="{{ route('buku.show', $b->id) }}" class="btn btn-sm btn-detail">Detail</a>
                        <a href="{{ route('buku.edit', $b->id) }}" class="btn btn-sm btn-edit">Edit</a>
                        <form action="{{ route('buku.destroy', $b->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Yakin hapus buku ini?')" class="btn btn-sm btn-del">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:2rem;color:#9ca3af;">Tidak ada data buku</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $buku->links() }}</div>
</div>
@endsection
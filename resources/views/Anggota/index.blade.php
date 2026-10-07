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
    .btn-del { background:#fef2f2; color:#dc2626; }
    .btn-del:hover { background:#fee2e2; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; }
    table { width:100%; border-collapse:collapse; }
    thead tr { background:#f9fafb; }
    th { padding:0.65rem 1rem; font-size:12px; font-weight:600; color:#6b7280; text-align:left; border-bottom:1px solid #e5e7eb; }
    td { padding:0.75rem 1rem; font-size:13px; color:#374151; border-bottom:1px solid #f3f4f6; }
    tr:last-child td { border-bottom:none; }
    tr:hover td { background:#f9fafb; }
    .actions { display:flex; gap:6px; align-items:center; }
    .kelas-badge { display:inline-block; padding:2px 8px; border-radius:100px; font-size:11px; font-weight:500; background:#f0fdf4; color:#16a34a; }
    .pagination-wrap { padding:0.875rem 1.25rem; border-top:1px solid #e5e7eb; }
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:1.25rem; }
    .stat-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:14px 16px; }
    .stat-val { font-size:22px; font-weight:600; }
    .stat-lbl { font-size:12px; color:#6b7280; margin-top:2px; }
</style>
@endpush

@section('content')
<x-alert />

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
    <div>
        <h1 style="font-size:20px;font-weight:600;color:#111827;">Data Anggota</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:2px;">Kelola data anggota perpustakaan</p>
    </div>
    <div style="display:flex;gap:8px;align-items:center;">
        <form method="GET" style="display:flex;gap:8px;">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, NISN, kelas..."
                style="padding:0.55rem 0.875rem;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-family:inherit;outline:none;width:220px;">
            <button class="btn btn-primary">Cari</button>
        </form>
        <a href="{{ route('anggota.create') }}" class="btn btn-success">+ Anggota</a>
    </div>
</div>

{{-- REKAP --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-val" style="color:#2563eb;">{{ $totalAnggota }}</div>
        <div class="stat-lbl">Total Anggota</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#16a34a;">{{ $totalLunas }}</div>
        <div class="stat-lbl">Lunas</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#dc2626;">{{ $totalMeminjam }}</div>
        <div class="stat-lbl">Masih Meminjam</div>
    </div>
    <div class="stat-card">
        <div class="stat-val" style="color:#d97706;">{{ $totalTerlambat }}</div>
        <div class="stat-lbl">Terlambat</div>
    </div>
</div>

{{-- TABEL --}}
<div class="card">
    <table>
        <thead>
            <tr>
                <th style="width:50px;">No</th>
                <th>Nama</th>
                <th>NISN</th>
                <th>Kelas</th>
                <th style="width:120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anggotum as $a)
            <tr>
                <td style="color:#9ca3af;">{{ $anggotum->firstItem() + $loop->index }}</td>
                <td style="font-weight:500;color:#111827;">{{ $a->nama }}</td>
                <td style="font-family:monospace;font-size:12px;color:#6b7280;">{{ $a->nisn ?? '-' }}</td>
                <td><span class="kelas-badge">{{ $a->kelas }}</span></td>
                <td>
                    <div class="actions">
                        <a href="{{ route('anggota.show', $a->id) }}" class="btn btn-sm btn-detail">Detail</a>
                        <form action="{{ route('anggota.destroy', $a->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Yakin hapus anggota ini?')" class="btn btn-sm btn-del">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:2rem;color:#9ca3af;">Tidak ada data anggota</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $anggotum->links() }}</div>
</div>
@endsection
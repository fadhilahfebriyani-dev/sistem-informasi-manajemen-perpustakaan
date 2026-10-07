@extends('layouts.app')

@push('styles')
<style>
.stat-row{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:1.25rem}
.stat-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:1rem 1.25rem;display:flex;align-items:center;gap:14px}
.stat-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.stat-val{font-size:24px;font-weight:600;color:#111827;line-height:1}
.stat-lbl{font-size:12px;color:#6b7280;margin-top:2px}
.card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden}
table{width:100%;border-collapse:collapse}
thead tr{background:#f9fafb}
th{padding:.65rem 1rem;font-size:12px;font-weight:600;color:#6b7280;text-align:left;border-bottom:1px solid #e5e7eb}
td{padding:.75rem 1rem;font-size:13px;color:#374151;border-bottom:1px solid #f3f4f6;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#f9fafb}
.actions{display:flex;gap:6px;align-items:center}
.pagination-wrap{padding:.875rem 1.25rem;border-top:1px solid #e5e7eb}
.btn{padding:.55rem 1rem;border-radius:8px;font-size:13px;font-weight:500;font-family:inherit;border:none;cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
.btn-primary{background:#2563eb;color:#fff}.btn-primary:hover{background:#1d4ed8}
.btn-success{background:#16a34a;color:#fff}.btn-success:hover{background:#15803d}
.btn-sm{padding:.35rem .75rem;font-size:12px;border-radius:6px}
.btn-view{background:#f0fdf4;color:#16a34a}.btn-view:hover{background:#dcfce7}
.btn-edit{background:#eff6ff;color:#2563eb}.btn-edit:hover{background:#dbeafe}
.btn-print{background:#fafafa;color:#374151;border:1px solid #e5e7eb}.btn-print:hover{background:#f3f4f6}
.btn-del{background:#fef2f2;color:#dc2626}.btn-del:hover{background:#fee2e2}
.badge{display:inline-block;padding:3px 10px;border-radius:100px;font-size:11px;font-weight:500}
.badge-aktif{background:#f0fdf4;color:#16a34a}
.badge-nonaktif{background:#f3f4f6;color:#6b7280}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:.75rem 1rem;font-size:13px;color:#16a34a;margin-bottom:1rem}
.filter-wrap{display:flex;gap:8px;align-items:center}
.filter-btn{padding:.4rem .875rem;border-radius:6px;font-size:12px;font-weight:500;border:1px solid #e5e7eb;background:#fff;color:#374151;cursor:pointer;text-decoration:none}
.filter-btn.active{background:#2563eb;color:#fff;border-color:#2563eb}
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
    <div>
        <h1 style="font-size:20px;font-weight:600;color:#111827">Bebas Pustaka</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:2px">Kelola surat keterangan bebas pustaka anggota perpustakaan</p>
    </div>
    <a href="{{ route('bebaspustaka.create') }}" class="btn btn-success">+ Tambah Bebas Pustaka</a>
</div>

{{-- STAT CARDS --}}
<div class="stat-row">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eff6ff">
            <svg width="20" height="20" viewBox="0 0 16 16" fill="#2563eb"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6H2z"/></svg>
        </div>
        <div><div class="stat-val">{{ $totalAnggota }}</div><div class="stat-lbl">Total Anggota</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f0fdf4">
            <svg width="20" height="20" viewBox="0 0 16 16" fill="#16a34a"><path d="M2 8l4 4 8-8" stroke="#16a34a" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
        </div>
        <div><div class="stat-val">{{ $totalAktif }}</div><div class="stat-lbl">Sudah Bebas Pustaka</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef2f2">
            <svg width="20" height="20" viewBox="0 0 16 16" fill="#dc2626"><path d="M8 3v5M8 10v2" stroke="#dc2626" stroke-width="2" stroke-linecap="round"/><circle cx="8" cy="8" r="6" stroke="#dc2626" stroke-width="1.5" fill="none"/></svg>
        </div>
        <div><div class="stat-val">{{ $belumBebas }}</div><div class="stat-lbl">Belum Bebas Pustaka</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f3f4f6">
            <svg width="20" height="20" viewBox="0 0 16 16" fill="#6b7280"><rect x="2" y="2" width="12" height="12" rx="2" stroke="#6b7280" stroke-width="1.5" fill="none"/><path d="M5 8h6M5 5h6M5 11h4" stroke="#6b7280" stroke-width="1.2"/></svg>
        </div>
        <div><div class="stat-val">{{ $totalNonaktif }}</div><div class="stat-lbl">Status Nonaktif</div></div>
    </div>
</div>

@if(session('success'))
<div class="alert-success">{{ session('success') }}</div>
@endif

{{-- FILTER & SEARCH --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
    <div class="filter-wrap">
        <a href="{{ route('bebaspustaka.index') }}" class="filter-btn {{ !$filterStat ? 'active' : '' }}">Semua</a>
        <a href="{{ route('bebaspustaka.index', ['status'=>'aktif']) }}" class="filter-btn {{ $filterStat=='aktif' ? 'active' : '' }}">Aktif</a>
        <a href="{{ route('bebaspustaka.index', ['status'=>'nonaktif']) }}" class="filter-btn {{ $filterStat=='nonaktif' ? 'active' : '' }}">Nonaktif</a>
    </div>
    <form method="GET" style="display:flex;gap:8px">
        @if($filterStat)<input type="hidden" name="status" value="{{ $filterStat }}">@endif
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / kelas..."
            style="padding:.55rem .875rem;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-family:inherit;outline:none;width:220px">
        <button class="btn btn-primary" style="padding:.55rem 1rem">Cari</button>
    </form>
</div>

{{-- TABLE --}}
<div class="card">
    <table>
        <thead>
            <tr>
                <th style="width:40px">No</th>
                <th>Anggota</th>
                <th>No. Surat</th>
                <th>Keperluan</th>
                <th>Tahun Ajaran</th>
                <th>Tanggal</th>
                <th>Status Pinjam</th>
                <th>Status</th>
                <th style="width:160px">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bebas as $b)
            @php
                $pinjamAktif = \App\Models\Peminjaman::where('anggota_id', $b->anggota_id)
                    ->where('status','dipinjam')->count();
            @endphp
            <tr>
                <td style="color:#9ca3af">{{ $bebas->firstItem() + $loop->index }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="font-weight:500;color:#111827">{{ $b->anggota->nama ?? '-' }}</div>
                        <div style="font-size:11px;color:#9ca3af">{{ $b->anggota->kelas ?? '-' }}</div>
                        
                        </div>
                    </div>
                </td>
                <td style="font-size:11px;color:#6b7280;font-family:monospace">{{ $b->nomor_surat ?? '-' }}</td>
                <td>{{ $b->keperluan }}</td>
                <td>{{ $b->tahun_ajaran }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</td>
                <td>
                    @if($pinjamAktif > 0)
                        <span style="background:#fef2f2;color:#dc2626;padding:3px 8px;border-radius:100px;font-size:11px;font-weight:500">
                            {{ $pinjamAktif }} buku dipinjam
                        </span>
                    @else
                        <span style="background:#f0fdf4;color:#16a34a;padding:3px 8px;border-radius:100px;font-size:11px;font-weight:500">
                            Lunas
                        </span>
                    @endif
                </td>
                <td><span class="badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                <td>
                    <div class="actions">
                        <a href="{{ route('bebaspustaka.show', $b->id) }}" class="btn btn-sm btn-view" title="Detail">Detail</a>
                        <a href="{{ route('bebaspustaka.cetak', $b->id) }}" class="btn btn-sm btn-print" target="_blank" title="Cetak">
                            <svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor"><path d="M4 1h8v3H4V1zM1 5h14v7H1V5zm3 2v4h8V7H4zM2 6v1h1V6H2zm11 0v1h1V6h-1z"/></svg>
                        </a>
                        <a href="{{ route('bebaspustaka.edit', $b->id) }}" class="btn btn-sm btn-edit">Edit</a>
                        <form action="{{ route('bebaspustaka.destroy', $b->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Yakin hapus data ini?')" class="btn btn-sm btn-del">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;padding:2rem;color:#9ca3af">Tidak ada data bebas pustaka</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-wrap">{{ $bebas->links() }}</div>
</div>
@endsection
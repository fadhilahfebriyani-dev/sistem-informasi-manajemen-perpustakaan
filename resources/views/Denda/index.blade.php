@extends('layouts.app')

@push('styles')
<style>
    .btn { padding:0.55rem 1rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-sm { padding:0.35rem 0.75rem; font-size:12px; border-radius:6px; }
    .btn-primary { background:#2563eb; color:#fff; }
    .btn-primary:hover { background:#1d4ed8; color:#fff; }
    .btn-outline { background:#fff; color:#374151; border:1px solid #e5e7eb; }
    .btn-outline:hover { background:#f9fafb; }
    .btn-reset { background:#f3f4f6; color:#6b7280; }
    .btn-reset:hover { background:#e5e7eb; }

    .card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; }
    table { width:100%; border-collapse:collapse; }
    thead tr { background:#f9fafb; }
    th { padding:0.65rem 1rem; font-size:11px; font-weight:600; color:#6b7280; text-align:left; border-bottom:1px solid #e5e7eb; text-transform:uppercase; letter-spacing:0.04em; }
    td { padding:0.75rem 1rem; font-size:13px; color:#374151; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
    tr:last-child td { border-bottom:none; }
    tr:hover td { background:#f9fafb; }

    .status-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:100px; font-size:11px; font-weight:600; }
    .badge-lunas    { background:#f0fdf4; color:#16a34a; }
    .badge-belum    { background:#fef2f2; color:#dc2626; }
    .badge-terlambat{ background:#fef2f2; color:#dc2626; }

    .avatar-sm {
        width:32px; height:32px; border-radius:50%;
        background:#2563eb; color:#fff;
        display:inline-flex; align-items:center; justify-content:center;
        font-size:12px; font-weight:600; flex-shrink:0;
    }

    .filter-bar { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
    .filter-bar label { font-size:12px; font-weight:600; color:#6b7280; }
    .filter-bar select { padding:0.35rem 0.65rem; border:1px solid #e5e7eb; border-radius:6px; font-size:13px; font-family:inherit; color:#374151; background:#fff; cursor:pointer; }
    .filter-bar select:focus { outline:none; border-color:#2563eb; }

    .empty-state { text-align:center; padding:3rem 1rem; color:#9ca3af; }
    .empty-state svg { margin-bottom:0.75rem; opacity:0.4; }
    .empty-state p { font-size:13px; margin-top:4px; }

    .dot { width:7px; height:7px; border-radius:50%; display:inline-block; flex-shrink:0; }
    .dot-lunas  { background:#16a34a; }
    .dot-belum  { background:#dc2626; }

    .buku-pill { display:inline-block; background:#f3f4f6; color:#374151; border:1px solid #e5e7eb; border-radius:4px; padding:2px 7px; font-size:11px; font-weight:500; margin:2px 2px 0 0; }

    .pagination-wrap { padding:0.875rem 1.25rem; border-top:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; }
    .pagination-info { font-size:12px; color:#9ca3af; }
</style>
@endpush

@section('content')

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon:'success', title:'{{ session('success') }}', timer:2000, showConfirmButton:false });
});
</script>
@endif
@if(session('warning'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon:'warning', title:'Perhatian', text:'{{ session('warning') }}', confirmButtonColor:'#dc2626' });
});
</script>
@endif

{{-- Page Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
    <div>
        <h1 style="font-size:20px;font-weight:600;color:#111827;">Daftar Denda Keterlambatan</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:2px;">Kelola dan pantau denda peminjaman buku</p>
    </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:1rem;">
    <div style="padding:0.75rem 1.25rem;">
        <form method="GET" action="{{ route('denda.index') }}" class="filter-bar">
            <label>Status:</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="belum_bayar" {{ request('status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="lunas"       {{ request('status') === 'lunas'       ? 'selected' : '' }}>Lunas</option>
            </select>
            @if(request('status'))
                <a href="{{ route('denda.index') }}" class="btn btn-sm btn-reset">✕ Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card">
    <table>
        <thead>
            <tr>
                <th style="width:45px;text-align:center;">No</th>
                <th>Anggota</th>
                <th>Buku Dipinjam</th>
                <th style="text-align:center;">Batas Kembali</th>
                <th style="text-align:center;">Dikembalikan</th>
                <th style="text-align:center;">Terlambat</th>
                <th style="text-align:right;">Total Denda</th>
                <th style="text-align:center;">Status</th>
                <th style="text-align:center;width:90px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($dendas as $denda)
            <tr>
                <td style="text-align:center;color:#9ca3af;font-size:12px;">
                    {{ $loop->iteration + ($dendas->currentPage() - 1) * $dendas->perPage() }}
                </td>

                {{-- Anggota --}}
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div class="avatar-sm">
                            {{ strtoupper(substr($denda->peminjaman->anggota->nama ?? '?', 0, 1)) }}
                        </div>
                        <span style="font-weight:500;color:#111827;">
                            {{ $denda->peminjaman->anggota->nama ?? '-' }}
                        </span>
                    </div>
                </td>

                {{-- Buku --}}
                <td>
                    @foreach($denda->peminjaman->details as $detail)
                        <span class="buku-pill">{{ $detail->buku->judul ?? '-' }}</span>
                    @endforeach
                </td>

                {{-- Batas Kembali --}}
                <td style="text-align:center;color:#6b7280;font-size:12px;">
                    {{ $denda->peminjaman->tanggal_kembali?->format('d/m/Y') ?? '-' }}
                </td>

                {{-- Dikembalikan --}}
                <td style="text-align:center;color:#6b7280;font-size:12px;">
                    {{ $denda->peminjaman->tanggal_kembali_aktual?->format('d/m/Y') ?? '-' }}
                </td>

                {{-- Terlambat --}}
                <td style="text-align:center;">
                    <span class="status-badge badge-terlambat">
                        {{ $denda->hari_terlambat }} hari
                    </span>
                </td>

                {{-- Total Denda --}}
                <td style="text-align:right;font-weight:600;color:#dc2626;">
                    {{ $denda->total_denda_format }}
                </td>

                {{-- Status --}}
                <td style="text-align:center;">
                    @if($denda->isLunas())
                        <span class="status-badge badge-lunas">
                            <span class="dot dot-lunas"></span> Lunas
                        </span>
                    @else
                        <span class="status-badge badge-belum">
                            <span class="dot dot-belum"></span> Belum Bayar
                        </span>
                    @endif
                </td>

                {{-- Aksi --}}
                <td style="text-align:center;">
                    <a href="{{ route('denda.show', $denda) }}" class="btn btn-sm btn-primary">Detail</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/>
                        </svg>
                        <strong style="color:#6b7280;font-size:14px;">Tidak ada data denda</strong>
                        <p>Belum ada denda keterlambatan yang tercatat</p>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    @if($dendas->hasPages())
    <div class="pagination-wrap">
        <span class="pagination-info">
            Menampilkan {{ $dendas->firstItem() }}–{{ $dendas->lastItem() }} dari {{ $dendas->total() }} data
        </span>
        {{ $dendas->links() }}
    </div>
    @endif
</div>

@endsection
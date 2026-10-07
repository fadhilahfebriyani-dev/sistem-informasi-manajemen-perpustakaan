@extends('layouts.app')

@push('styles')
<style>
    .btn { padding:0.55rem 1rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-sm { padding:0.35rem 0.75rem; font-size:12px; border-radius:6px; }
    .btn-success { background:#16a34a; color:#fff; }
    .btn-success:hover { background:#15803d; }
    .btn-outline { background:#fff; color:#374151; border:1px solid #e5e7eb; }
    .btn-outline:hover { background:#f9fafb; }
    .btn-ghost { background:#f3f4f6; color:#6b7280; }
    .btn-ghost:hover { background:#e5e7eb; }

    .card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; }
    .card-header { padding:0.85rem 1.25rem; border-bottom:1px solid #e5e7eb; font-size:13px; font-weight:600; color:#374151; display:flex; align-items:center; gap:8px; }
    .card-header svg { opacity:0.5; }
    .card-body { padding:1.25rem; }

    .info-table { width:100%; border-collapse:collapse; }
    .info-table tr td { padding:0.55rem 0; font-size:13px; border-bottom:1px solid #f3f4f6; vertical-align:top; }
    .info-table tr:last-child td { border-bottom:none; }
    .info-table .label { color:#6b7280; font-weight:500; width:42%; white-space:nowrap; }
    .info-table .value { color:#111827; }

    .status-banner { display:flex; align-items:center; gap:12px; padding:0.85rem 1.1rem; border-radius:8px; margin-bottom:1.25rem; font-size:13px; }
    .banner-lunas  { background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; }
    .banner-belum  { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; }
    .banner-icon   { width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:16px; }
    .banner-lunas .banner-icon  { background:#dcfce7; }
    .banner-belum .banner-icon  { background:#fee2e2; }

    .status-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:100px; font-size:12px; font-weight:600; }
    .badge-lunas { background:#f0fdf4; color:#16a34a; }
    .badge-belum { background:#fef2f2; color:#dc2626; }

    .total-card { border:1px solid #fecaca; border-radius:10px; background:#fff5f5; padding:1.5rem; text-align:center; margin-top:1.25rem; }
    .total-card .amount { font-size:28px; font-weight:700; color:#dc2626; margin:4px 0 1.25rem; }

    .avatar-sm { width:32px; height:32px; border-radius:50%; background:#2563eb; color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; flex-shrink:0; }

    .buku-item { display:flex; flex-direction:column; gap:2px; padding:7px 10px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; margin-bottom:6px; }
    .buku-item:last-child { margin-bottom:0; }
    .buku-judul { font-size:13px; font-weight:600; color:#111827; }
    .buku-kode { font-size:11px; color:#2563eb; background:#eff6ff; padding:1px 6px; border-radius:4px; display:inline-block; font-family:monospace; }

    .terlambat-big { font-size:22px; font-weight:700; color:#dc2626; }

    .two-col { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
    @media(max-width:640px) { .two-col { grid-template-columns:1fr; } }
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
@if(session('info'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon:'info', title:'{{ session('info') }}', timer:2000, showConfirmButton:false });
});
</script>
@endif

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
    <div>
        <h1 style="font-size:20px;font-weight:600;color:#111827;">Detail Denda Keterlambatan</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:2px;">Informasi lengkap peminjaman dan tagihan denda</p>
    </div>
    <a href="{{ route('denda.index') }}" class="btn btn-outline">← Kembali</a>
</div>

{{-- Status Banner --}}
@if($denda->isLunas())
    <div class="status-banner banner-lunas">
        <div class="banner-icon">✓</div>
        <div>
            <div style="font-weight:600;">Denda Telah Lunas</div>
            <div style="font-size:12px;opacity:0.8;">Dibayarkan pada {{ $denda->dibayar_at->format('d/m/Y, H:i') }} WIB</div>
        </div>
    </div>
@else
    <div class="status-banner banner-belum">
        <div class="banner-icon">!</div>
        <div>
            <div style="font-weight:600;">Denda Belum Dibayar</div>
            <div style="font-size:12px;opacity:0.8;">Segera lakukan konfirmasi pembayaran</div>
        </div>
    </div>
@endif

<div class="two-col">

    {{-- Kartu Informasi Peminjaman --}}
    <div class="card">
        <div class="card-header">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="#374151"><circle cx="8" cy="5.5" r="2.5"/><path d="M2.5 13.5c0-3 2.5-5 5.5-5s5.5 2 5.5 5H2.5z"/></svg>
            Informasi Peminjaman
        </div>
        <div class="card-body">
            <table class="info-table">
                <tr>
                    <td class="label">Nama Anggota</td>
                    <td class="value">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div class="avatar-sm">
                                {{ strtoupper(substr($denda->peminjaman->anggota->nama ?? '?', 0, 1)) }}
                            </div>
                            <span style="font-weight:500;">{{ $denda->peminjaman->anggota->nama ?? '-' }}</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="label" style="vertical-align:top;padding-top:0.75rem;">Buku Dipinjam</td>
                    <td class="value" style="padding-top:0.5rem;">
                        @foreach($denda->peminjaman->details as $detail)
                            <div class="buku-item">
                                <span class="buku-judul">{{ $detail->buku->judul ?? '-' }}</span>
                                @if($detail->kode_buku)
                                    <span class="buku-kode">{{ $detail->kode_buku }}</span>
                                @endif
                            </div>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td class="label">Tanggal Pinjam</td>
                    <td class="value">{{ $denda->peminjaman->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Batas Kembali</td>
                    <td class="value" style="color:#dc2626;font-weight:500;">
                        {{ $denda->peminjaman->tanggal_kembali?->format('d/m/Y') ?? '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Dikembalikan</td>
                    <td class="value">{{ $denda->peminjaman->tanggal_kembali_aktual?->format('d/m/Y') ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Kartu Rincian Denda --}}
    <div class="card">
        <div class="card-header">
            <svg width="15" height="15" viewBox="0 0 16 16" fill="#374151"><path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 1.5a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11zM7.25 4v4.25l3.5 2.1.65-1.08-3-1.79V4H7.25z"/></svg>
            Rincian Denda
        </div>
        <div class="card-body">
            <table class="info-table">
                <tr>
                    <td class="label">Hari Terlambat</td>
                    <td class="value">
                        <span class="terlambat-big">{{ $denda->hari_terlambat }}</span>
                        <span style="color:#6b7280;font-size:13px;"> hari</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Denda / Hari</td>
                    <td class="value">{{ $denda->denda_per_hari_format }}</td>
                </tr>
                <tr>
                    <td class="label">Total Denda</td>
                    <td class="value" style="font-size:18px;font-weight:700;color:#dc2626;">
                        {{ $denda->total_denda_format }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Status</td>
                    <td class="value">
                        @if($denda->isLunas())
                            <span class="status-badge badge-lunas">● Lunas</span>
                        @else
                            <span class="status-badge badge-belum">● Belum Bayar</span>
                        @endif
                    </td>
                </tr>
                @if($denda->isLunas() && $denda->dibayar_at)
                <tr>
                    <td class="label">Dibayar Pada</td>
                    <td class="value" style="font-size:12px;">{{ $denda->dibayar_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endif
                @if($denda->catatan)
                <tr>
                    <td class="label">Catatan</td>
                    <td class="value" style="color:#6b7280;">{{ $denda->catatan }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

</div>

{{-- Tombol Bayar --}}
@if(! $denda->isLunas())
<div class="total-card">
    <div style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Total yang harus dibayar</div>
    <div class="amount">{{ $denda->total_denda_format }}</div>
    <form action="{{ route('denda.bayar', $denda) }}" method="POST"
          onsubmit="return confirm('Konfirmasi pembayaran {{ $denda->total_denda_format }}?')">
        @csrf
        <button type="submit" class="btn btn-success" style="font-size:14px;padding:0.65rem 2rem;">
            ✓ Konfirmasi Pembayaran Denda
        </button>
    </form>
</div>
@endif
@endsection
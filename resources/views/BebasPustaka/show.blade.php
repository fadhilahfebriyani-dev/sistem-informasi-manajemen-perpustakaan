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
</style>
@endpush

@section('content')

<div style="margin-bottom:1rem;">
    <a href="{{ route('bebaspustaka.index') }}" class="btn btn-gray">← Kembali</a>
</div>

{{-- HERO: Info Anggota --}}
<div class="hero">
    <div>
        <div style="font-size:18px;font-weight:600;color:#111827;">{{ $anggota->nama }}</div>
        <div style="font-size:12px;color:#6b7280;margin-top:4px;">
            {{ $anggota->kelas }} &nbsp;·&nbsp; {{ $anggota->nis ?? '-' }}
        </div>
        <div style="margin-top:8px;">
            <span class="badge {{ $bebaspustaka->status === 'aktif' ? 'badge-green' : 'badge-gray' }}">
                {{ ucfirst($bebaspustaka->status) }}
            </span>
        </div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('bebaspustaka.edit', $bebaspustaka->id) }}" class="btn btn-sm btn-edit">Edit</a>
        <form action="{{ route('bebaspustaka.destroy', $bebaspustaka->id) }}" method="POST" style="display:inline">
            @csrf @method('DELETE')
            <button onclick="return confirm('Yakin hapus data ini?')" class="btn btn-sm btn-del">Hapus</button>
        </form>
    </div>
</div>

{{-- INFO SURAT --}}
<div class="card">
    <div class="section-title">Detail Surat Bebas Pustaka</div>
    <table>
        <tr>
            <td style="color:#6b7280;width:180px;">Nomor Surat</td>
            <td>{{ $bebaspustaka->nomor_surat ?? '-' }}</td>
        </tr>
        <tr>
            <td style="color:#6b7280;">Tanggal</td>
            <td>{{ \Carbon\Carbon::parse($bebaspustaka->tanggal)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td style="color:#6b7280;">Tahun Ajaran</td>
            <td>{{ $bebaspustaka->tahun_ajaran }}</td>
        </tr>
        <tr>
            <td style="color:#6b7280;">Keperluan</td>
            <td>{{ $bebaspustaka->keperluan }}</td>
        </tr>
    </table>
</div>

{{-- Pinjaman Aktif --}}
<div class="card">
    <div class="section-title">Buku Sedang Dipinjam ({{ $pinjamAktif->count() }})</div>
    <table>
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pinjamAktif as $p)
            <tr>
                <td>{{ $p->details->first()?->buku?->judul ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                <td><span class="badge badge-amber">Dipinjam</span></td>
            </tr>
            @empty
            <tr><td colspan="3" class="empty-row">Tidak ada pinjaman aktif ✓</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Riwayat --}}
<div class="card">
    <div class="section-title">Riwayat Peminjaman ({{ $sudahKembali->count() }})</div>
    <table>
        <thead>
            <tr>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sudahKembali as $p)
            <tr>
                <td>{{ $p->details->first()?->buku?->judul ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                <td>{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '-' }}</td>
                <td><span class="badge badge-green">Dikembalikan</span></td>
            </tr>
            @empty
            <tr><td colspan="4" class="empty-row">Belum ada riwayat</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
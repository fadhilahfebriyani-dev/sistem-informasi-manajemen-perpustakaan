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
    .btn-edit { background:#eff6ff; color:#2563eb; }
    .btn-edit:hover { background:#dbeafe; }
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

{{-- Tombol kembali --}}
<div style="margin-bottom:1rem;">
    <a href="{{ route('anggota.index') }}" class="btn btn-gray">← Kembali</a>
</div>

{{-- HERO: Nama + NISN --}}
<div class="hero">
    <div>
        <div style="font-size:18px;font-weight:600;color:#111827;">{{ $anggotum->nama }}</div>
        <div style="font-size:12px;color:#6b7280;font-family:monospace;margin-top:3px;">
            NISN: {{ $anggotum->nisn ?? '-' }} &nbsp;·&nbsp; {{ $anggotum->kelas }}
        </div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('anggota.edit', $anggotum->id) }}" class="btn btn-sm btn-edit">Edit</a>
        <form action="{{ route('anggota.destroy', $anggotum->id) }}" method="POST" style="display:inline">
            @csrf @method('DELETE')
            <button onclick="return confirm('Yakin hapus anggota ini?')" class="btn btn-sm btn-del">Hapus</button>
        </form>
    </div>
</div>

{{-- TABEL: Peminjaman Aktif --}}
<div class="card">
    <div class="section-title">
        Peminjaman Aktif
        <span style="font-weight:400;color:#9ca3af;margin-left:6px;">({{ $pinjamAktif->count() }} buku)</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Buku</th>
                <th>Kode Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pinjamAktif as $p)
                @foreach($p->details as $detail)
                <tr>
                    <td style="font-weight:500;">{{ $detail->buku->judul ?? '-' }}</td>
                    <td style="font-family:monospace;font-size:12px;color:#2563eb;">{{ $detail->kode_buku ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                    <td>{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '-' }}</td>
                    <td><span class="badge badge-amber">Dipinjam</span></td>
                </tr>
                @endforeach
            @empty
            <tr><td colspan="5" class="empty-row">Tidak ada peminjaman aktif</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- TABEL: Riwayat Peminjaman --}}
<div class="card">
    <div class="section-title">
        Riwayat Peminjaman
        <span style="font-weight:400;color:#9ca3af;margin-left:6px;">({{ $riwayat->count() }} transaksi)</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Durasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $p)
                @foreach($p->details as $detail)
                <tr>
                    <td style="font-weight:500;">{{ $detail->buku->judul ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                    <td>{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '-' }}</td>
                    <td style="color:#6b7280;">
                        @if($p->tanggal_kembali)
                            {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->diffInDays($p->tanggal_kembali) }} hari
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'dikembalikan')
                            <span class="badge badge-green">Dikembalikan</span>
                        @elseif($p->status === 'terlambat')
                            <span class="badge badge-red">Terlambat</span>
                        @else
                            <span class="badge badge-gray">{{ ucfirst($p->status) }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            @empty
            <tr><td colspan="5" class="empty-row">Belum ada riwayat peminjaman</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- STATUS BEBAS PUSTAKA --}}
<div class="card">
    <div class="section-title">Status Bebas Pustaka</div>
    @if($bebasPustaka)
    <table>
        <tr>
            <td style="color:#6b7280;width:160px;">No. Surat</td>
            <td style="font-family:monospace;">{{ $bebasPustaka->nomor_surat ?? '-' }}</td>
        </tr>
        <tr>
            <td style="color:#6b7280;">Tanggal</td>
            <td>{{ \Carbon\Carbon::parse($bebasPustaka->tanggal)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td style="color:#6b7280;">Keperluan</td>
            <td>{{ $bebasPustaka->keperluan }}</td>
        </tr>
        <tr>
            <td style="color:#6b7280;">Tahun Ajaran</td>
            <td>{{ $bebasPustaka->tahun_ajaran }}</td>
        </tr>
        <tr>
            <td style="color:#6b7280;">Status</td>
            <td>
                <span class="badge {{ $bebasPustaka->status === 'aktif' ? 'badge-green' : 'badge-gray' }}">
                    {{ ucfirst($bebasPustaka->status) }}
                </span>
            </td>
        </tr>
    </table>
    @else
    <div class="empty-row">Belum ada data bebas pustaka</div>
    @endif
</div>

@endsection
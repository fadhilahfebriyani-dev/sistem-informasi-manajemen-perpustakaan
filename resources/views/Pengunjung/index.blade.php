@extends('layouts.app')

@push('styles')
<style>
    .btn { padding:0.55rem 1rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-success { background:#16a34a; color:#fff; }
    .btn-success:hover { background:#15803d; }
    .btn-sm { padding:0.35rem 0.75rem; font-size:12px; border-radius:6px; }
    .btn-edit { background:#eff6ff; color:#2563eb; }
    .btn-edit:hover { background:#dbeafe; }
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
    .pagination-wrap { padding:0.875rem 1.25rem; border-top:1px solid #e5e7eb; }
</style>
@endpush

@section('content')

<x-alert />

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
    <div>
        <h1 style="font-size:20px;font-weight:600;color:#111827;">Data Pengunjung</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:2px;">Kelola data kunjungan perpustakaan</p>
    </div>
    <a href="/pengunjung/create" class="btn btn-success">+ Pengunjung</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th style="width:50px;">No</th>
                <th>Nama</th>
                <th>Tanggal Kunjungan</th>
                <th>Keperluan</th>
                <th style="width:120px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengunjung as $item)
            <tr>
                <td style="color:#9ca3af;">{{ $pengunjung->firstItem() + $loop->index }}</td>
                <td style="font-weight:500;color:#111827;">{{ $item->nama }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->format('d M Y') }}</td>
                <td><span style="background:#fafafa;border:1px solid #e5e7eb;padding:2px 8px;border-radius:6px;font-size:12px;">{{ $item->keperluan }}</span></td>
                <td>
                    <div class="actions">
                        <a href="{{ route('pengunjung.edit', $item->id) }}" class="btn btn-sm btn-edit">Edit</a>
                        <form action="{{ route('pengunjung.destroy', $item->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Yakin hapus data ini?')" class="btn btn-sm btn-del">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:2rem;color:#9ca3af;">Tidak ada data pengunjung</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

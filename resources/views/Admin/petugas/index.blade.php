@extends('layouts.admin')

@push('styles')
<style>
    .page-toolbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; gap:12px; }
    .btn { padding:0.55rem 1rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-amber  { background:#f59e0b; color:#fff; }
    .btn-amber:hover { background:#d97706; }
    .btn-sm { padding:0.35rem 0.75rem; font-size:12px; border-radius:6px; }
    .btn-edit { background:#fffbeb; color:#d97706; }
    .btn-edit:hover { background:#fef3c7; }
    .btn-del  { background:#fef2f2; color:#dc2626; }
    .btn-del:hover  { background:#fee2e2; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; }
    table { width:100%; border-collapse:collapse; }
    thead tr { background:#f9fafb; }
    th { padding:0.65rem 1rem; font-size:12px; font-weight:600; color:#6b7280; text-align:left; border-bottom:1px solid #e5e7eb; }
    td { padding:0.75rem 1rem; font-size:13px; color:#374151; border-bottom:1px solid #f3f4f6; }
    tr:last-child td { border-bottom:none; }
    tr:hover td { background:#fffdf5; }
    .badge { display:inline-block; padding:2px 8px; border-radius:100px; font-size:11px; font-weight:500; }
    .actions { display:flex; gap:6px; align-items:center; }
    .avatar-sm {
        width: 30px; height: 30px; border-radius: 50%;
        background: #f59e0b; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 600; flex-shrink: 0;
    }
    .petugas-info { display: flex; align-items: center; gap: 10px; }
    .petugas-name { font-weight: 500; color: #111827; }
    .petugas-email { font-size: 12px; color: #6b7280; }
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

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ icon:'error', title:'{{ session('error') }}', confirmButtonColor:'#f59e0b' });
});
</script>
@endif

<div class="page-toolbar">
    <div>
        <h1 style="font-size:20px;font-weight:600;color:#111827;">Kelola Petugas</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:2px;">Manajemen akun petugas perpustakaan</p>
    </div>
    <a href="{{ route('admin.petugas.create') }}" class="btn btn-amber">
        <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1v14M1 8h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        Tambah Petugas
    </a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th style="width:50px;">No</th>
                <th>Petugas</th>
                <th>Email</th>
                <th style="width:100px;text-align:center;">Status</th>
                <th style="width:100px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($petugas as $p)
            <tr>
                <td style="color:#9ca3af;">{{ $loop->iteration }}</td>
                <td>
                    <div class="petugas-info">
                        <div class="avatar-sm">{{ strtoupper(substr($p->name, 0, 1)) }}</div>
                        <div>
                            <div class="petugas-name">{{ $p->name }}</div>
                        </div>
                    </div>
                </td>
                <td style="color:#6b7280;">{{ $p->email }}</td>
                <td style="text-align:center;">
                    <span class="badge" style="background:#f0fdf4;color:#16a34a;">Aktif</span>
                </td>
                <td>
                    <div class="actions">
                        <a href="{{ route('admin.petugas.edit', $p->id) }}" class="btn btn-sm btn-edit">Edit</a>
                        <form action="{{ route('admin.petugas.destroy', $p->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-del"
                                onclick="Swal.fire({title:'Hapus petugas?',text:'Data tidak dapat dikembalikan.',icon:'warning',showCancelButton:true,confirmButtonColor:'#dc2626',cancelButtonText:'Batal',confirmButtonText:'Ya, hapus'}).then(r=>{ if(r.isConfirmed) this.closest('form').submit() })">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:2.5rem;color:#9ca3af;">
                    Belum ada petugas terdaftar
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
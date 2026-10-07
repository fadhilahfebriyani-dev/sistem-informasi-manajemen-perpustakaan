@extends('layouts.app')

@push('styles')
<style>
    .form-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.5rem; max-width:600px; }
    .form-group { margin-bottom:1.1rem; }
    label { display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; }
    input[type="text"], input[type="date"], select, textarea {
        width:100%; padding:0.6rem 0.875rem; border:1px solid #e5e7eb;
        border-radius:8px; font-size:13px; font-family:inherit; color:#111827;
        outline:none; transition:border-color 0.15s, box-shadow 0.15s;
    }
    input:focus, select:focus, textarea:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
    .btn { padding:0.6rem 1.25rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#2563eb; color:#fff; }
    .btn-primary:hover { background:#1d4ed8; }
    .btn-secondary { background:#f3f4f6; color:#374151; }
    .btn-secondary:hover { background:#e5e7eb; }
    .error-msg { font-size:12px; color:#dc2626; margin-top:4px; }
</style>
@endpush

@section('content')
<div style="margin-bottom:1.25rem;">
    <h1 style="font-size:20px;font-weight:600;color:#111827;">Tambah Pengunjung</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px;">Catat data kunjungan baru</p>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('pengunjung.store') }}">
        @csrf
        <div class="form-group">
            <label>Nama Pengunjung</label>
            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama pengunjung" required>
            @error('nama')<p class="error-msg">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label>Tanggal Kunjungan</label>
            <input type="date" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}" required>
            @error('tanggal_kunjungan')<p class="error-msg">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label>Keperluan</label>
            <textarea name="keperluan" rows="3" placeholder="Contoh: Membaca buku, Meminjam buku, Belajar">{{ old('keperluan') }}</textarea>
            @error('keperluan')<p class="error-msg">{{ $message }}</p>@enderror
        </div>
        <div style="display:flex;gap:8px;margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pengunjung.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

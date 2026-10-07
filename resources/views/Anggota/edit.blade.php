@extends('layouts.app')

@push('styles')
<style>
    .form-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.5rem; max-width:600px; }
    .form-group { margin-bottom:1.1rem; }
    label { display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; }
    input[type="text"], select {
        width:100%; padding:0.6rem 0.875rem; border:1px solid #e5e7eb;
        border-radius:8px; font-size:13px; font-family:inherit; color:#111827;
        outline:none; transition:border-color 0.15s, box-shadow 0.15s;
    }
    input:focus, select:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
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
    <h1 style="font-size:20px;font-weight:600;color:#111827;">Edit Anggota</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px;">Ubah data anggota: <strong>{{ $anggotum->nama }}</strong></p>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('anggota.update', $anggotum) }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="{{ old('nama', $anggotum->nama) }}" required>
            @error('nama')<p class="error-msg">{{ $message }}</p>@enderror
        </div>
        <div class="form-group">
            <label>NISN</label>
            <input type="text" name="nisn" value="{{ old('nisn', $anggotum->nisn) }}" placeholder="Contoh: 1234567890" maxlength="20">
            @error('nisn')<p class="error-msg">{{ $message }}</p>@enderror
        <div class="form-group">
            <label>Kelas</label>
            <input type="text" name="kelas" value="{{ old('kelas', $anggotum->kelas) }}" required>
            @error('kelas')<p class="error-msg">{{ $message }}</p>@enderror
        </div>
        <div style="display:flex;gap:8px;margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('anggota.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

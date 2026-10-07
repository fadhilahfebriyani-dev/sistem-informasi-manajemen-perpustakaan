@extends('layouts.app')

@push('styles')
<style>
    .form-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.5rem; max-width:560px; }
    .form-group { margin-bottom:1.1rem; }
    label { display:block; font-size:12px; font-weight:500; color:#374151; margin-bottom:6px; }
    input[type="text"], textarea {
        width:100%; padding:0.65rem 0.875rem;
        border:1px solid #e5e7eb; border-radius:8px;
        font-size:14px; font-family:inherit; color:#111827;
        outline:none; transition:border-color 0.15s, box-shadow 0.15s;
        background:#fff;
    }
    input[type="text"]:focus, textarea:focus {
        border-color:#2563eb;
        box-shadow:0 0 0 3px rgba(37,99,235,0.1);
    }
    textarea { resize:vertical; min-height:80px; }
    .hint { font-size:11px; color:#9ca3af; margin-top:4px; }
    .btn { padding:0.55rem 1.1rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#2563eb; color:#fff; }
    .btn-primary:hover { background:#1d4ed8; }
    .btn-secondary { background:#f3f4f6; color:#374151; }
    .btn-secondary:hover { background:#e5e7eb; }
</style>
@endpush

@section('content')
<x-alert />

<div style="margin-bottom:1.25rem;">
    <h1 style="font-size:20px;font-weight:600;color:#111827;">Edit Kategori</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px;">Perbarui data kategori DDC</p>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('kategori.update', $kategori->id) }}">
        @csrf @method('PUT')

        <div class="form-group">
            <label for="kode">Kode DDC</label>
            <input type="text" id="kode" name="kode" value="{{ old('kode', $kategori->kode) }}"
                maxlength="10" required>
            <div class="hint">Kode unik kategori DDC</div>
        </div>

        <div class="form-group">
            <label for="nama">Nama Kategori</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $kategori->nama) }}" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi <span style="color:#9ca3af;font-weight:400;">(opsional)</span></label>
            <textarea id="deskripsi" name="deskripsi">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
        </div>

        <div style="display:flex;gap:8px;margin-top:0.5rem;">
            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@endsection
@extends('layouts.admin')

@push('styles')
<style>
    .form-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.5rem; max-width:520px; }
    .form-group { margin-bottom:1.1rem; }
    label { display:block; font-size:12px; font-weight:500; color:#374151; margin-bottom:6px; }
    .form-control {
        width:100%; padding:0.65rem 0.875rem;
        border:1px solid #e5e7eb; border-radius:8px;
        font-size:13px; font-family:inherit; color:#111827;
        outline:none; transition:border-color 0.15s, box-shadow 0.15s;
    }
    .form-control:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,0.12); }
    .form-control.is-invalid { border-color:#dc2626; }
    .invalid-feedback { font-size:12px; color:#dc2626; margin-top:4px; }
    .btn { padding:0.6rem 1.25rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-amber { background:#f59e0b; color:#fff; }
    .btn-amber:hover { background:#d97706; }
    .btn-ghost { background:#f3f4f6; color:#374151; }
    .btn-ghost:hover { background:#e5e7eb; }
    .form-footer { display:flex; gap:8px; margin-top:1.5rem; }
</style>
@endpush

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:20px;font-weight:600;color:#111827;">Tambah Petugas</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px;">Buat akun baru untuk petugas perpustakaan</p>
</div>

<div class="form-card">
    <form action="{{ route('admin.petugas.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   placeholder="Nama petugas" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   placeholder="email@simperpus.com" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="Minimal 6 karakter" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-control" placeholder="Ulangi password" required>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-amber">Simpan Petugas</button>
            <a href="{{ route('admin.petugas.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
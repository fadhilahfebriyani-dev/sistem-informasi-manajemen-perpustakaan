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
    .form-hint { font-size:11px; color:#9ca3af; margin-top:4px; }
    .btn { padding:0.6rem 1.25rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-amber { background:#f59e0b; color:#fff; }
    .btn-amber:hover { background:#d97706; }
    .btn-ghost { background:#f3f4f6; color:#374151; }
    .btn-ghost:hover { background:#e5e7eb; }
    .form-footer { display:flex; gap:8px; margin-top:1.5rem; }
    .divider { border:none; border-top:1px solid #f3f4f6; margin:1.25rem 0; }
</style>
@endpush

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:20px;font-weight:600;color:#111827;">Edit Petugas</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px;">Perbarui data akun petugas</p>
</div>

<div class="form-card">
    <form action="{{ route('admin.petugas.update', $petugas->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name', $petugas->name) }}"
                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   placeholder="Nama petugas" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $petugas->email) }}"
                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   placeholder="email@simperpus.com" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <hr class="divider">

        <div class="form-group">
            <label for="password">Password Baru</label>
            <input type="password" id="password" name="password"
                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="Kosongkan jika tidak ingin diubah">
            <div class="form-hint">Isi hanya jika ingin mengganti password.</div>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-control" placeholder="Ulangi password baru">
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-amber">Simpan Perubahan</button>
            <a href="{{ route('admin.petugas.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>
@endsection
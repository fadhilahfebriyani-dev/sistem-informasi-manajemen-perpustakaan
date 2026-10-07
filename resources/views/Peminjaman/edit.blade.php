@extends('layouts.app')

@push('styles')
<style>
    .form-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.5rem; max-width:640px; }
    .form-group { margin-bottom:1.1rem; }
    label { display:block; font-size:12px; font-weight:500; color:#374151; margin-bottom:6px; }
    .form-control {
        width:100%; padding:0.65rem 0.875rem;
        border:1px solid #e5e7eb; border-radius:8px;
        font-size:13px; font-family:inherit; color:#111827;
        outline:none; transition:border-color 0.15s;
    }
    .form-control:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
    .form-control.is-invalid { border-color:#dc2626; }
    .invalid-feedback { font-size:12px; color:#dc2626; margin-top:4px; }
    .btn { padding:0.6rem 1.25rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#2563eb; color:#fff; }
    .btn-primary:hover { background:#1d4ed8; }
    .btn-ghost { background:#f3f4f6; color:#374151; }
    .btn-ghost:hover { background:#e5e7eb; }
    .divider { border:none; border-top:1px solid #f3f4f6; margin:1.25rem 0; }
    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }

    /* Detail buku readonly */
    .buku-detail-list { display:flex; flex-direction:column; gap:8px; margin-bottom:1rem; }
    .buku-detail-item {
        display:flex; align-items:center; justify-content:space-between;
        padding:10px 12px;
        background:#f9fafb;
        border:1px solid #e5e7eb;
        border-radius:8px;
    }
    .buku-detail-judul { font-size:13px; font-weight:500; color:#111827; }
    .buku-detail-kode {
        font-size:11px; color:#2563eb; font-weight:600;
        background:#eff6ff; padding:2px 8px; border-radius:4px;
        font-family: monospace;
    }
    .info-box {
        background:#f0f9ff; border:1px solid #bae6fd;
        border-radius:8px; padding:0.75rem 1rem;
        font-size:12px; color:#0369a1;
        margin-bottom:1.25rem;
    }
</style>
@endpush

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:20px;font-weight:600;color:#111827;">Edit Peminjaman</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px;">Perbarui status dan tanggal peminjaman</p>
</div>

<div class="form-card">

    {{-- Info buku yang dipinjam (readonly) --}}
    <div style="margin-bottom:0.75rem;">
        <label style="font-size:13px;font-weight:600;color:#111827;">Buku yang Dipinjam</label>
        <p style="font-size:12px;color:#6b7280;margin-top:2px;">Daftar buku tidak dapat diubah. Untuk mengubah buku, hapus dan buat transaksi baru.</p>
    </div>

    <div class="buku-detail-list">
        @forelse($peminjaman->details as $detail)
        <div class="buku-detail-item">
            <span class="buku-detail-judul">{{ $detail->buku->judul ?? '-' }}</span>
            @if($detail->kode_buku)
                <span class="buku-detail-kode">{{ $detail->kode_buku }}</span>
            @else
                <span style="font-size:11px;color:#9ca3af;">Kode tidak tersedia</span>
            @endif
        </div>
        @empty
        <div style="color:#9ca3af;font-size:13px;padding:8px;">Tidak ada data buku</div>
        @endforelse
    </div>

    <div class="info-box">
        Untuk mengubah status menjadi <strong>Dikembalikan</strong>, stok buku akan otomatis dikembalikan dan kode buku akan tersedia kembali.
    </div>

    <hr class="divider">

    <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
        @csrf @method('PUT')

        {{-- Anggota --}}
        <div class="form-group">
            <label>Anggota *</label>
            <select name="anggota_id" class="form-control {{ $errors->has('anggota_id') ? 'is-invalid' : '' }}" required>
                @foreach($anggota as $a)
                    <option value="{{ $a->id }}" {{ $peminjaman->anggota_id == $a->id ? 'selected' : '' }}>
                        {{ $a->nama }} — {{ $a->kelas }}
                    </option>
                @endforeach
            </select>
            @error('anggota_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Tanggal --}}
        <div class="form-grid-2">
            <div class="form-group">
                <label>Tanggal Pinjam *</label>
                <input type="date" name="tanggal_pinjam"
                       value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam) }}"
                       class="form-control {{ $errors->has('tanggal_pinjam') ? 'is-invalid' : '' }}" required>
                @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali"
                       value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali) }}"
                       class="form-control {{ $errors->has('tanggal_kembali') ? 'is-invalid' : '' }}">
                @error('tanggal_kembali')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Status --}}
        <div class="form-group">
            <label>Status *</label>
            <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                <option value="dipinjam"      {{ $peminjaman->status == 'dipinjam'      ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan"  {{ $peminjaman->status == 'dikembalikan'  ? 'selected' : '' }}>Dikembalikan</option>
                <option value="terlambat"     {{ $peminjaman->status == 'terlambat'     ? 'selected' : '' }}>Terlambat</option>
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;gap:8px;margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>

@endsection
@extends('layouts.app')

@push('styles')
<style>
.layout-2col{display:grid;grid-template-columns:1fr 300px;gap:16px;align-items:start}
.form-card,.info-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:1.5rem}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-group{margin-bottom:1.1rem}
label{display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px}
input[type=text],input[type=number],select{width:100%;padding:.6rem .875rem;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-family:inherit;color:#111827;outline:none;transition:border-color .15s,box-shadow .15s;background:#fff}
input:focus,select:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.btn{padding:.6rem 1.25rem;border-radius:8px;font-size:13px;font-weight:500;font-family:inherit;border:none;cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
.btn-primary{background:#2563eb;color:#fff}.btn-primary:hover{background:#1d4ed8}
.btn-secondary{background:#f3f4f6;color:#374151}.btn-secondary:hover{background:#e5e7eb}
.error-msg{font-size:12px;color:#dc2626;margin-top:4px}
.form-hint{font-size:11px;color:#9ca3af;margin-top:4px}
.info-title{font-size:14px;font-weight:600;color:#111827;margin-bottom:1rem;padding-bottom:.75rem;border-bottom:1px solid #f3f4f6}
.stok-info{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.75rem 1rem;font-size:12px;color:#92400e;margin-top:.5rem}
.stok-info.kurang{background:#fef2f2;border-color:#fecaca;color:#b91c1c}
.kode-preview{margin-top:1rem}
.kode-item{padding:.4rem .75rem;background:#f9fafb;border-radius:6px;font-size:11px;font-family:monospace;color:#2563eb;margin-bottom:4px}
</style>
@endpush

@section('content')
<div style="margin-bottom:1.25rem">
    <h1 style="font-size:20px;font-weight:600;color:#111827">Edit Buku</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px">Ubah data: <strong>{{ $buku->judul }}</strong></p>
</div>

<div class="layout-2col">
    <div class="form-card">
        <form method="POST" action="{{ route('buku.update', $buku->id) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" required>
                @error('judul')<p class="error-msg">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}" required>
                @error('pengarang')<p class="error-msg">{{ $message }}</p>@enderror
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                        <option value="{{ $k->id }}" 
                        data-kode="{{ $k->kode }}"
                        {{ old('kategori_id', $buku->kategori_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('kategori_id')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Stok / Jumlah Eksemplar</label>
                    <input type="number" name="stok" id="inputStok"
                        value="{{ old('stok', $buku->stok) }}" min="0" max="999"
                        required oninput="cekTambahan(this.value)">
                    <p class="form-hint">Stok saat ini: <strong>{{ $buku->stok }}</strong>. Menambah akan membuat kode baru, mengurangi akan menghapus kode dengan nomor urut tertinggi yang masih tersedia (kode yang sedang dipinjam tidak akan dihapus).</p>
                    @error('stok')<p class="error-msg">{{ $message }}</p>@enderror
                    <div id="infoTambah" style="display:none" class="stok-info"></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Penerbit <span style="color:#9ca3af;font-weight:400">(opsional)</span></label>
                    <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}">
                    @error('penerbit')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Tahun Terbit <span style="color:#9ca3af;font-weight:400">(opsional)</span></label>
                    <input type="text" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" maxlength="4">
                    @error('tahun_terbit')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
            </div>
            <div style="display:flex;gap:8px;margin-top:1.5rem">
                <button type="submit" class="btn btn-primary">Update Buku</button>
                <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    {{-- INFO KODE YANG SUDAH ADA --}}
    <div class="info-card">
        <div class="info-title">Kode Buku Saat Ini</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:1rem">
            @php
                $tersedia = $buku->kodeBuku->where('status','tersedia')->count();
                $dipinjam = $buku->kodeBuku->where('status','dipinjam')->count();
            @endphp
            <div style="background:#f0fdf4;border-radius:8px;padding:.75rem;text-align:center">
                <div style="font-size:20px;font-weight:700;color:#16a34a">{{ $tersedia }}</div>
                <div style="font-size:11px;color:#16a34a">Tersedia</div>
            </div>
            <div style="background:#fffbeb;border-radius:8px;padding:.75rem;text-align:center">
                <div style="font-size:20px;font-weight:700;color:#d97706">{{ $dipinjam }}</div>
                <div style="font-size:11px;color:#d97706">Dipinjam</div>
            </div>
        </div>

        <div class="kode-preview">
            <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">Kode yang sudah ada:</div>
            @foreach($buku->kodeBuku->sortBy('nomor_urut')->take(6) as $k)
            <div class="kode-item">{{ $k->kode_buku }}</div>
            @endforeach
            @if($buku->kodeBuku->count() > 6)
            <div style="font-size:11px;color:#9ca3af;text-align:center;padding:.5rem">
                + {{ $buku->kodeBuku->count() - 6 }} kode lainnya
            </div>
            @endif
        </div>

        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f3f4f6">
            <a href="{{ route('buku.show', $buku->id) }}#semua-kode"
                style="font-size:13px;color:#2563eb;text-decoration:none;display:flex;align-items:center;gap:4px">
                <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor"><path d="M2 8l4 4 8-8" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                Lihat semua kode buku
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
const stokLama = {{ $buku->stok }};
function cekTambahan(val) {
    const stokBaru = parseInt(val) || 0;
    const el = document.getElementById('infoTambah');
    if (stokBaru > stokLama) {
        el.className = 'stok-info';
        el.style.display = 'block';
        el.innerHTML = `<strong>${stokBaru - stokLama} kode buku baru</strong> akan dibuat otomatis (nomor ${stokLama+1} s/d ${stokBaru}).`;
    } else if (stokBaru < stokLama) {
        el.className = 'stok-info kurang';
        el.style.display = 'block';
        el.innerHTML = `<strong>${stokLama - stokBaru} kode buku</strong> dengan nomor urut tertinggi akan dihapus (kode yang sedang dipinjam tidak akan dihapus).`;
    } else {
        el.style.display = 'none';
    }
}
</script>
@endpush
@endsection
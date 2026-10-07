@extends('layouts.app')

@push('styles')
<style>
    .form-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.5rem; }
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
    .btn-success { background:#16a34a; color:#fff; }
    .btn-success:hover { background:#15803d; }
    .btn-ghost { background:#f3f4f6; color:#374151; }
    .btn-ghost:hover { background:#e5e7eb; }
    .btn-danger-sm { background:#fef2f2; color:#dc2626; border:none; border-radius:6px; padding:0.3rem 0.6rem; font-size:12px; cursor:pointer; }
    .btn-danger-sm:hover { background:#fee2e2; }

    /* Buku rows */
    .buku-rows { display:flex; flex-direction:column; gap:10px; margin-bottom:10px; }
    .buku-row {
        display:grid;
        grid-template-columns: 1fr 1fr auto;
        gap:10px;
        align-items:end;
        padding:12px;
        background:#f9fafb;
        border:1px solid #e5e7eb;
        border-radius:8px;
    }
    .buku-row-label { font-size:11px; font-weight:600; color:#6b7280; margin-bottom:4px; }
    .kode-info {
        font-size:11px; color:#2563eb; margin-top:4px;
        font-family: monospace; font-weight:500;
    }
    .divider { border:none; border-top:1px solid #f3f4f6; margin:1.25rem 0; }
    .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
</style>
@endpush

@section('content')
<div style="margin-bottom:1.5rem;">
    <h1 style="font-size:20px;font-weight:600;color:#111827;">Tambah Peminjaman</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px;">Catat transaksi peminjaman buku — bisa lebih dari 1 buku</p>
</div>

<div class="form-card">
    <form action="{{ route('peminjaman.store') }}" method="POST" id="formPeminjaman">
        @csrf

        {{-- Anggota --}}
        <div class="form-group">
            <label>Anggota *</label>
            <select name="anggota_id" class="form-control {{ $errors->has('anggota_id') ? 'is-invalid' : '' }}" required>
                <option value="">-- Pilih Anggota --</option>
                @foreach($anggota as $a)
                    <option value="{{ $a->id }}" {{ old('anggota_id') == $a->id ? 'selected' : '' }}>
                        {{ $a->nama }} — {{ $a->kelas }}
                    </option>
                @endforeach
            </select>
            @error('anggota_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <hr class="divider">

        {{-- Daftar Buku --}}
        <div style="margin-bottom:0.75rem;">
            <label style="font-size:13px;font-weight:600;color:#111827;">Daftar Buku yang Dipinjam *</label>
            <p style="font-size:12px;color:#6b7280;margin-top:2px;">Pilih buku dan kode fisik buku yang dipinjam</p>
        </div>

        <div class="buku-rows" id="bukuRows">
            {{-- Row buku pertama --}}
            <div class="buku-row" id="bukuRow0">
                <div>
                    <div class="buku-row-label">Judul Buku</div>
                    <select name="buku_ids[]" class="form-control buku-select" data-index="0" required>
                        <option value="">-- Pilih Buku --</option>
                        @foreach($buku as $b)
                            <option value="{{ $b->id }}">{{ $b->judul }} (Stok: {{ $b->stok }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="buku-row-label">Kode Buku</div>
                    <select name="kode_buku_ids[]" class="form-control kode-select" id="kodeSelect0">
                        <option value="">-- Pilih buku dulu --</option>
                    </select>
                    <div class="kode-info" id="kodeInfo0"></div>
                </div>
                <div>
                    <button type="button" class="btn-danger-sm" onclick="hapusBuku(0)" style="display:none;" id="hapusBtn0">✕</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-ghost" onclick="tambahBuku()" style="margin-bottom:1.25rem;">
            + Tambah Buku Lagi
        </button>

        <hr class="divider">

        {{-- Tanggal & Status --}}
        <div class="form-grid-2">
            <div class="form-group">
                <label>Tanggal Pinjam *</label>
                <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                       class="form-control {{ $errors->has('tanggal_pinjam') ? 'is-invalid' : '' }}" required>
                @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}"
                       class="form-control {{ $errors->has('tanggal_kembali') ? 'is-invalid' : '' }}">
                @error('tanggal_kembali')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label>Status *</label>
            <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ old('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;gap:8px;margin-top:1.5rem;">
            <button type="submit" class="btn btn-success">Simpan Peminjaman</button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-ghost">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Data buku dari server
const semuaBuku = @json($buku);
let rowCount = 1;

// Ambil kode buku via AJAX
async function loadKodeBuku(bukuId, kodeSelectId, kodeInfoId) {
    const kodeSelect = document.getElementById(kodeSelectId);
    const kodeInfo   = document.getElementById(kodeInfoId);

    kodeSelect.innerHTML = '<option value="">Loading...</option>';
    kodeInfo.textContent = '';

    if (!bukuId) {
        kodeSelect.innerHTML = '<option value="">-- Pilih buku dulu --</option>';
        return;
    }

    try {
        const res  = await fetch(`/peminjaman/kode-buku?buku_id=${bukuId}`);
        const data = await res.json();
        const list = data.kode_bukus;

        if (!list || list.length === 0) {
            kodeSelect.innerHTML = '<option value="">Tidak ada kode tersedia</option>';
            kodeInfo.textContent = '⚠ Semua kode sedang dipinjam';
            kodeInfo.style.color = '#dc2626';
            return;
        } else {
            kodeSelect.innerHTML = '<option value="">-- Pilih Kode Buku --</option>';
            list.forEach(k => {
                const opt = document.createElement('option');
                opt.value = k.id;
                opt.textContent = k.kode_buku;

                if (k.id === data.auto_pilih) {
                opt.selected = true;
            }              
                kodeSelect.appendChild(opt);
            if (data.auto_pilih) {
            const terpilih = list.find(k => k.id === data.auto_pilih);
            kodeInfo.textContent = `✓ Kode: ${terpilih?.kode_buku}`;
            kodeInfo.style.color = '#2563eb';
        }
            });
        }
    } catch(e) {
        kodeSelect.innerHTML = '<option value="">Gagal memuat kode</option>';
        kodeInfo.textContent = '⚠ Periksa koneksi server';
        kodeInfo.style.color = '#dc2626';
    }   

}

// Tambah row buku baru
function tambahBuku() {
    const idx  = rowCount;
    const rows = document.getElementById('bukuRows');

    // Buat opsi buku
    let opsi = '<option value="">-- Pilih Buku --</option>';
    semuaBuku.forEach(b => {
        opsi += `<option value="${b.id}">${b.judul} (Stok: ${b.stok})</option>`;
    });

    const div = document.createElement('div');
    div.className = 'buku-row';
    div.id = `bukuRow${idx}`;
    div.innerHTML = `
        <div>
            <div class="buku-row-label">Judul Buku</div>
            <select name="buku_ids[]" class="form-control buku-select" data-index="${idx}" required
                onchange="loadKodeBuku(this.value, 'kodeSelect${idx}', 'kodeInfo${idx}')">
                ${opsi}
            </select>
        </div>
        <div>
            <div class="buku-row-label">Kode Buku</div>
            <select name="kode_buku_ids[]" class="form-control kode-select" id="kodeSelect${idx}">
                <option value="">-- Pilih buku dulu --</option>
            </select>
            <div class="kode-info" id="kodeInfo${idx}"></div>
        </div>
        <div>
            <button type="button" class="btn-danger-sm" onclick="hapusBuku(${idx})">✕</button>
        </div>
    `;
    rows.appendChild(div);
    rowCount++;
    updateHapusBtn();
}

// Hapus row buku
function hapusBuku(idx) {
    const row = document.getElementById(`bukuRow${idx}`);
    if (row) row.remove();
    updateHapusBtn();
}

// Tampilkan/sembunyikan tombol hapus
function updateHapusBtn() {
    const rows = document.querySelectorAll('.buku-row');
    rows.forEach((row, i) => {
        const btn = row.querySelector('.btn-danger-sm');
        if (btn) btn.style.display = rows.length > 1 ? 'block' : 'none';
    });
}

// Event listener untuk buku select pertama
document.querySelector('[data-index="0"]').addEventListener('change', function() {
    loadKodeBuku(this.value, 'kodeSelect0', 'kodeInfo0');
});

// Tampilkan kode yang dipilih
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('kode-select')) {
        const idx      = e.target.id.replace('kodeSelect', '');
        const kodeInfo = document.getElementById(`kodeInfo${idx}`);
        const opt      = e.target.options[e.target.selectedIndex];
        kodeInfo.textContent = opt.value ? `✓ Kode: ${opt.text}` : '';
    }
});
</script>
@endpush

@endsection
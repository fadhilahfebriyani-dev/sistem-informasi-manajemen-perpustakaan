@extends('layouts.app')

@push('styles')
<style>
.layout-2col{display:grid;grid-template-columns:1fr 320px;gap:16px;align-items:start}
.form-card,.preview-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:1.5rem}
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

/* ── Upload Sampul ── */
.sampul-upload-area{
    border:2px dashed #d1d5db;border-radius:10px;
    padding:1.5rem;text-align:center;cursor:pointer;
    transition:all .15s;background:#fafafa;position:relative;
}
.sampul-upload-area:hover,.sampul-upload-area.dragover{
    border-color:#2563eb;background:#eff6ff;
}
.sampul-upload-area input[type=file]{
    position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;
}
.sampul-icon{width:40px;height:40px;margin:0 auto 8px;color:#9ca3af;}
.sampul-upload-text{font-size:13px;font-weight:500;color:#374151;}
.sampul-upload-hint{font-size:11px;color:#9ca3af;margin-top:3px;}

/* Preview gambar terpilih */
.sampul-preview-wrap{display:none;position:relative;margin-top:10px;}
.sampul-preview-wrap img{width:100%;max-height:200px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;}
.sampul-remove-btn{
    position:absolute;top:6px;right:6px;
    background:rgba(0,0,0,0.6);color:#fff;border:none;
    border-radius:50%;width:24px;height:24px;cursor:pointer;
    display:flex;align-items:center;justify-content:center;font-size:14px;line-height:1;
}
.sampul-remove-btn:hover{background:rgba(220,38,38,.8);}

/* Preview kode */
.preview-title{font-size:14px;font-weight:600;color:#111827;margin-bottom:1rem;padding-bottom:.75rem;border-bottom:1px solid #f3f4f6}
.kode-preview-list{display:flex;flex-direction:column;gap:6px;max-height:280px;overflow-y:auto}
.kode-chip{display:flex;align-items:center;justify-content:space-between;padding:.5rem .75rem;background:#f9fafb;border-radius:6px;border:1px solid #f3f4f6}
.kode-num{font-size:11px;color:#9ca3af}
.kode-val{font-size:12px;font-weight:500;font-family:monospace;color:#2563eb}
.kode-status{width:8px;height:8px;border-radius:50%;background:#16a34a}
.more-info{text-align:center;padding:.75rem;font-size:12px;color:#9ca3af;border-top:1px solid #f3f4f6;margin-top:.5rem}
</style>
@endpush

@section('content')
<div style="margin-bottom:1.25rem">
    <h1 style="font-size:20px;font-weight:600;color:#111827">Tambah Buku</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px">Kode buku akan dibuat otomatis sesuai jumlah stok</p>
</div>

<div class="layout-2col">
    <div class="form-card">
        {{-- enctype multipart wajib untuk upload file --}}
        <form method="POST" action="{{ route('buku.store') }}" id="formBuku" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul" id="inputJudul" value="{{ old('judul') }}"
                    placeholder="Masukkan judul buku" required oninput="updatePreview()">
                @error('judul')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label>Pengarang</label>
                <input type="text" name="pengarang" value="{{ old('pengarang') }}"
                    placeholder="Nama pengarang" required>
                @error('pengarang')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori_id" id="inputKategori" required onchange="updatePreview()">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                        <option value="{{ $k->id }}" data-kode="{{ $k->kode }}"
                            {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('kategori_id')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Stok / Jumlah Eksemplar</label>
                    <input type="number" name="stok" id="inputStok"
                        value="{{ old('stok', 1) }}" min="1" max="999" required
                        oninput="updatePreview()">
                    <p class="form-hint">Kode buku akan dibuat sejumlah stok ini</p>
                    @error('stok')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Penerbit <span style="color:#9ca3af;font-weight:400">(opsional)</span></label>
                    <input type="text" name="penerbit" value="{{ old('penerbit') }}" placeholder="Nama penerbit">
                    @error('penerbit')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Tahun Terbit <span style="color:#9ca3af;font-weight:400">(opsional)</span></label>
                    <input type="text" name="tahun_terbit" value="{{ old('tahun_terbit') }}"
                        placeholder="{{ date('Y') }}" maxlength="4">
                    @error('tahun_terbit')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- ── UPLOAD SAMPUL ── --}}
            <div class="form-group">
                <label>
                    Sampul Buku
                    <span style="color:#9ca3af;font-weight:400">(opsional · JPG/PNG/WebP · maks. 2 MB)</span>
                </label>

                <div class="sampul-upload-area" id="uploadArea"
                     ondragover="event.preventDefault();this.classList.add('dragover')"
                     ondragleave="this.classList.remove('dragover')"
                     ondrop="handleDrop(event)">
                    <input type="file" name="sampul" id="sampulInput"
                           accept="image/jpeg,image/png,image/webp"
                           onchange="previewSampul(this)">
                    <svg class="sampul-icon" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5M21 3.75H3A.75.75 0 002.25 4.5v15"/>
                    </svg>
                    <div class="sampul-upload-text">Klik atau seret gambar ke sini</div>
                    <div class="sampul-upload-hint">JPG, PNG, WebP — maksimal 2 MB</div>
                </div>

                {{-- Pratinjau gambar terpilih --}}
                <div class="sampul-preview-wrap" id="sampulPreviewWrap">
                    <img src="" alt="Preview sampul" id="sampulPreviewImg">
                    <button type="button" class="sampul-remove-btn"
                            onclick="hapusPilihan()" title="Hapus pilihan">✕</button>
                </div>

                @error('sampul')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex;gap:8px;margin-top:1.5rem">
                <button type="submit" class="btn btn-primary">Simpan & Buat Kode Buku</button>
                <a href="{{ route('buku.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    {{-- PREVIEW KODE --}}
    <div class="preview-card">
        <div class="preview-title">Preview Kode Buku</div>
        <div id="previewEmpty" style="text-align:center;padding:2rem 1rem;color:#9ca3af;font-size:13px">
            <svg width="32" height="32" viewBox="0 0 16 16" fill="#d1d5db" style="margin-bottom:8px">
                <path d="M2 2h5v12H2V2zm7 0h5v12H9V2z"/>
            </svg>
            <p>Isi form untuk melihat preview kode buku</p>
        </div>
        <div id="previewList" style="display:none">
            <div class="kode-preview-list" id="kodeList"></div>
            <div class="more-info" id="moreInfo" style="display:none"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Preview kode buku ──
let nextId = 'XXX';
function updatePreview() {
    const stok   = parseInt(document.getElementById('inputStok').value) || 0;
    const katEl  = document.getElementById('inputKategori');
    const katOpt = katEl.options[katEl.selectedIndex];
    const katKode = katOpt && katOpt.dataset.kode ? katOpt.dataset.kode.trim() : 'GEN';
    const empty  = document.getElementById('previewEmpty');
    const list   = document.getElementById('previewList');
    const kodeEl = document.getElementById('kodeList');
    const more   = document.getElementById('moreInfo');
    if (stok < 1) { empty.style.display='block'; list.style.display='none'; return; }
    empty.style.display = 'none';
    list.style.display  = 'block';
    kodeEl.innerHTML    = '';
    const tampil = Math.min(stok, 8);
    for (let i = 1; i <= tampil; i++) {
        const kode = `${katKode}-${nextId}-${String(i).padStart(4,'0')}`;
        kodeEl.innerHTML += `
            <div class="kode-chip">
                <span class="kode-num">Eksemplar ${i}</span>
                <span class="kode-val">${kode}</span>
                <span class="kode-status"></span>
            </div>`;
    }
    if (stok > 8) {
        more.style.display = 'block';
        more.textContent   = `+ ${stok - 8} kode lainnya akan dibuat otomatis`;
    } else { more.style.display = 'none'; }
}
updatePreview();

// ── Upload sampul ──
function previewSampul(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file maksimal 2 MB.'); hapusPilihan(); return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('sampulPreviewImg').src = e.target.result;
        document.getElementById('sampulPreviewWrap').style.display = 'block';
        document.getElementById('uploadArea').style.display = 'none';
    };
    reader.readAsDataURL(file);
}

function hapusPilihan() {
    document.getElementById('sampulInput').value      = '';
    document.getElementById('sampulPreviewImg').src   = '';
    document.getElementById('sampulPreviewWrap').style.display = 'none';
    document.getElementById('uploadArea').style.display = 'block';
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('uploadArea').classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (!file || !file.type.startsWith('image/')) {
        alert('File harus berupa gambar.'); return;
    }
    // Assign ke input file agar ikut tersubmit
    const dt = new DataTransfer();
    dt.items.add(file);
    const inp = document.getElementById('sampulInput');
    inp.files = dt.files;
    previewSampul(inp);
}
</script>
@endpush
@endsection
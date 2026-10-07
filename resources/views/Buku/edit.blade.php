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
.stok-info{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.75rem 1rem;font-size:12px;color:#92400e;margin-top:.5rem}
.stok-info.kurang{background:#fef2f2;border-color:#fecaca;color:#b91c1c}
.info-title{font-size:14px;font-weight:600;color:#111827;margin-bottom:1rem;padding-bottom:.75rem;border-bottom:1px solid #f3f4f6}
.kode-item{padding:.4rem .75rem;background:#f9fafb;border-radius:6px;font-size:11px;font-family:monospace;color:#2563eb;margin-bottom:4px}

/* ── Sampul saat ini ── */
.sampul-current{border-radius:8px;overflow:hidden;border:1px solid #e5e7eb;position:relative;margin-bottom:10px;}
.sampul-current img{width:100%;height:160px;object-fit:cover;display:block;}
.sampul-current-label{position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,0.45);color:#fff;font-size:11px;font-weight:500;padding:5px 10px;}
.hapus-sampul-wrap{display:flex;align-items:center;gap:6px;margin-top:6px;font-size:12px;color:#dc2626;cursor:pointer;}
.hapus-sampul-wrap input{width:auto;cursor:pointer;}

/* ── Upload baru ── */
.sampul-upload-area{
    border:2px dashed #d1d5db;border-radius:10px;
    padding:1.25rem;text-align:center;cursor:pointer;
    transition:all .15s;background:#fafafa;position:relative;
}
.sampul-upload-area:hover,.sampul-upload-area.dragover{border-color:#2563eb;background:#eff6ff;}
.sampul-upload-area input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
.sampul-icon{width:36px;height:36px;margin:0 auto 6px;color:#9ca3af;}
.sampul-upload-text{font-size:12px;font-weight:500;color:#374151;}
.sampul-upload-hint{font-size:11px;color:#9ca3af;margin-top:2px;}
.sampul-preview-wrap{display:none;position:relative;margin-top:8px;}
.sampul-preview-wrap img{width:100%;max-height:160px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;}
.sampul-remove-btn{position:absolute;top:6px;right:6px;background:rgba(0,0,0,0.6);color:#fff;border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;}
.sampul-remove-btn:hover{background:rgba(220,38,38,.8);}
.sampul-placeholder{height:160px;background:linear-gradient(135deg,#dbeafe,#eff6ff);border-radius:8px;display:flex;align-items:center;justify-content:center;border:1px solid #e5e7eb;margin-bottom:10px;}
</style>
@endpush

@section('content')
<div style="margin-bottom:1.25rem">
    <h1 style="font-size:20px;font-weight:600;color:#111827">Edit Buku</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px">Ubah data: <strong>{{ $buku->judul }}</strong></p>
</div>

<div class="layout-2col">
    <div class="form-card">
        <form method="POST" action="{{ route('buku.update', $buku->id) }}" enctype="multipart/form-data">
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
                        <option value="{{ $k->id }}" data-kode="{{ $k->kode }}"
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
                    <p class="form-hint">Stok saat ini: <strong>{{ $buku->stok }}</strong></p>
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
                    <input type="text" name="tahun_terbit"
                        value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" maxlength="4">
                    @error('tahun_terbit')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- ── SAMPUL ── --}}
            <div class="form-group">
                <label>
                    Sampul Buku
                    <span style="color:#9ca3af;font-weight:400">(JPG/PNG/WebP · maks. 2 MB)</span>
                </label>

                @if($buku->sampul_url)
                {{-- Ada sampul saat ini --}}
                <div class="sampul-current" id="sampulCurrent">
                    <img src="{{ $buku->sampul_url }}" alt="Sampul saat ini">
                    <div class="sampul-current-label">Sampul saat ini</div>
                </div>
                <label class="hapus-sampul-wrap" id="hapusSampulWrap">
                    <input type="checkbox" name="hapus_sampul" value="1"
                           id="cbHapusSampul" onchange="toggleHapusSampul(this)">
                    Hapus sampul ini
                </label>
                @else
                {{-- Tidak ada sampul --}}
                <div class="sampul-placeholder" id="sampulPlaceholder">
                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none"
                         stroke="#93c5fd" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909"/>
                    </svg>
                </div>
                @endif

                {{-- Area upload baru --}}
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
                              d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                    </svg>
                    <div class="sampul-upload-text">
                        {{ $buku->sampul_url ? 'Ganti dengan gambar baru' : 'Klik atau seret gambar ke sini' }}
                    </div>
                    <div class="sampul-upload-hint">JPG, PNG, WebP — maks. 2 MB</div>
                </div>

                <div class="sampul-preview-wrap" id="sampulPreviewWrap">
                    <img src="" alt="Preview" id="sampulPreviewImg">
                    <button type="button" class="sampul-remove-btn" onclick="hapusPilihan()">✕</button>
                </div>

                @error('sampul')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex;gap:8px;margin-top:1.5rem">
                <button type="submit" class="btn btn-primary">Update Buku</button>
                <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    {{-- INFO KODE --}}
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
        <div>
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
            <a href="{{ route('buku.show', $buku->id) }}"
               style="font-size:13px;color:#2563eb;text-decoration:none;display:flex;align-items:center;gap:4px">
                ← Lihat semua kode buku
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
        el.className = 'stok-info'; el.style.display = 'block';
        el.innerHTML = `<strong>${stokBaru-stokLama} kode buku baru</strong> akan dibuat (nomor ${stokLama+1} s/d ${stokBaru}).`;
    } else if (stokBaru < stokLama) {
        el.className = 'stok-info kurang'; el.style.display = 'block';
        el.innerHTML = `<strong>${stokLama-stokBaru} kode buku</strong> nomor urut tertinggi akan dihapus.`;
    } else { el.style.display = 'none'; }
}

function previewSampul(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (file.size > 2*1024*1024) { alert('Ukuran file maksimal 2 MB.'); hapusPilihan(); return; }
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('sampulPreviewImg').src = e.target.result;
        document.getElementById('sampulPreviewWrap').style.display = 'block';
        document.getElementById('uploadArea').style.display = 'none';
        // Sembunyikan sampul lama sementara
        const cur = document.getElementById('sampulCurrent');
        const ph  = document.getElementById('sampulPlaceholder');
        if (cur) cur.style.display = 'none';
        if (ph)  ph.style.display  = 'none';
    };
    reader.readAsDataURL(file);
}

function hapusPilihan() {
    document.getElementById('sampulInput').value    = '';
    document.getElementById('sampulPreviewImg').src = '';
    document.getElementById('sampulPreviewWrap').style.display = 'none';
    document.getElementById('uploadArea').style.display = 'block';
    const cur = document.getElementById('sampulCurrent');
    const ph  = document.getElementById('sampulPlaceholder');
    if (cur) cur.style.display = 'block';
    if (ph)  ph.style.display  = 'flex';
    const cb = document.getElementById('cbHapusSampul');
    if (cb) cb.checked = false;
}

function toggleHapusSampul(cb) {
    const cur = document.getElementById('sampulCurrent');
    if (cur) cur.style.opacity = cb.checked ? '0.3' : '1';
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('uploadArea').classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (!file || !file.type.startsWith('image/')) { alert('File harus berupa gambar.'); return; }
    const dt = new DataTransfer(); dt.items.add(file);
    const inp = document.getElementById('sampulInput');
    inp.files = dt.files;
    previewSampul(inp);
}
</script>
@endpush
@endsection
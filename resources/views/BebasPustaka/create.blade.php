@extends('layouts.app')

@push('styles')
<style>
.layout-2col{display:grid;grid-template-columns:1fr 380px;gap:16px;align-items:start}
.form-card,.info-card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:1.5rem}
.form-group{margin-bottom:1.1rem}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
label{display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px}
input[type=text],input[type=date],select{width:100%;padding:.6rem .875rem;border:1px solid #e5e7eb;border-radius:8px;font-size:13px;font-family:inherit;color:#111827;outline:none;transition:border-color .15s,box-shadow .15s;background:#fff}
input:focus,select:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1)}
input.error,select.error{border-color:#dc2626}
.btn{padding:.6rem 1.25rem;border-radius:8px;font-size:13px;font-weight:500;font-family:inherit;border:none;cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
.btn-primary{background:#2563eb;color:#fff}.btn-primary:hover{background:#1d4ed8}
.btn-secondary{background:#f3f4f6;color:#374151}.btn-secondary:hover{background:#e5e7eb}
.error-msg{font-size:12px;color:#dc2626;margin-top:4px}
.form-hint{font-size:11px;color:#9ca3af;margin-top:4px}
/* Info panel */
.info-card .card-title{font-size:14px;font-weight:600;color:#111827;margin-bottom:1rem;padding-bottom:.75rem;border-bottom:1px solid #f3f4f6}
.info-empty{text-align:center;padding:2rem 1rem;color:#9ca3af;font-size:13px}
.anggota-detail{display:none}
.detail-row{display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #f9fafb;font-size:13px}
.detail-row:last-child{border:none}
.detail-lbl{color:#6b7280}
.detail-val{font-weight:500;color:#111827;text-align:right}
/* Status peminjaman */
.status-pinjam{margin-top:1rem;border-radius:8px;overflow:hidden}
.status-header{padding:.6rem .875rem;font-size:12px;font-weight:600;display:flex;align-items:center;gap:6px}
.status-ok .status-header{background:#f0fdf4;color:#16a34a}
.status-err .status-header{background:#fef2f2;color:#dc2626}
.buku-list{padding:.5rem .875rem;background:#fafafa;border-top:1px solid #f3f4f6}
.buku-item{display:flex;justify-content:space-between;padding:.4rem 0;font-size:12px;border-bottom:1px solid #f3f4f6}
.buku-item:last-child{border:none}
/* Sudah ada badge */
.sudah-ada{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.75rem;margin-top:1rem;font-size:12px;color:#92400e}
.loading-dot{display:inline-block;width:8px;height:8px;border-radius:50%;background:#9ca3af;animation:pulse 1s ease-in-out infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}
</style>
@endpush

@section('content')
<div style="margin-bottom:1.25rem">
    <h1 style="font-size:20px;font-weight:600;color:#111827">Tambah Bebas Pustaka</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px">Buat surat keterangan bebas pustaka untuk anggota</p>
</div>

<div class="layout-2col">
    {{-- FORM KIRI --}}
    <div class="form-card">
        <form method="POST" action="{{ route('bebaspustaka.store') }}" id="formBebas">
            @csrf

            <div class="form-group">
                <label>Pilih Anggota</label>
                <select name="anggota_id" id="anggotaSelect" required onchange="loadAnggota(this.value)"
                    class="{{ $errors->has('anggota_id') ? 'error' : '' }}">
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($anggota->groupBy('kelas') as $kelas => $list)
                    <optgroup label="Kelas {{ $kelas }}">
                        @foreach($list as $a)
                        <option value="{{ $a->id }}" {{ old('anggota_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->nama }}
                        </option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
                @error('anggota_id')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>Nomor Surat <span style="color:#9ca3af;font-weight:400">(opsional)</span></label>
                <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}"
                    placeholder="Otomatis jika dikosongkan">
                <p class="form-hint">Format: 001/BP/SMAN5TEBO/06/2026</p>
                @error('nomor_surat')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Tahun Ajaran</label>
                    <select name="tahun_ajaran" required>
                        <option value="">-- Pilih --</option>
                        @foreach($tahunAjaran as $ta)
                        <option value="{{ $ta }}" {{ old('tahun_ajaran') == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                        @endforeach
                    </select>
                    @error('tahun_ajaran')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Keperluan</label>
                    <select name="keperluan" required>
                        <option value="">-- Pilih --</option>
                        @foreach($keperluan as $k)
                        <option value="{{ $k }}" {{ old('keperluan') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                    @error('keperluan')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="aktif" {{ old('status','aktif')=='aktif' ? 'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status')=='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                    </select>
                    @error('status')<p class="error-msg">{{ $message }}</p>@enderror
                </div>
            </div>

            <div style="display:flex;gap:8px;margin-top:1.5rem">
                <button type="submit" id="btnSubmit" class="btn btn-primary">Simpan & Buat Surat</button>
                <a href="{{ route('bebaspustaka.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    {{-- PANEL KANAN — info anggota --}}
    <div class="info-card">
        <div class="card-title">Info Anggota & Status Peminjaman</div>

        {{-- Kosong --}}
        <div id="infoEmpty" class="info-empty">
            <svg width="40" height="40" viewBox="0 0 16 16" fill="#d1d5db" style="margin-bottom:8px"><circle cx="8" cy="5" r="3"/><path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6H2z"/></svg>
            <p>Pilih anggota untuk melihat detail dan status peminjaman</p>
        </div>

        {{-- Loading --}}
        <div id="infoLoading" style="display:none;text-align:center;padding:2rem;color:#6b7280;font-size:13px">
            <span class="loading-dot"></span>
            <span style="margin-left:8px">Memuat data anggota...</span>
        </div>

        {{-- Detail --}}
        <div id="infoDetail" class="anggota-detail">
            <div class="detail-row"><span class="detail-lbl">Nama</span><span class="detail-val" id="dNama">-</span></div>
            <div class="detail-row"><span class="detail-lbl">Kelas</span><span class="detail-val" id="dKelas">-</span></div>
            <div class="detail-row"><span class="detail-lbl">No. HP</span><span class="detail-val" id="dHp">-</span></div>

            <div class="status-pinjam" id="statusPinjam"></div>
            <div id="sudahAdaInfo" style="display:none" class="sudah-ada"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function loadAnggota(id) {
    const empty   = document.getElementById('infoEmpty');
    const loading = document.getElementById('infoLoading');
    const detail  = document.getElementById('infoDetail');
    const btn     = document.getElementById('btnSubmit');

    if (!id) {
        empty.style.display = 'block';
        loading.style.display = 'none';
        detail.style.display = 'none';
        return;
    }

    empty.style.display = 'none';
    loading.style.display = 'block';
    detail.style.display = 'none';

    fetch(`/bebaspustaka/cek/${id}`)
        .then(r => r.json())
        .then(data => {
            loading.style.display = 'none';
            detail.style.display = 'block';

            document.getElementById('dNama').textContent  = data.anggota.nama;
            document.getElementById('dKelas').textContent = data.anggota.kelas;
            document.getElementById('dHp').textContent    = data.anggota.no_hp;

            // Status peminjaman
            const sp = document.getElementById('statusPinjam');
            if (data.aman) {
                sp.className = 'status-pinjam status-ok';
                sp.innerHTML = `<div class="status-header">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M3 8l3.5 3.5 6.5-7" stroke="#16a34a" stroke-width="2" stroke-linecap="round"/></svg>
                    Tidak ada buku yang masih dipinjam
                </div>`;
            } else {
                sp.className = 'status-pinjam status-err';
                let items = data.buku_pinjam.map(b =>
                    `<div class="buku-item"><span>${b.judul}</span><span style="color:#9ca3af">${b.tanggal_pinjam}</span></div>`
                ).join('');
                sp.innerHTML = `<div class="status-header">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6" stroke="#dc2626" stroke-width="1.5"/><path d="M8 5v3M8 10v1" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round"/></svg>
                    ${data.jumlah} buku belum dikembalikan
                </div><div class="buku-list">${items}</div>`;
            }

            // Sudah ada bebas pustaka
            const sudahEl = document.getElementById('sudahAdaInfo');
            if (data.sudah_bebas) {
                sudahEl.style.display = 'block';
                sudahEl.innerHTML = `<strong>Perhatian:</strong> Anggota ini sudah memiliki surat bebas pustaka
                    (No. ${data.sudah_bebas.nomor ?? '-'}, Status: ${data.sudah_bebas.status}).
                    Menyimpan akan menimpa data lama.`;
            } else {
                sudahEl.style.display = 'none';
            }

            // Disable tombol simpan jika ada pinjaman aktif
            btn.disabled = !data.aman;
            btn.style.opacity = data.aman ? '1' : '.5';
            btn.style.cursor  = data.aman ? 'pointer' : 'not-allowed';
            btn.title = data.aman ? '' : 'Anggota masih memiliki buku yang dipinjam';
        })
        .catch(() => {
            loading.style.display = 'none';
            detail.style.display = 'block';
        });
}

// Jika ada old value (validation error), reload info
const oldId = '{{ old("anggota_id") }}';
if (oldId) loadAnggota(oldId);
</script>
@endpush
@endsection
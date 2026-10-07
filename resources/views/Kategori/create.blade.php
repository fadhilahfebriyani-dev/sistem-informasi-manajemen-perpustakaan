@extends('layouts.app')

@push('styles')
<style>
    .form-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.5rem; max-width:560px; }
    .form-group { margin-bottom:1.1rem; }
    label { display:block; font-size:12px; font-weight:500; color:#374151; margin-bottom:6px; }
    input[type="text"], textarea, select {
        width:100%; padding:0.65rem 0.875rem;
        border:1px solid #e5e7eb; border-radius:8px;
        font-size:14px; font-family:inherit; color:#111827;
        outline:none; transition:border-color 0.15s, box-shadow 0.15s;
        background:#fff;
    }
    input[type="text"]:focus, textarea:focus, select:focus {
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

    /* DDC Quick Pick */
    .ddc-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:6px; margin-top:6px; }
    .ddc-btn {
        padding:0.4rem 0.5rem; border-radius:6px; font-size:11px; font-weight:600;
        border:1px solid #e5e7eb; background:#f9fafb; color:#374151;
        cursor:pointer; text-align:center; transition:all 0.15s;
    }
    .ddc-btn:hover { border-color:#2563eb; color:#2563eb; background:#eff6ff; }
</style>
@endpush

@section('content')
<x-alert />

<div style="margin-bottom:1.25rem;">
    <h1 style="font-size:20px;font-weight:600;color:#111827;">Tambah Kategori</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px;">Tambahkan kategori DDC baru ke perpustakaan</p>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('kategori.store') }}">
        @csrf

        <div class="form-group">
            <label for="kode">Kode DDC</label>
            <input type="text" id="kode" name="kode" value="{{ old('kode') }}"
                placeholder="contoh: 000, 100, FIK, REF" maxlength="10" required>
            <div class="hint">Kode unik kategori DDC (000–900, FIK, REF, dll)</div>

            {{-- DDC Quick Pick --}}
            <div class="ddc-grid" style="margin-top:8px;">
                @foreach([
                    '000'=>'Karya Umum','100'=>'Filsafat','200'=>'Agama','300'=>'Sosial',
                    '400'=>'Bahasa','500'=>'Sains','600'=>'Terapan','700'=>'Seni',
                    '800'=>'Sastra','900'=>'Sejarah','FIK'=>'Fiksi','REF'=>'Referensi'
                ] as $kode => $label)
                <div class="ddc-btn" onclick="pilihKode('{{ $kode }}','{{ $label }}')">
                    {{ $kode }}
                </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label for="nama">Nama Kategori</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                placeholder="contoh: Sains & Matematika" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi <span style="color:#9ca3af;font-weight:400;">(opsional)</span></label>
            <textarea id="deskripsi" name="deskripsi" placeholder="Contoh buku dalam kategori ini...">{{ old('deskripsi') }}</textarea>
        </div>

        <div style="display:flex;gap:8px;margin-top:0.5rem;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
const deskripsiDefault = {
    '000': 'Ensiklopedia, majalah, jurnal, karya umum lainnya',
    '100': 'Buku pengembangan diri, filsafat, psikologi',
    '200': 'Buku-buku keagamaan dan kerohanian',
    '300': 'PKN, sosiologi, ekonomi, ilmu sosial lainnya',
    '400': 'Bahasa Inggris, Bahasa Indonesia, kamus, linguistik',
    '500': 'Fisika, biologi, kimia, matematika, astronomi',
    '600': 'Kesehatan, teknik, pertanian, manajemen',
    '700': 'Semua yang berhubungan dengan kesenian dan rekreasi',
    '800': 'Novel, puisi, drama, karya sastra',
    '900': 'Peta, biografi, sejarah dunia, geografi',
    'FIK': 'Koleksi buku fiksi perpustakaan',
    'REF': 'Koleksi referensi perpustakaan',
};
const namaDefault = {
    '000':'Karya Umum','100':'Filsafat & Psikologi','200':'Agama',
    '300':'Ilmu Sosial','400':'Bahasa','500':'Sains & Matematika',
    '600':'Kesehatan & Ilmu Terapan','700':'Seni & Rekreasi',
    '800':'Sastra','900':'Geografi & Sejarah','FIK':'Fiksi','REF':'Referensi',
};

function pilihKode(kode, label) {
    document.getElementById('kode').value = kode;
    document.getElementById('nama').value = namaDefault[kode] || label;
    document.getElementById('deskripsi').value = deskripsiDefault[kode] || '';
}
</script>
@endpush

@endsection
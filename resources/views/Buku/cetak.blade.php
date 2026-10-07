<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Label Buku — SIMPERPUS</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, sans-serif; background: #f3f4f6; padding: 16px; }

/* ── Toolbar ── */
.toolbar {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
    padding: 14px 16px; margin-bottom: 12px;
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.toolbar-title { font-size: 14px; font-weight: 700; color: #111827; }
.toolbar-sub   { font-size: 12px; color: #9ca3af; margin-top: 2px; }
.toolbar-spacer{ flex: 1; }

.btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: 8px; font-size: 13px;
    font-weight: 600; cursor: pointer; border: none;
    text-decoration: none; white-space: nowrap; transition: background .15s;
    font-family: Arial, sans-serif;
}
.btn-primary   { background: #2563eb; color: #fff; }
.btn-primary:hover  { background: #1d4ed8; }
.btn-primary:disabled { background: #93c5fd; cursor: not-allowed; }
.btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
.btn-secondary:hover { background: #e5e7eb; }
.btn-danger    { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
.btn-danger:hover { background: #fecaca; }

/* ── Filter bar ── */
.filter-bar {
    background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
    padding: 14px 16px; margin-bottom: 12px;
    display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
}
.filter-group { display: flex; flex-direction: column; gap: 4px; }
.filter-group label {
    font-size: 11px; font-weight: 600; color: #6b7280;
    text-transform: uppercase; letter-spacing: .05em;
}
.filter-group select,
.filter-group input[type="date"] {
    padding: 6px 10px; border: 1px solid #d1d5db; border-radius: 8px;
    font-size: 13px; color: #111827; outline: none; background: #fff;
    min-width: 150px; font-family: Arial, sans-serif;
}
.filter-group select:focus,
.filter-group input[type="date"]:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
}

/* ── Stat badges ── */
.stat-row { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
.stat-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 12px; border-radius: 100px;
    font-size: 12px; font-weight: 600;
}
.stat-belum  { background: #fef9c3; color: #a16207; }
.stat-sudah  { background: #dcfce7; color: #15803d; }
.stat-tampil { background: #dbeafe; color: #1d4ed8; }

/* ── Pilih manual ── */
.panel-manual {
    display: none;
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 12px; padding: 14px 16px; margin-bottom: 12px;
}
.panel-manual.aktif { display: block; }
.panel-manual h4 { font-size: 13px; font-weight: 700; color: #111827; margin-bottom: 10px; }
.manual-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 6px;
    max-height: 220px;
    overflow-y: auto;
}
.manual-grid label {
    display: flex; align-items: flex-start; gap: 8px;
    font-size: 12px; color: #374151;
    padding: 6px 8px; border: 1px solid #e5e7eb;
    border-radius: 8px; cursor: pointer; transition: background .1s;
}
.manual-grid label:hover { background: #f0f9ff; }
.manual-grid input[type="checkbox"] { margin-top: 2px; flex-shrink: 0; accent-color: #2563eb; }
.buku-tanggal { font-size: 10px; color: #9ca3af; }

/* ── Empty state ── */
.empty-state {
    text-align: center; padding: 60px 20px;
    color: #6b7280; font-size: 14px;
}
.empty-state .icon { font-size: 48px; margin-bottom: 12px; }

/* ── Label grid ── */
.label-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.label {
    background: #fff;
    border: 1.5px solid #d1d5db;
    border-radius: 6px;
    padding: 10px 8px;
    display: flex; flex-direction: column; align-items: center; gap: 5px;
    page-break-inside: avoid; break-inside: avoid;
    min-height: 175px;
}
.label-sekolah {
    font-size: 7px; font-weight: 600; color: #9ca3af;
    text-transform: uppercase; letter-spacing: .06em; text-align: center;
}
.label-divider { width: 100%; border: none; border-top: 1px dashed #e5e7eb; }
.label-ddc {
    font-size: 18px; font-weight: 700;
    padding: 2px 10px; border-radius: 100px; letter-spacing: .04em;
}
.label-kategori { font-size: 8px; color: #6b7280; text-align: center; }
.label-qr {
    min-height: 90px;
    display: flex; align-items: center; justify-content: center;
}
.label-qr canvas { width: 90px !important; height: 90px !important; }
.label-kode {
    font-size: 9px; font-family: monospace;
    font-weight: 700; color: #2563eb; letter-spacing: .05em;
}
.label-judul {
    font-size: 7.5px; color: #374151; text-align: center;
    line-height: 1.4; max-width: 110px; font-weight: 600;
}
.badge-dicetak {
    font-size: 8px; background: #dcfce7; color: #15803d;
    border-radius: 100px; padding: 1px 6px; font-weight: 600;
}

/* ── Toast ── */
#toast {
    position: fixed; bottom: 20px; right: 20px; z-index: 9999;
    background: #111827; color: #fff;
    padding: 10px 18px; border-radius: 10px;
    font-size: 13px; display: none;
    box-shadow: 0 4px 16px rgba(0,0,0,.2);
    transition: opacity .3s;
}

/* ── Print ── */
@media print {
    body { background: #fff; padding: 0; }
    .toolbar, .filter-bar, .stat-row, .panel-manual, #toast { display: none !important; }
    .label-grid { grid-template-columns: repeat(4, 1fr); gap: 6px; }
    .label { border: 1px solid #000; min-height: unset; }
    .badge-dicetak { display: none; }
    @page { size: A4 portrait; margin: 8mm; }
}
</style>
</head>
<body>

{{-- ── Toolbar ── --}}
<div class="toolbar">
    <div>
        <div class="toolbar-title">Cetak Label Buku</div>
        <div class="toolbar-sub">SMAN 5 Tebo &nbsp;·&nbsp; Perpustakaan Digital SIMPERPUS</div>
    </div>
    <div class="toolbar-spacer"></div>
    <a href="{{ route('buku.index') }}" class="btn btn-secondary">← Kembali</a>
    <button id="btnCetak" class="btn btn-primary" disabled onclick="handleCetak()">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
            <path d="M4 1h8v3H4V1zM1 5h14v7H1V5zm3 2v4h8V7H4zM2 6v1h1V6H2zm11 0v1h1V6h-1z"/>
        </svg>
        <span id="btnLabel">Menyiapkan QR…</span>
    </button>
</div>

{{-- ── Filter bar ── --}}
<form method="GET" action="{{ route('buku.cetak') }}" id="formFilter">
<div class="filter-bar">

    {{-- Filter status cetak --}}
    <div class="filter-group">
        <label>Tampilkan</label>
        <select name="filter" onchange="toggleManual(this.value); this.form.submit()">
            <option value="belum"  {{ $filter==='belum'  ? 'selected':'' }}>Belum dicetak saja</option>
            <option value="semua"  {{ $filter==='semua'  ? 'selected':'' }}>Semua label</option>
            <option value="manual" {{ $filter==='manual' ? 'selected':'' }}>Pilih buku manual</option>
        </select>
    </div>

    {{-- Filter tanggal buku ditambah --}}
    <div class="filter-group">
        <label>Buku ditambah dari</label>
        <input type="date" name="dari" value="{{ $dari }}" onchange="this.form.submit()">
    </div>
    <div class="filter-group">
        <label>Sampai</label>
        <input type="date" name="sampai" value="{{ $sampai }}" onchange="this.form.submit()">
    </div>

    {{-- Hidden inputs buku_id (mode manual) --}}
    @foreach($bukuIds as $bid)
        <input type="hidden" name="buku_id[]" value="{{ $bid }}">
    @endforeach

    {{-- Reset filter --}}
    @if($dari || $sampai || !empty($bukuIds))
    <div class="filter-group">
        <label>&nbsp;</label>
        <a href="{{ route('buku.cetak', ['filter' => $filter]) }}" class="btn btn-danger" style="font-size:12px;">
            ✕ Reset filter
        </a>
    </div>
    @endif

</div>
</form>

{{-- ── Panel pilih manual ── --}}
<div class="panel-manual {{ $filter==='manual' ? 'aktif':'' }}" id="panelManual">
    <h4>Pilih judul buku yang ingin dicetak labelnya:</h4>
    <div class="manual-grid">
        @foreach($semuaBuku as $sb)
        @php $belumKode = $sb->kodeBuku->where('label_dicetak', false)->count(); @endphp
        <label>
            <input type="checkbox" class="cb-buku" value="{{ $sb->id }}"
                   {{ in_array($sb->id, array_map('intval', $bukuIds)) ? 'checked':'' }}>
            <div>
                <div>{{ $sb->judul }}</div>
                <div class="buku-tanggal">
                    Ditambah: {{ \Carbon\Carbon::parse($sb->created_at)->format('d M Y') }}
                    @if($belumKode > 0)
                        &nbsp;·&nbsp; <span style="color:#a16207;">⚠ {{ $belumKode }} belum dicetak</span>
                    @endif
                </div>
            </div>
        </label>
        @endforeach
    </div>
    <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap;">
        <button type="button" class="btn btn-primary" onclick="terapkanManual()" style="font-size:12px;">Terapkan</button>
        <button type="button" class="btn btn-secondary" onclick="toggleSemuaCb(true)"  style="font-size:12px;">Pilih Semua</button>
        <button type="button" class="btn btn-secondary" onclick="toggleSemuaCb(false)" style="font-size:12px;">Hapus Pilihan</button>
    </div>
</div>

{{-- ── Stat badges ── --}}
<div class="stat-row">
    <span class="stat-badge stat-belum">📋 {{ $totalBelumDicetak }} belum dicetak</span>
    <span class="stat-badge stat-sudah">✅ {{ $totalSudahDicetak }} sudah dicetak</span>
    <span class="stat-badge stat-tampil">
        🖨 {{ $bukus->sum(fn($b) => $b->kodeBuku->count()) }} label akan dicetak
    </span>
</div>

{{-- ── Warna DDC ── --}}
@php
$warna = [
    '000' => ['bg'=>'#f3f4f6','color'=>'#374151'],
    '100' => ['bg'=>'#ede9fe','color'=>'#7c3aed'],
    '200' => ['bg'=>'#fef9c3','color'=>'#a16207'],
    '300' => ['bg'=>'#dbeafe','color'=>'#1d4ed8'],
    '400' => ['bg'=>'#dcfce7','color'=>'#15803d'],
    '500' => ['bg'=>'#e0f2fe','color'=>'#0369a1'],
    '600' => ['bg'=>'#fce7f3','color'=>'#be185d'],
    '700' => ['bg'=>'#ffedd5','color'=>'#c2410c'],
    '800' => ['bg'=>'#f0fdf4','color'=>'#166534'],
    '900' => ['bg'=>'#fef2f2','color'=>'#991b1b'],
    'FIK' => ['bg'=>'#fdf4ff','color'=>'#86198f'],
    'REF' => ['bg'=>'#f8fafc','color'=>'#475569'],
];
@endphp

{{-- ── Label / empty state ── --}}
@if($bukus->isEmpty())
<div class="empty-state">
    <div class="icon">🎉</div>
    <div style="font-weight:700;font-size:15px;color:#111827;margin-bottom:6px;">
        @if($filter==='belum') Semua label sudah dicetak! @else Tidak ada label ditemukan. @endif
    </div>
    <div>
        @if($filter==='belum')
            Tidak ada label baru yang belum dicetak.
            <a href="{{ route('buku.cetak', ['filter'=>'semua']) }}" style="color:#2563eb;">Tampilkan semua</a>
            jika ingin cetak ulang.
        @else
            Coba ubah filter atau
            <a href="{{ route('buku.cetak') }}" style="color:#2563eb;">reset ke default</a>.
        @endif
    </div>
</div>
@else
<div class="label-grid" id="labelGrid">
    @foreach($bukus as $buku)
    @php
        $kode = $buku->kategori?->kode ?? '000';
        $w    = $warna[$kode] ?? ['bg'=>'#f3f4f6','color'=>'#374151'];
    @endphp
    @foreach($buku->kodeBuku as $kb)
    <div class="label">
        <div class="label-sekolah">Perpustakaan SMAN 5 Tebo</div>
        <hr class="label-divider">
        <div class="label-ddc" style="background:{{ $w['bg'] }};color:{{ $w['color'] }};">{{ $kode }}</div>
        <div class="label-kategori">{{ $buku->kategori?->nama ?? '-' }}</div>
        <div class="label-qr">
            <div id="qr-{{ $kb->id }}"></div>
        </div>
        <div class="label-kode">{{ $kb->kode_buku }}</div>
        @if($filter==='semua' && $kb->label_dicetak)
            <div class="badge-dicetak">✓ Sudah dicetak</div>
        @endif
        <hr class="label-divider">
        <div class="label-judul">{{ Str::limit($buku->judul, 35) }}</div>
    </div>
    @endforeach
    @endforeach
</div>
@endif

{{-- ── Toast ── --}}
<div id="toast"></div>

<script>
// ── Data QR ──────────────────────────────────────────────────────
const daftarQR = [
@foreach($bukus as $buku)
    @foreach($buku->kodeBuku as $kb)
    { elId: 'qr-{{ $kb->id }}', url: @json(url('/scan/buku/' . $kb->kode_buku)), kodeId: {{ $kb->id }} },
    @endforeach
@endforeach
];

// ── Buat QR satu per satu (non-blocking) ─────────────────────────
function buatQR(elId, teks) {
    return new Promise(resolve => {
        const el = document.getElementById(elId);
        if (!el) { resolve(); return; }
        new QRCode(el, {
            text: teks, width: 90, height: 90,
            colorDark: '#1a237e', colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M,
        });
        requestAnimationFrame(resolve);
    });
}

async function buatSemuaQR() {
    const btn = document.getElementById('btnCetak');
    const lbl = document.getElementById('btnLabel');
    for (let i = 0; i < daftarQR.length; i++) {
        await buatQR(daftarQR[i].elId, daftarQR[i].url);
        lbl.textContent = `Menyiapkan QR… (${i + 1}/${daftarQR.length})`;
    }
    lbl.textContent = daftarQR.length > 0
        ? `Cetak ${daftarQR.length} Label`
        : 'Tidak ada label';
    if (daftarQR.length > 0) btn.disabled = false;
}

buatSemuaQR();

// ── Cetak + tandai sesudah ────────────────────────────────────────
function handleCetak() {
    window.print();
    window.addEventListener('afterprint', tandaiSudahDicetak, { once: true });
}

function tandaiSudahDicetak() {
    const ids = daftarQR.map(q => q.kodeId);
    if (!ids.length) return;

    fetch('{{ route('buku.tandai-dicetak') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ ids }),
    })
    .then(r => r.json())
    .then(data => {
        tampilToast('✅ ' + data.jumlah + ' label ditandai sudah dicetak.');
        setTimeout(() => location.reload(), 2200);
    })
    .catch(() => tampilToast('⚠️ Gagal menandai. Coba refresh halaman.'));
}

// ── Toast ─────────────────────────────────────────────────────────
function tampilToast(pesan) {
    const t = document.getElementById('toast');
    t.textContent = pesan;
    t.style.display = 'block';
    setTimeout(() => t.style.display = 'none', 3000);
}

// ── Filter manual ─────────────────────────────────────────────────
function toggleManual(val) {
    document.getElementById('panelManual').classList.toggle('aktif', val === 'manual');
}

function terapkanManual() {
    const form = document.getElementById('formFilter');
    // Hapus hidden lama
    form.querySelectorAll('input[name="buku_id[]"]').forEach(el => el.remove());
    // Tambah yang terpilih
    document.querySelectorAll('.cb-buku:checked').forEach(cb => {
        const inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = 'buku_id[]'; inp.value = cb.value;
        form.appendChild(inp);
    });
    form.submit();
}

function toggleSemuaCb(pilih) {
    document.querySelectorAll('.cb-buku').forEach(cb => cb.checked = pilih);
}
</script>
</body>
</html>
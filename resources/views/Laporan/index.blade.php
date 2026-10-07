
@extends($layout)

@push('styles')
<style>
    .btn { padding:0.55rem 1rem; border-radius:8px; font-size:13px; font-weight:500; font-family:inherit; border:none; cursor:pointer; transition:all 0.15s; text-decoration:none; display:inline-flex; align-items:center; gap:6px; }
    .btn-primary { background:#2563eb; color:#fff; }
    .btn-primary:hover { background:#1d4ed8; }
    .btn-green { background:#16a34a; color:#fff; }
    .btn-green:hover { background:#15803d; }
    .stats-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px; margin-bottom:1.25rem; }
    .stat-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.1rem 1.25rem; }
    .stat-icon { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-bottom:10px; }
    .stat-value { font-size:26px; font-weight:600; color:#111827; line-height:1; }
    .stat-label { font-size:12px; color:#6b7280; margin-top:3px; }
    .card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; margin-bottom:1.25rem; }
    .card-header { padding:1rem 1.25rem; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between; }
    .card-title { font-size:14px; font-weight:600; color:#111827; }
    .card-badge { font-size:11px; font-weight:500; padding:2px 8px; border-radius:100px; }
    .badge-blue { background:#eff6ff; color:#2563eb; }
    .badge-amber { background:#fffbeb; color:#d97706; }
    table { width:100%; border-collapse:collapse; }
    thead tr { background:#f9fafb; }
    th { padding:0.65rem 1rem; font-size:12px; font-weight:600; color:#6b7280; text-align:left; border-bottom:1px solid #e5e7eb; }
    td { padding:0.75rem 1rem; font-size:13px; color:#374151; border-bottom:1px solid #f3f4f6; }
    tr:last-child td { border-bottom:none; }
    tr:hover td { background:#f9fafb; }
    .status-badge { display:inline-block; padding:3px 10px; border-radius:100px; font-size:11px; font-weight:500; }
    .status-dipinjam { background:#fffbeb; color:#d97706; }
    .status-dikembalikan { background:#f0fdf4; color:#16a34a; }
    .status-terlambat { background:#fef2f2; color:#dc2626; }
    .filter-card { background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.1rem 1.25rem; margin-bottom:1.25rem; }
    .filter-row { display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap; }
    .filter-group { display:flex; flex-direction:column; gap:5px; }
    .filter-group label { font-size:12px; font-weight:500; color:#374151; }
    .filter-group select, .filter-group input {
        padding:0.55rem 0.875rem; border:1px solid #e5e7eb;
        border-radius:8px; font-size:13px; font-family:inherit; outline:none;
    }
    /* CHART */
    .chart-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:1.25rem; }
    .chart-wrap { height:140px; display:flex; align-items:flex-end; gap:5px; }
    .bar-col { flex:1; display:flex; flex-direction:column; align-items:center; height:100%; justify-content:flex-end; }
    .bar-fill { width:100%; border-radius:3px 3px 0 0; min-height:3px; transition:opacity .15s; cursor:pointer; }
    .bar-fill:hover { opacity:.75; }
    .bar-label { font-size:9px; color:#9ca3af; margin-top:3px; }
    .bar-val { font-size:9px; color:#6b7280; margin-bottom:2px; }
</style>
@endpush

@section('content')
<x-alert />

{{-- HEADER --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
    <div>
        <h1 style="font-size:20px;font-weight:600;color:#111827;">Laporan</h1>
        <p style="font-size:13px;color:#6b7280;margin-top:2px;">Ringkasan data perpustakaan</p>
    </div>
    <div style="display:flex;gap:8px;">
        
        {{-- Export Word --}}
        @php
            $routeWord = Auth::user()->isAdmin() ? 'admin.laporan.word' : 'laporan.export-word';
        @endphp

        <a href="{{ route($routeWord, ['tahun' => $tahun]) }}" class="btn btn-green">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                <path d="M3 1h7l3 3v11H3V1zm7 0v3h3"/>
            </svg>
            Export Word
        </a>

    </div>
</div>

{{-- STAT CARDS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#eff6ff;">
            <svg width="18" height="18" viewBox="0 0 16 16" fill="#2563eb"><path d="M2 2h5v12H2V2zm7 0h5v12H9V2z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($totalStokBuku) }}</div>
        <div class="stat-label">Total Buku</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fffbeb;">
            <svg width="18" height="18" viewBox="0 0 16 16" fill="#d97706"><circle cx="6" cy="5" r="2.5"/><path d="M1 13c0-2.8 2.2-5 5-5s5 2.2 5 5H1z"/><circle cx="11.5" cy="4.5" r="2"/><path d="M10 11.5h5c0-2-1.6-3.5-3.5-3.5"/></svg>
        </div>
        <div class="stat-value">{{ number_format($totalPengunjung) }}</div>
        <div class="stat-label">Total Pengunjung</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef2f2;">
            <svg width="18" height="18" viewBox="0 0 16 16" fill="#dc2626"><path d="M2 3h12v1.5H2V3zm0 3h8v1.5H2V6zm0 3h10v1.5H2V9zm0 3h6v1.5H2V12z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($totalPeminjaman) }}</div>
        <div class="stat-label">Total Peminjaman</div>
    </div>
</div>

{{-- FILTER TAHUN --}}
<div class="filter-card">
    <form method="GET" action="{{ Auth::user()->isAdmin() ? route('admin.laporan') : route('laporan') }}">
        <div class="filter-row">
            <div class="filter-group">
                <label>Tahun</label>
                <select name="tahun">
                    @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Tanggal Awal</label>
                <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
            </div>
            <div class="filter-group">
                <label>Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ Auth::user()->isAdmin() ? route('admin.laporan') : route('laporan') }}" class="btn" style="background:#f3f4f6;color:#374151;">Reset</a>
        </div>
    </form>
</div>

{{-- GRAFIK --}}
<div class="chart-row">
    {{-- Grafik Peminjaman --}}
    <div class="card" style="overflow:visible;">
        <div class="card-header">
            <span class="card-title">Grafik Peminjaman {{ $tahun }}</span>
            <span class="card-badge badge-blue">Per Bulan</span>
        </div>
        <div style="padding:1rem 1.25rem;">
            <div class="chart-wrap" id="chartPinjam"></div>
            <div style="display:flex;gap:5px;margin-top:2px;" id="labelPinjam"></div>
        </div>
    </div>

    {{-- Grafik Pengunjung --}}
    <div class="card" style="overflow:visible;">
        <div class="card-header">
            <span class="card-title">Grafik Pengunjung {{ $tahun }}</span>
            <span class="card-badge badge-amber">Per Bulan</span>
        </div>
        <div style="padding:1rem 1.25rem;">
            <div class="chart-wrap" id="chartKunjung"></div>
            <div style="display:flex;gap:5px;margin-top:2px;" id="labelKunjung"></div>
        </div>
    </div>
</div>

{{-- TABEL REKAP PER BULAN --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Rekap Per Bulan — {{ $tahun }}</span>
        <span class="card-badge badge-blue">{{ $tahun }}</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th style="text-align:center;">Jumlah Peminjaman</th>
                <th style="text-align:center;">Jumlah Pengunjung</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataPerBulan as $no => $d)
            <tr>
                <td style="color:#9ca3af;">{{ $no }}</td>
                <td style="font-weight:500;">{{ $d['bulan'] }}</td>
                <td style="text-align:center;">{{ $d['peminjaman'] > 0 ? $d['peminjaman'] : '-' }}</td>
                <td style="text-align:center;">{{ $d['pengunjung'] > 0 ? $d['pengunjung'] : '-' }}</td>
            </tr>
            @endforeach
            {{-- Rata-rata --}}
            <tr style="background:#f9fafb;font-weight:600;">
                <td colspan="2" style="text-align:center;font-weight:600;">Rata-Rata per Bulan</td>
                <td style="text-align:center;">{{ $rataPinjam }}</td>
                <td style="text-align:center;">{{ $rataKunjung }}</td>
            </tr>
        </tbody>
    </table>
</div>

{{-- TABEL DETAIL PEMINJAMAN --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Detail Peminjaman</span>
        <span style="font-size:12px;color:#9ca3af;">{{ count($peminjaman) }} data</span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:50px;">No</th>
                <th>Buku</th>
                <th>Anggota</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $p)
            <tr>
                <td style="color:#9ca3af;">{{ $loop->iteration }}</td>
                <td style="font-weight:500;color:#111827;">
                    @foreach($p->details as $detail)
                        <div>{{ $detail->buku->judul ?? '-' }}</div>
                    @endforeach
                </td>
                <td>{{ $p->anggota->nama ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                <td>{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '-' }}</td>
                <td>
                    @php
                        $status = strtolower($p->status);
                        $cls = $status === 'dikembalikan' ? 'status-dikembalikan'
                             : ($status === 'terlambat' ? 'status-terlambat' : 'status-dipinjam');
                    @endphp
                    <span class="status-badge {{ $cls }}">{{ ucfirst($p->status) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:2rem;color:#9ca3af;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
const bulanNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
const dataPinjam  = @json(array_values(array_column($dataPerBulan, 'peminjaman')));
const dataKunjung = @json(array_values(array_column($dataPerBulan, 'pengunjung')));

function buatGrafik(data, wrapId, labelId, warna) {
    const wrap   = document.getElementById(wrapId);
    const labels = document.getElementById(labelId);
    if (!wrap || !labels) return;
    const max = Math.max(...data, 1);
    data.forEach((val, i) => {
        const pct = Math.round((val / max) * 100);
        wrap.innerHTML += `
            <div class="bar-col">
                <div class="bar-val">${val > 0 ? val : ''}</div>
                <div class="bar-fill" style="height:${Math.max(pct,2)}%;background:${warna};"
                     title="${bulanNames[i]}: ${val}"></div>
            </div>`;
        labels.innerHTML += `<div style="flex:1;text-align:center;font-size:9px;color:#9ca3af;">${bulanNames[i]}</div>`;
    });
}

buatGrafik(dataPinjam,  'chartPinjam',  'labelPinjam',  '#2563eb');
buatGrafik(dataKunjung, 'chartKunjung', 'labelKunjung', '#d97706');
</script>
@endpush

@endsection
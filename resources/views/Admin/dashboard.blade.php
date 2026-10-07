@extends('layouts.admin')

@push('styles')
<style>
    .page-header { margin-bottom: 1.5rem; }
    .page-header h1 { font-size: 20px; font-weight: 600; color: var(--text-primary); }
    .page-header p  { font-size: 13px; color: var(--text-secondary); margin-top: 3px; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-bottom: 1.25rem; }
    .stat-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius); padding: 1.1rem 1.25rem; display: flex; flex-direction: column; gap: 10px; }
    .stat-top { display: flex; align-items: center; justify-content: space-between; }
    .stat-icon { width: 36px; height: 36px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; }
    .stat-trend { font-size: 11px; font-weight: 500; padding: 2px 7px; border-radius: 100px; }
    .trend-amber   { background: #fffbeb; color: #d97706; }
    .trend-blue    { background: #eff6ff; color: #2563eb; }
    .trend-green   { background: #f0fdf4; color: #16a34a; }
    .trend-neutral { background: #f3f4f6; color: #6b7280; }
    .stat-value { font-size: 28px; font-weight: 600; color: var(--text-primary); line-height: 1; }
    .stat-label { font-size: 12px; color: var(--text-secondary); }
    .bottom-row { display: grid; grid-template-columns: 2fr 1fr; gap: 12px; }
    .card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius); padding: 1.25rem; }
    .card-title { font-size: 14px; font-weight: 600; color: var(--text-primary); }
    .card-badge { font-size: 11px; font-weight: 500; background: #fffbeb; color: #d97706; padding: 2px 8px; border-radius: 100px; }
    .chart-wrap { height: 140px; display: flex; align-items: flex-end; gap: 5px; }
    .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; height: 100%; justify-content: flex-end; }
    .bar-fill { width: 100%; border-radius: 3px 3px 0 0; min-height: 4px; transition: opacity 0.15s; }
    .bar-fill:hover { opacity: 0.75; }
    .chart-empty { width: 100%; height: 140px; display: flex; align-items: center; justify-content: center; }
    .chart-empty span { font-size: 13px; color: var(--text-muted); }
    .quick-list { display: flex; flex-direction: column; }
    .quick-item { display: flex; align-items: center; gap: 12px; padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6; }
    .quick-item:last-child { border-bottom: none; }
    .quick-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .quick-label { font-size: 13px; color: var(--text-secondary); flex: 1; }
    .quick-value { font-size: 15px; font-weight: 600; color: var(--text-primary); }
    .akses-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .akses-item { display: flex; align-items: center; gap: 10px; padding: 0.75rem 1rem; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; text-decoration: none; transition: all 0.15s; }
    .akses-item:hover { background: #fffbeb; border-color: #fde68a; }
    .akses-icon { width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .akses-label { font-size: 12px; font-weight: 500; color: var(--text-primary); }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong>. Berikut ringkasan data perpustakaan.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background:#fffbeb;">
                <svg width="18" height="18" viewBox="0 0 16 16" fill="#d97706"><circle cx="8" cy="5.5" r="2.5"/><path d="M2.5 13.5c0-3 2.5-5 5.5-5s5.5 2 5.5 5H2.5z"/></svg>
            </div>
            <span class="stat-trend trend-amber">Petugas</span>
        </div>
        <div class="stat-value">{{ number_format($totalPetugas) }}</div>
        <div class="stat-label">Petugas perpustakaan</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background:#eff6ff;">
                <svg width="18" height="18" viewBox="0 0 16 16" fill="#2563eb"><path d="M2 2h5v12H2V2zm7 0h5v12H9V2z"/></svg>
            </div>
            <span class="stat-trend trend-blue">Buku</span>
        </div>
        <div class="stat-value">{{ number_format($totalStokBuku) }}</div>
        <div class="stat-label">Total seluruh buku</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background:#f0fdf4;">
                <svg width="18" height="18" viewBox="0 0 16 16" fill="#16a34a"><circle cx="6" cy="5" r="2.5"/><path d="M1 13c0-2.8 2.2-5 5-5s5 2.2 5 5H1z"/><circle cx="11.5" cy="4.5" r="2"/><path d="M10 11.5h5c0-2-1.6-3.5-3.5-3.5"/></svg>
            </div>
            <span class="stat-trend trend-green">Anggota</span>
        </div>
        <div class="stat-value">{{ number_format($totalAnggota) }}</div>
        <div class="stat-label">Anggota terdaftar</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background:#f3f4f6;">
                <svg width="18" height="18" viewBox="0 0 16 16" fill="#6b7280"><path d="M2 3h12v1.5H2V3zm0 3h8v1.5H2V6zm0 3h10v1.5H2V9zm0 3h6v1.5H2V12z"/></svg>
            </div>
            <span class="stat-trend trend-neutral">Pinjam</span>
        </div>
        <div class="stat-value">{{ number_format($totalPeminjaman) }}</div>
        <div class="stat-label">Total peminjaman</div>
    </div>
</div>

<div class="bottom-row">
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <span class="card-title">Grafik Peminjaman</span>
            <span class="card-badge">Per Bulan</span>
        </div>
        @if($chartData->isEmpty())
            <div class="chart-empty"><span>Belum ada data peminjaman</span></div>
        @else
            <div class="chart-wrap" id="chartWrap"></div>
            <div style="display:flex;gap:5px;margin-top:6px;" id="chartLabels"></div>
        @endif
        <div style="margin-top:1.25rem;">
            <div style="font-size:13px;font-weight:600;color:#111827;margin-bottom:0.75rem;">Akses Cepat</div>
            <div class="akses-grid">
                <a href="{{ route('admin.petugas.index') }}" class="akses-item">
                    <div class="akses-icon" style="background:#fffbeb;">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="#d97706"><circle cx="8" cy="5.5" r="2.5"/><path d="M2.5 13.5c0-3 2.5-5 5.5-5s5.5 2 5.5 5H2.5z"/></svg>
                    </div>
                    <span class="akses-label">Kelola Petugas</span>
                </a>
                <a href="{{ route('admin.laporan') }}" class="akses-item">
                    <div class="akses-icon" style="background:#eff6ff;">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="#2563eb"><path d="M3 1h10a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm1 4v1.5h8V5H4zm0 2.5V9h8V7.5H4zm0 2.5v1.5h5V10H4z"/></svg>
                    </div>
                    <span class="akses-label">Lihat Laporan</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card">
        <div style="font-size:14px;font-weight:600;color:#111827;margin-bottom:1.25rem;">Ringkasan Data</div>
        <div class="quick-list">
            <div class="quick-item">
                <div class="quick-dot" style="background:#d97706;"></div>
                <span class="quick-label">Petugas</span>
                <span class="quick-value">{{ $totalPetugas }}</span>
            </div>
            <div class="quick-item">
                <div class="quick-dot" style="background:#2563eb;"></div>
                <span class="quick-label">Buku</span>
                <span class="quick-value">{{ $totalStokBuku }}</span>
            </div>
            <div class="quick-item">
                <div class="quick-dot" style="background:#16a34a;"></div>
                <span class="quick-label">Anggota</span>
                <span class="quick-value">{{ $totalAnggota }}</span>
            </div>
            <div class="quick-item">
                <div class="quick-dot" style="background:#6b7280;"></div>
                <span class="quick-label">Peminjaman</span>
                <span class="quick-value">{{ $totalPeminjaman }}</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const chartData = @json($chartData);
if (chartData.length > 0) {
    const wrap = document.getElementById('chartWrap');
    const labelsEl = document.getElementById('chartLabels');
    const bulanNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
    const max = Math.max(...chartData.map(d => d.total), 1);
    const colors = ['#f59e0b','#fbbf24','#fcd34d','#fde68a'];
    chartData.forEach((d, i) => {
        const pct = Math.round((d.total / max) * 100);
        const color = colors[i % colors.length];
        const namaBulan = bulanNames[d.bulan - 1];
        wrap.innerHTML += `<div class="bar-col"><div class="bar-fill" style="height:${Math.max(pct,3)}%;background:${color};" title="${namaBulan} ${d.tahun}: ${d.total}"></div></div>`;
        labelsEl.innerHTML += `<div style="flex:1;text-align:center;font-size:10px;color:#9ca3af;">${namaBulan}</div>`;
    });
}
</script>
@endpush
@endsection
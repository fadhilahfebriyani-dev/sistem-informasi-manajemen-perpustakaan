@extends('layouts.app')

@push('styles')
<style>
    .page-header { margin-bottom: 1.5rem; }
    .page-header h1 { font-size: 20px; font-weight: 600; color: var(--text-primary); }
    .page-header p { font-size: 13px; color: var(--text-secondary); margin-top: 3px; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 1.25rem;
    }
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius);
        padding: 1.1rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .stat-top { display: flex; align-items: center; justify-content: space-between; }
    .stat-icon {
        width: 36px; height: 36px;
        border-radius: var(--radius-sm);
        display: flex; align-items: center; justify-content: center;
    }
    .stat-trend { font-size: 11px; font-weight: 500; padding: 2px 7px; border-radius: 100px; }
    .trend-up { background: #f0fdf4; color: #16a34a; }
    .trend-red { background: #fef2f2; color: #dc2626; }
    .trend-neutral { background: #f3f4f6; color: #6b7280; }
    .stat-value { font-size: 28px; font-weight: 600; color: var(--text-primary); line-height: 1; }
    .stat-label { font-size: 12px; color: var(--text-secondary); }

    /* CHART ROW */
    .chart-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 1.25rem;
    }

    .card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius);
        padding: 1.25rem;
    }
    .card-header {
        display: flex; align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .card-title { font-size: 14px; font-weight: 600; color: var(--text-primary); }
    .card-badge {
        font-size: 11px; font-weight: 500;
        padding: 2px 8px; border-radius: 100px;
    }
    .badge-blue { background: #eff6ff; color: #2563eb; }
    .badge-amber { background: #fffbeb; color: #d97706; }

    /* BAR CHART */
    .chart-wrap {
        height: 120px;
        display: flex;
        align-items: flex-end;
        gap: 6px;
    }
    .bar-col {
        flex: 1; display: flex; flex-direction: column;
        align-items: center; gap: 4px;
        height: 100%; justify-content: flex-end;
    }
    .bar-fill {
        width: 100%; border-radius: 3px 3px 0 0;
        min-height: 4px; transition: opacity 0.15s;
        cursor: pointer;
    }
    .bar-fill:hover { opacity: 0.75; }
    .bar-month { font-size: 9px; color: var(--text-muted); margin-top: 4px; }
    .chart-empty {
        width: 100%; height: 120px;
        display: flex; align-items: center; justify-content: center;
    }
    .chart-empty span { font-size: 13px; color: var(--text-muted); }

    /* BOTTOM ROW */
    .bottom-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 12px;
    }

    /* QUICK STATS */
    .quick-list { display: flex; flex-direction: column; gap: 0; }
    .quick-item {
        display: flex; align-items: center; gap: 12px;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .quick-item:last-child { border-bottom: none; }
    .quick-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .quick-label { font-size: 13px; color: var(--text-secondary); flex: 1; }
    .quick-value { font-size: 15px; font-weight: 600; color: var(--text-primary); }

    /* ACTIVITY LIST */
    .activity-item {
        display: flex; align-items: center; gap: 10px;
        padding: 0.6rem 0;
        border-bottom: 1px solid #f3f4f6;
        font-size: 12px;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong>. Berikut ringkasan data perpustakaan hari ini.</p>
</div>

{{-- STAT CARDS --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background:#eff6ff;">
                <svg width="18" height="18" viewBox="0 0 16 16" fill="#2563eb">
                    <path d="M2 2h5v12H2V2zm7 0h5v12H9V2z"/>
                </svg>
            </div>
            <span class="stat-trend trend-neutral">Buku</span>
        </div>
        <div class="stat-value">{{ number_format($totalStokBuku) }}</div>
        <div class="stat-label">Total seluruh buku</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background:#f0fdf4;">
                <svg width="18" height="18" viewBox="0 0 16 16" fill="#16a34a">
                    <circle cx="8" cy="5.5" r="2.5"/>
                    <path d="M2.5 13.5c0-3 2.5-5 5.5-5s5.5 2 5.5 5H2.5z"/>
                </svg>
            </div>
            <span class="stat-trend trend-up">Anggota</span>
        </div>
        <div class="stat-value">{{ number_format($totalAnggota) }}</div>
        <div class="stat-label">Anggota terdaftar</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background:#fffbeb;">
                <svg width="18" height="18" viewBox="0 0 16 16" fill="#d97706">
                    <circle cx="6" cy="5" r="2.5"/>
                    <path d="M1 13c0-2.8 2.2-5 5-5s5 2.2 5 5H1z"/>
                    <circle cx="11.5" cy="4.5" r="2"/>
                    <path d="M10 11.5h5c0-2-1.6-3.5-3.5-3.5"/>
                </svg>
            </div>
            <span class="stat-trend trend-neutral">Hari ini</span>
        </div>
        <div class="stat-value">{{ number_format($pengunjungHariIni) }}</div>
        <div class="stat-label">Pengunjung hari ini</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-icon" style="background:#fef2f2;">
                <svg width="18" height="18" viewBox="0 0 16 16" fill="#dc2626">
                    <path d="M2 3h12v1.5H2V3zm0 3h8v1.5H2V6zm0 3h10v1.5H2V9zm0 3h6v1.5H2V12z"/>
                </svg>
            </div>
            @if($peminjamanTerlambat > 0)
                <span class="stat-trend trend-red">{{ $peminjamanTerlambat }} terlambat</span>
            @else
                <span class="stat-trend trend-neutral">Aktif</span>
            @endif
        </div>
        <div class="stat-value">{{ number_format($peminjamanAktif) }}</div>
        <div class="stat-label">Peminjaman aktif</div>
    </div>
</div>

{{-- GRAFIK ROW --}}
<div class="chart-row">

    {{-- Grafik Peminjaman --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Grafik Peminjaman</span>
            <span class="card-badge badge-blue">6 Bulan Terakhir</span>
        </div>
        @if($chartData->isEmpty())
            <div class="chart-empty"><span>Belum ada data peminjaman</span></div>
        @else
            <div class="chart-wrap" id="chartPinjam"></div>
            <div style="display:flex;gap:6px;margin-top:4px;" id="labelPinjam"></div>
        @endif
    </div>

    {{-- Grafik Pengunjung --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Grafik Pengunjung</span>
            <span class="card-badge badge-amber">6 Bulan Terakhir</span>
        </div>
        @if($chartPengunjung->isEmpty())
            <div class="chart-empty"><span>Belum ada data pengunjung</span></div>
        @else
            <div class="chart-wrap" id="chartKunjung"></div>
            <div style="display:flex;gap:6px;margin-top:4px;" id="labelKunjung"></div>
        @endif
    </div>

</div>

{{-- BOTTOM ROW --}}
<div class="bottom-row">

    {{-- Ringkasan Lengkap --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Ringkasan Data</span>
        </div>
        <div class="quick-list">
            <div class="quick-item">
                <div class="quick-dot" style="background:#2563eb;"></div>
                <span class="quick-label">Total Stok Buku</span>
                <span class="quick-value">{{ $totalStokBuku }}</span>
            </div>
            <div class="quick-item">
                <div class="quick-dot" style="background:#16a34a;"></div>
                <span class="quick-label">Anggota Terdaftar</span>
                <span class="quick-value">{{ $totalAnggota }}</span>
            </div>
            <div class="quick-item">
                <div class="quick-dot" style="background:#d97706;"></div>
                <span class="quick-label">Total Pengunjung</span>
                <span class="quick-value">{{ $totalPengunjung }}</span>
            </div>
            <div class="quick-item">
                <div class="quick-dot" style="background:#6366f1;"></div>
                <span class="quick-label">Total Peminjaman</span>
                <span class="quick-value">{{ $totalPeminjaman }}</span>
            </div>
            <div class="quick-item">
                <div class="quick-dot" style="background:#dc2626;"></div>
                <span class="quick-label">Peminjaman Aktif</span>
                <span class="quick-value">{{ $peminjamanAktif }}</span>
            </div>
            <div class="quick-item">
                <div class="quick-dot" style="background:#f59e0b;"></div>
                <span class="quick-label">Terlambat Kembali</span>
                <span class="quick-value" style="{{ $peminjamanTerlambat > 0 ? 'color:#dc2626' : '' }}">
                    {{ $peminjamanTerlambat }}
                </span>
            </div>
            <div class="quick-item">
                <div class="quick-dot" style="background:#0ea5e9;"></div>
                <span class="quick-label">Pengunjung Hari Ini</span>
                <span class="quick-value">{{ $pengunjungHariIni }}</span>
            </div>
        </div>
    </div>

    {{-- Aksi Cepat --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Aksi Cepat</span>
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <a href="{{ route('peminjaman.create') }}"
               style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#eff6ff;border-radius:8px;text-decoration:none;font-size:13px;color:#1d4ed8;font-weight:500;">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zM7.5 4h1v4.5l3 1.75-.5.87-3.5-2V4z"/></svg>
                Catat Peminjaman
            </a>
            <a href="{{ route('pengunjung.create') }}"
               style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#fffbeb;border-radius:8px;text-decoration:none;font-size:13px;color:#92400e;font-weight:500;">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><circle cx="8" cy="5.5" r="2.5"/><path d="M2.5 13.5c0-3 2.5-5 5.5-5s5.5 2 5.5 5H2.5z"/></svg>
                Catat Pengunjung
            </a>
            <a href="{{ route('anggota.create') }}"
               style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f0fdf4;border-radius:8px;text-decoration:none;font-size:13px;color:#166534;font-weight:500;">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a5 5 0 0 0-5 5h10a5 5 0 0 0-5-5z"/></svg>
                Tambah Anggota
            </a>
            <a href="{{ route('bebaspustaka.create') }}"
               style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f5f3ff;border-radius:8px;text-decoration:none;font-size:13px;color:#5b21b6;font-weight:500;">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><path d="M2 2.5A1.5 1.5 0 0 1 3.5 1h8A1.5 1.5 0 0 1 13 2.5v11a.5.5 0 0 1-.777.416L8 11.101l-4.223 2.815A.5.5 0 0 1 3 13.5v-11z"/></svg>
                Bebas Pustaka
            </a>
            <a href="{{ route('buku.create') }}"
               style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f3f4f6;border-radius:8px;text-decoration:none;font-size:13px;color:#374151;font-weight:500;">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor"><path d="M2 2h5v12H2V2zm7 0h5v12H9V2z"/></svg>
                Tambah Buku
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script>
const bulanNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];

function buatGrafik(data, wrapId, labelId, warna) {
    if (!data || data.length === 0) return;
    const wrap   = document.getElementById(wrapId);
    const labels = document.getElementById(labelId);
    if (!wrap || !labels) return;

    const max = Math.max(...data.map(d => d.total), 1);

    data.forEach(d => {
        const pct      = Math.round((d.total / max) * 100);
        const namaBulan = bulanNames[d.bulan - 1];

        wrap.innerHTML += `
            <div class="bar-col">
                <div style="font-size:9px;color:#6b7280;margin-bottom:2px;">${d.total}</div>
                <div class="bar-fill" style="height:${Math.max(pct,3)}%;background:${warna};"
                     title="${namaBulan} ${d.tahun}: ${d.total}"></div>
            </div>`;

        labels.innerHTML += `
            <div style="flex:1;text-align:center;font-size:9px;color:#9ca3af;">
                ${namaBulan}
            </div>`;
    });
}

// Grafik peminjaman — biru
buatGrafik(@json($chartData), 'chartPinjam', 'labelPinjam', '#2563eb');

// Grafik pengunjung — amber
buatGrafik(@json($chartPengunjung), 'chartKunjung', 'labelKunjung', '#d97706');
</script>
@endpush

@endsection
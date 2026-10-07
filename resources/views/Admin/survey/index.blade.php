@extends('layouts.admin')

@push('styles')
<style>
  .card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;}
  .card-head{padding:0.85rem 1.25rem;border-bottom:1px solid #e5e7eb;font-size:13px;font-weight:600;color:#374151;display:flex;align-items:center;justify-content:space-between;gap:8px;}
  .card-body{padding:1.25rem;}

  /* KPI */
  .kpi-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;margin-bottom:1.5rem;}
  .kpi{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:1.1rem 1.25rem;}
  .kpi-label{font-size:11px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;}
  .kpi-val{font-size:28px;font-weight:700;color:#111827;line-height:1;}
  .kpi-sub{font-size:12px;color:#6b7280;margin-top:4px;}
  .kpi-amber .kpi-val{color:#f59e0b;}
  .kpi-green  .kpi-val{color:#16a34a;}
  .kpi-blue   .kpi-val{color:#2563eb;}
  .kpi-red    .kpi-val{color:#dc2626;}

  /* Skor per pertanyaan */
  .q-row{display:flex;align-items:center;gap:12px;padding:0.8rem 0;border-bottom:1px solid #f3f4f6;}
  .q-row:last-child{border-bottom:none;}
  .q-num{font-size:11px;font-weight:700;color:#f59e0b;background:#fffbeb;border-radius:4px;padding:2px 7px;white-space:nowrap;}
  .q-text{flex:1;font-size:13px;color:#374151;}
  .q-bar-wrap{width:150px;flex-shrink:0;}
  .q-bar-bg{height:8px;background:#f3f4f6;border-radius:100px;overflow:hidden;}
  .q-bar-fill{height:100%;border-radius:100px;background:linear-gradient(90deg,#f59e0b,#fcd34d);transition:width 0.5s;}
  .q-score{font-size:14px;font-weight:700;color:#111827;width:34px;text-align:right;flex-shrink:0;}
  .q-label-txt{font-size:11px;width:80px;text-align:right;flex-shrink:0;}

  /* NPS bar */
  .nps-row{display:flex;gap:0;border-radius:8px;overflow:hidden;height:32px;margin:1rem 0;}
  .nps-promoter {background:#16a34a;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:600;}
  .nps-passive  {background:#d97706;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:600;}
  .nps-detractor{background:#dc2626;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:600;}
  .nps-legend{display:flex;gap:1rem;flex-wrap:wrap;margin-top:8px;}
  .nps-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;margin-top:2px;}

  /* Profil responden */
  .resp-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:100px;font-size:12px;font-weight:600;}
  .resp-siswa{background:#fffbeb;color:#d97706;}
  .resp-guru {background:#f0fdf4;color:#16a34a;}
  .resp-umum {background:#eff6ff;color:#2563eb;}

  /* Tren bar */
  .tren-bar-wrap{display:flex;align-items:flex-end;gap:6px;height:80px;}
  .tren-col{flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;}
  .tren-bar{width:100%;background:#fde68a;border-radius:4px 4px 0 0;min-height:4px;transition:background 0.2s;}
  .tren-bar:hover{background:#f59e0b;}
  .tren-val{font-size:10px;color:#6b7280;}
  .tren-bln{font-size:10px;color:#9ca3af;margin-top:2px;}

  /* Saran */
  .saran-item{padding:0.85rem;background:#f9fafb;border:1px solid #f3f4f6;border-radius:8px;margin-bottom:8px;}
  .saran-item:last-child{margin-bottom:0;}
  .saran-meta{font-size:11px;color:#9ca3af;margin-bottom:4px;}
  .saran-text{font-size:13px;color:#374151;line-height:1.5;}

  /* Filter & export */
  .toolbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:10px;}
  .filter-bar{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
  .filter-bar label{font-size:12px;font-weight:600;color:#6b7280;}
  .filter-bar select{border:1px solid #e5e7eb;border-radius:7px;padding:6px 10px;font-size:13px;font-family:inherit;background:#fff;color:#374151;cursor:pointer;outline:none;}
  .filter-bar select:focus{border-color:#f59e0b;}
  .btn-export{background:#f59e0b;color:#fff;border:none;border-radius:7px;padding:7px 16px;font-size:12px;font-weight:600;font-family:inherit;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:background 0.15s;}
  .btn-export:hover{background:#d97706;}

  /* Layout grid */
  .two-col{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;}
  @media(max-width:700px){
    .two-col{grid-template-columns:1fr;}
    .q-bar-wrap,.q-label-txt{display:none;}
  }

  /* Distribusi tabel */
  .dist-table th{padding:0.5rem 0.75rem;font-size:11px;color:#9ca3af;font-weight:600;text-align:left;border-bottom:1px solid #e5e7eb;background:#f9fafb;}
  .dist-table td{padding:0.65rem 0.75rem;font-size:13px;border-bottom:1px solid #f3f4f6;}
  .dist-table tr:last-child td{border-bottom:none;}
  .dist-num{font-size:13px;font-weight:600;color:#111827;text-align:center;}
  .dist-pct{font-size:10px;color:#9ca3af;text-align:center;}
</style>
@endpush

@section('content')

{{-- Toolbar: judul + export --}}
<div class="toolbar">
  <div>
    <h1 style="font-size:20px;font-weight:600;color:#111827;">Rekap Survey Kepuasan</h1>
    <p style="font-size:13px;color:#6b7280;margin-top:2px;">
      ISO 9001:2015 · Klausul 9 — Evaluasi Kinerja &amp; Kepuasan Pelanggan
    </p>
  </div>
  <a href="{{ route('admin.survey.export') }}" class="btn-export">
    <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor">
      <path d="M8 12l-4-4h2.5V3h3v5H12L8 12zM2 14h12v1.5H2V14z"/>
    </svg>
    Export CSV
  </a>
</div>

{{-- Filter Periode --}}
<form method="GET" class="filter-bar" style="margin-bottom:1.25rem;">
  <label>Periode:</label>
  <select name="periode" onchange="this.form.submit()">
    <option value="hari_ini"   {{ $periode==='hari_ini'   ?'selected':'' }}>Hari Ini</option>
    <option value="minggu_ini" {{ $periode==='minggu_ini' ?'selected':'' }}>Minggu Ini</option>
    <option value="bulan_ini"  {{ $periode==='bulan_ini'  ?'selected':'' }}>Bulan Ini</option>
    <option value="tahun_ini"  {{ $periode==='tahun_ini'  ?'selected':'' }}>Tahun Ini</option>
    <option value="semua"      {{ $periode==='semua'      ?'selected':'' }}>Semua Waktu</option>
  </select>
</form>

{{-- KPI Cards --}}
<div class="kpi-grid">
  <div class="kpi kpi-blue">
    <div class="kpi-label">Total Responden</div>
    <div class="kpi-val">{{ $total }}</div>
    <div class="kpi-sub">survei masuk</div>
  </div>
  <div class="kpi {{ $rataTotal>=4?'kpi-green':($rataTotal>=3?'kpi-amber':'kpi-red') }}">
    <div class="kpi-label">Rata-rata Kepuasan</div>
    <div class="kpi-val">
      {{ number_format($rataTotal,2) }}<span style="font-size:15px;font-weight:500;">/5</span>
    </div>
    <div class="kpi-sub">{{ \App\Models\SurveyKepuasan::labelNilai($rataTotal) }}</div>
  </div>
  <div class="kpi {{ $nps>=50?'kpi-green':($nps>=0?'kpi-amber':'kpi-red') }}">
    <div class="kpi-label">NPS Score</div>
    <div class="kpi-val">{{ $nps>0?'+'.$nps:$nps }}</div>
    <div class="kpi-sub">Net Promoter Score</div>
  </div>
  <div class="kpi kpi-green">
    <div class="kpi-label">Promoter</div>
    <div class="kpi-val">{{ $promoters }}</div>
    <div class="kpi-sub">nilai 5 di rekomendasi</div>
  </div>
</div>

{{-- Skor per Aspek + NPS --}}
<div class="two-col">

  {{-- Kiri: skor per pertanyaan --}}
  <div class="card" style="margin-bottom:0;">
    <div class="card-head">
      <span>Skor per Aspek Layanan</span>
      <span style="font-size:11px;color:#9ca3af;">Klausul 9.1.2 &amp; 9.1.3</span>
    </div>
    <div class="card-body" style="padding:0.5rem 1.25rem;">
      @foreach($pertanyaan as $field => $label)
        @php $skor = $rataPerQ[$field] ?? 0; @endphp
        <div class="q-row">
          <div class="q-num">{{ $loop->iteration }}</div>
          <div class="q-text">{{ $label }}</div>
          <div class="q-bar-wrap">
            <div class="q-bar-bg">
              <div class="q-bar-fill" style="width:{{ ($skor/5)*100 }}%"></div>
            </div>
          </div>
          <div class="q-score">{{ number_format($skor,1) }}</div>
          <div class="q-label-txt"
               style="color:{{ $skor>=4?'#16a34a':($skor>=3?'#d97706':'#dc2626') }}">
            {{ \App\Models\SurveyKepuasan::labelNilai($skor) }}
          </div>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Kanan: NPS + Profil Responden --}}
  <div style="display:flex;flex-direction:column;gap:1rem;">

    <div class="card">
      <div class="card-head">Net Promoter Score (rekomendasi)</div>
      <div class="card-body">
        @php
          $pw = $total ? round($promoters/$total*100)  : 0;
          $dw = $total ? round($detractors/$total*100) : 0;
          $aw = max(0, 100-$pw-$dw);
        @endphp
        @if($total > 0)
          <div class="nps-row">
            <div class="nps-promoter"  style="width:{{ $pw }}%">{{ $pw>8?$pw.'%':'' }}</div>
            <div class="nps-passive"   style="width:{{ $aw }}%">{{ $aw>8?$aw.'%':'' }}</div>
            <div class="nps-detractor" style="width:{{ $dw }}%">{{ $dw>8?$dw.'%':'' }}</div>
          </div>
        @else
          <div style="height:32px;background:#f3f4f6;border-radius:8px;margin:1rem 0;"></div>
        @endif
        <div class="nps-legend">
          <div style="display:flex;gap:6px;align-items:flex-start;font-size:12px;">
            <div class="nps-dot" style="background:#16a34a;"></div>
            <div><strong>Promoter</strong> (nilai 5)<br><span style="color:#9ca3af">{{ $promoters }} orang</span></div>
          </div>
          <div style="display:flex;gap:6px;align-items:flex-start;font-size:12px;">
            <div class="nps-dot" style="background:#d97706;"></div>
            <div><strong>Pasif</strong> (nilai 4)<br><span style="color:#9ca3af">{{ $passives }} orang</span></div>
          </div>
          <div style="display:flex;gap:6px;align-items:flex-start;font-size:12px;">
            <div class="nps-dot" style="background:#dc2626;"></div>
            <div><strong>Detractor</strong> (1–3)<br><span style="color:#9ca3af">{{ $detractors }} orang</span></div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-head">Profil Responden</div>
      <div class="card-body">
        @foreach(['siswa'=>['Siswa','resp-siswa'],'guru'=>['Guru / Staff','resp-guru'],'umum'=>['Lainnya','resp-umum']] as $key=>[$lbl,$cls])
          @php $cnt = $jenisResp[$key] ?? 0; $pct = $total ? round($cnt/$total*100) : 0; @endphp
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <span class="resp-badge {{ $cls }}">{{ $lbl }}</span>
            <div style="flex:1;margin:0 12px;">
              <div style="height:6px;background:#f3f4f6;border-radius:100px;overflow:hidden;">
                <div style="height:100%;border-radius:100px;width:{{ $pct }}%;
                  background:{{ $key==='siswa'?'#f59e0b':($key==='guru'?'#16a34a':'#2563eb') }};opacity:0.6;"></div>
              </div>
            </div>
            <span style="font-size:13px;font-weight:600;color:#374151;min-width:20px;text-align:right;">{{ $cnt }}</span>
          </div>
        @endforeach
      </div>
    </div>

  </div>
</div>

{{-- Distribusi Nilai per Pertanyaan --}}
<div class="card" style="margin-bottom:1rem;">
  <div class="card-head">Distribusi Nilai per Pertanyaan</div>
  <div class="card-body" style="padding:0;overflow-x:auto;">
    <table class="dist-table" style="width:100%;border-collapse:collapse;min-width:580px;">
      <thead>
        <tr>
          <th>Pertanyaan</th>
          @foreach(['⭐ (1)','⭐⭐ (2)','⭐⭐⭐ (3)','⭐⭐⭐⭐ (4)','⭐⭐⭐⭐⭐ (5)'] as $h)
            <th style="text-align:center;">{{ $h }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach($pertanyaan as $field => $label)
          <tr>
            <td>{{ $label }}</td>
            @for($i=1;$i<=5;$i++)
              @php $v=$distribusi[$field][$i]??0; $p=$total?round($v/$total*100):0; @endphp
              <td>
                <div class="dist-num">{{ $v }}</div>
                <div class="dist-pct">{{ $p }}%</div>
              </td>
            @endfor
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

{{-- Tren Bulanan + Saran --}}
<div class="two-col">

  <div class="card">
    <div class="card-head">Tren Jumlah Responden (6 Bulan)</div>
    <div class="card-body">
      @if($tren->count() > 0)
        @php $maxTren = $tren->max('total') ?: 1; @endphp
        <div class="tren-bar-wrap">
          @foreach($tren as $t)
            @php $h = round(($t->total/$maxTren)*70); @endphp
            <div class="tren-col">
              <div class="tren-val">{{ $t->total }}</div>
              <div class="tren-bar" style="height:{{ max($h,4) }}px;"
                   title="{{ \Carbon\Carbon::create()->month($t->bulan)->format('M') }}: {{ $t->total }} responden"></div>
              <div class="tren-bln">{{ \Carbon\Carbon::create()->month($t->bulan)->format('M') }}</div>
            </div>
          @endforeach
        </div>
      @else
        <div style="text-align:center;padding:2rem;color:#9ca3af;font-size:13px;">
          Belum ada data tren
        </div>
      @endif
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <span>Saran &amp; Masukan Terbaru</span>
      <span style="font-size:11px;color:#9ca3af;">{{ $sarans->count() }} entri</span>
    </div>
    <div class="card-body" style="max-height:260px;overflow-y:auto;">
      @forelse($sarans as $s)
        <div class="saran-item">
          <div class="saran-meta">
            {{ $s->nama_responden ?? 'Anonim' }}
            @if($s->kelas) · {{ $s->kelas }}@endif
            · {{ $s->created_at->diffForHumans() }}
          </div>
          <div class="saran-text">{{ $s->saran }}</div>
        </div>
      @empty
        <div style="text-align:center;padding:2rem;color:#9ca3af;font-size:13px;">
          Belum ada saran masuk
        </div>
      @endforelse
    </div>
  </div>

</div>

@endsection
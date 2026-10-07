<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIMPERPUS — Perpustakaan SMAN 5 Tebo</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --blue:#1d58ce;--blue-dark:#163fa3;--blue-mid:#2d6de8;
  --blue-light:#eff6ff;--text:#111827;--muted:#6b7280;
  --border:#e5e7eb;--bg:#f5f7fa;--white:#fff;
  --green:#16a34a;--amber:#d97706;--red:#dc2626;--r:10px;
}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);font-size:14px;}
a{text-decoration:none;color:inherit;}

/* NAV */
nav{background:var(--blue);padding:0 1.5rem;height:56px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;}
.nav-brand{display:flex;align-items:center;gap:10px;}
.nav-logo{width:32px;height:32px;background:rgba(255,255,255,0.15);border-radius:8px;display:flex;align-items:center;justify-content:center;}
.nav-logo svg{width:18px;height:18px;}
.nav-brand-text .sub{font-size:10px;color:rgba(255,255,255,0.5);letter-spacing:0.08em;text-transform:uppercase;}
.nav-brand-text .name{font-size:15px;font-weight:600;color:#fff;line-height:1.2;}
.nav-links{display:flex;align-items:center;gap:6px;}
.nav-links a{font-size:13px;color:rgba(255,255,255,0.7);padding:6px 12px;border-radius:6px;transition:all 0.15s;}
.nav-links a:hover{color:#fff;background:rgba(255,255,255,0.1);}
.nav-links .btn-login{background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.2);}
.nav-links .btn-login:hover{background:rgba(255,255,255,0.25);}

/* HERO */
.hero{
  background-image:
    linear-gradient(135deg,rgba(29,88,206,0.55) 0%,rgba(45,109,232,0.85) 60%,rgba(79,156,245,0.45) 100%),
    url('{{ asset("images/bg-hero.jpeg") }}');
  background-size:cover;background-position:center 80%;background-repeat:no-repeat;
  padding:3rem 1.5rem 0;overflow:hidden;position:relative;
}
.hero::after{content:'';position:absolute;bottom:0;left:0;right:0;height:60px;background:var(--bg);border-radius:60px 60px 0 0;}
.hero-inner{max-width:900px;margin:0 auto;text-align:center;position:relative;z-index:1;}
.hero-eyebrow{display:inline-block;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.2);color:rgba(255,255,255,0.9);font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;padding:4px 14px;border-radius:100px;margin-bottom:1rem;}
.hero h1{font-size:clamp(22px,5vw,40px);font-weight:700;color:#fff;line-height:1.25;margin-bottom:0.75rem;}
.hero h1 em{font-style:normal;color:#93c5fd;}
.hero-sub{font-size:15px;color:rgba(255,255,255,0.75);margin-bottom:2rem;max-width:560px;margin-left:auto;margin-right:auto;}
.stats-row{display:flex;justify-content:center;gap:2rem;margin-bottom:2.5rem;flex-wrap:wrap;}
.stat-item{text-align:center;}
.stat-num{font-size:28px;font-weight:700;color:#fff;line-height:1;}
.stat-label{font-size:11px;color:rgba(255,255,255,0.55);margin-top:3px;text-transform:uppercase;letter-spacing:0.05em;}

/* SEARCH */
.search-box{background:#fff;border-radius:14px;padding:1.25rem 1.25rem 1rem;box-shadow:0 8px 32px rgba(0,0,0,0.12);max-width:680px;margin:0 auto 2rem;}
.search-row{display:flex;gap:8px;}
.search-input{flex:1;border:1.5px solid var(--border);border-radius:8px;padding:0.65rem 1rem;font-size:14px;font-family:inherit;color:var(--text);outline:none;transition:border 0.15s;}
.search-input:focus{border-color:var(--blue);}
.search-input::placeholder{color:#b0b7c3;}
.btn-search{background:var(--blue);color:#fff;border:none;border-radius:8px;padding:0.65rem 1.25rem;font-size:13px;font-weight:600;font-family:inherit;cursor:pointer;white-space:nowrap;transition:background 0.15s;}
.btn-search:hover{background:var(--blue-dark);}
.filter-row{display:flex;align-items:center;gap:8px;margin-top:10px;flex-wrap:wrap;}
.filter-row label{font-size:12px;color:var(--muted);font-weight:500;}
.filter-select{border:1px solid var(--border);border-radius:6px;padding:5px 8px;font-size:12px;font-family:inherit;color:var(--text);background:#fff;cursor:pointer;}
.filter-select:focus{outline:none;border-color:var(--blue);}
.tag-reset{font-size:12px;color:var(--red);background:#fef2f2;border:1px solid #fecaca;border-radius:100px;padding:3px 10px;text-decoration:none;}
.tag-reset:hover{background:#fee2e2;}

/* SECTION */
.section{max-width:1100px;margin:0 auto;padding:2rem 1.5rem;}
.section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;}
.section-title{font-size:17px;font-weight:600;color:var(--text);}
.section-count{font-size:13px;color:var(--muted);}

/* GRID BUKU */
.buku-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(168px,1fr));gap:1rem;}
.buku-card{background:#fff;border:1px solid var(--border);border-radius:var(--r);overflow:hidden;transition:box-shadow 0.15s,transform 0.15s;display:flex;flex-direction:column;}
.buku-card:hover{box-shadow:0 6px 24px rgba(0,0,0,0.1);transform:translateY(-3px);}

/* ── Sampul buku ── */
.buku-cover{
  height:200px;               /* lebih tinggi agar sampul terlihat bagus */
  position:relative;
  flex-shrink:0;
  overflow:hidden;
  background:linear-gradient(135deg,#dbeafe,#eff6ff);
}
/* Gambar sampul sungguhan */
.buku-cover-img{
  width:100%;height:100%;object-fit:cover;
  display:block;transition:transform 0.3s;
}
.buku-card:hover .buku-cover-img{transform:scale(1.04);}

/* Placeholder saat tidak ada sampul */
.buku-cover-placeholder{
  width:100%;height:100%;
  display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;
}
.buku-cover-placeholder svg{opacity:0.2;width:48px;height:48px;}
.buku-cover-placeholder .ph-judul{
  font-size:11px;font-weight:600;color:#93c5fd;text-align:center;
  padding:0 10px;line-height:1.35;
  display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;
}

/* Badge kategori */
.buku-kat{
  position:absolute;top:8px;left:8px;
  background:rgba(255,255,255,0.92);color:var(--blue);
  font-size:10px;font-weight:600;padding:2px 7px;
  border-radius:100px;border:1px solid #dbeafe;backdrop-filter:blur(4px);
}

/* Body kartu */
.buku-body{padding:0.75rem;flex:1;display:flex;flex-direction:column;gap:4px;}
.buku-judul{font-size:13px;font-weight:600;color:var(--text);line-height:1.35;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.buku-pengarang{font-size:11px;color:var(--muted);}
.buku-footer{display:flex;align-items:center;justify-content:space-between;margin-top:auto;padding-top:6px;}
.stok-badge{font-size:10px;font-weight:600;padding:2px 8px;border-radius:100px;}
.stok-ada{background:#f0fdf4;color:#16a34a;}
.stok-habis{background:#fef2f2;color:#dc2626;}
.tahun-label{font-size:11px;color:#b0b7c3;}

/* EMPTY */
.empty{text-align:center;padding:3rem 1rem;color:var(--muted);}
.empty svg{opacity:0.25;width:48px;height:48px;margin:0 auto 0.75rem;display:block;}
.empty strong{display:block;font-size:15px;color:#9ca3af;margin-bottom:4px;}
.pagi-wrap{margin-top:1.5rem;display:flex;justify-content:center;}

/* SURVEY CTA */
.survey-cta{background:linear-gradient(135deg,#1e3a8a,var(--blue));border-radius:14px;padding:2rem;display:flex;align-items:center;justify-content:space-between;gap:1.5rem;flex-wrap:wrap;margin:0 1.5rem 2rem;}
.survey-cta-text .label{font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.5);margin-bottom:6px;}
.survey-cta-text h2{font-size:20px;font-weight:700;color:#fff;margin-bottom:6px;line-height:1.3;}
.survey-cta-text p{font-size:13px;color:rgba(255,255,255,0.65);max-width:480px;}
.btn-survey{background:#fff;color:var(--blue);font-size:13px;font-weight:700;padding:0.7rem 1.5rem;border-radius:8px;white-space:nowrap;flex-shrink:0;border:none;cursor:pointer;text-decoration:none;display:inline-block;transition:all 0.15s;}
.btn-survey:hover{background:#eff6ff;transform:translateY(-1px);}

footer{background:#fff;border-top:1px solid var(--border);padding:1.25rem 1.5rem;text-align:center;font-size:12px;color:var(--muted);}
.alert{max-width:900px;margin:1rem auto 0;padding:0.75rem 1rem;border-radius:8px;font-size:13px;}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;}
.alert-info{background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;}

@media(max-width:600px){
  .stats-row{gap:1rem;} .stat-num{font-size:22px;}
  .survey-cta{flex-direction:column;text-align:center;}
  .buku-grid{grid-template-columns:repeat(auto-fill,minmax(145px,1fr));}
  .buku-cover{height:170px;}
}
</style>
</head>
<body>

{{-- NAV --}}
<nav>
  <div class="nav-brand">
    <div class="nav-logo">
      <svg viewBox="0 0 20 20" fill="white">
        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.396 0 2.7.378 3.8 1.042A7.966 7.966 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
      </svg>
    </div>
    <div class="nav-brand-text">
      <div class="sub">SIMPERPUS</div>
      <div class="name">SMAN 5 Tebo</div>
    </div>
  </div>
  <div class="nav-links">
    <a href="#katalog">Katalog Buku</a>
    <a href="{{ route('publik.survey') }}">Survey Kepuasan</a>
    <a href="{{ route('login') }}" class="btn-login">LOGIN</a>
  </div>
</nav>

{{-- ALERT --}}
@if(session('success'))
  <div class="alert alert-success" style="margin:1rem 1.5rem 0;">{{ session('success') }}</div>
@endif
@if(session('info'))
  <div class="alert alert-info" style="margin:1rem 1.5rem 0;">{{ session('info') }}</div>
@endif

{{-- HERO --}}
<div class="hero">
  <div class="hero-inner">
    <div class="hero-eyebrow">Perpustakaan Digital SMAN 5 Tebo</div>
    <h1>Temukan Buku yang<br><em>Kamu Butuhkan</em></h1>
    <p class="hero-sub">Cari koleksi buku perpustakaan secara mandiri tanpa perlu ke meja petugas.</p>
    <div class="stats-row">
      <div class="stat-item">
        <div class="stat-num">{{ number_format($stats['total_judul']) }}</div>
        <div class="stat-label">Judul Buku</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">{{ number_format($stats['total_koleksi']) }}</div>
        <div class="stat-label">Total Eksemplar</div>
      </div>
    </div>

    <div class="search-box" id="katalog">
      <form method="GET" action="{{ route('publik.index') }}">
        <div class="search-row">
          <input class="search-input" type="text" name="q"
                 value="{{ request('q') }}"
                 placeholder="Cari judul, pengarang, atau penerbit...">
          <button class="btn-search" type="submit">Cari</button>
        </div>
        <div class="filter-row">
          <label>Kategori:</label>
          <select class="filter-select" name="kategori_id" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $kat)
              <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                {{ $kat->nama }}
              </option>
            @endforeach
          </select>
          @if(request('q') || request('kategori_id'))
            <a href="{{ route('publik.index') }}" class="tag-reset">✕ Reset</a>
          @endif
        </div>
      </form>
    </div>
  </div>
</div>

{{-- KATALOG --}}
<div class="section">
  <div class="section-header">
    <span class="section-title">
      @if(request('q'))
        Hasil untuk "<strong>{{ request('q') }}</strong>"
      @else
        Koleksi Buku Tersedia
      @endif
    </span>
    <span class="section-count">{{ $bukus->total() }} buku ditemukan</span>
  </div>

  @forelse($bukus as $buku)
    @if($loop->first)<div class="buku-grid">@endif

    <div class="buku-card">
      <div class="buku-cover">

        {{-- ▼ Tampilkan sampul jika ada, placeholder jika tidak ▼ --}}
        @if($buku->sampul_url)
          <img class="buku-cover-img"
               src="{{ $buku->sampul_url }}"
               alt="Sampul {{ $buku->judul }}"
               loading="lazy">
        @else
          <div class="buku-cover-placeholder">
            <svg viewBox="0 0 24 24" fill="#93c5fd">
              <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span class="ph-judul">{{ $buku->judul }}</span>
          </div>
        @endif

        @if($buku->kategori)
          <span class="buku-kat">{{ $buku->kategori->nama }}</span>
        @endif
      </div>

      <div class="buku-body">
        <div class="buku-judul">{{ $buku->judul }}</div>
        <div class="buku-pengarang">{{ $buku->pengarang ?? '-' }}</div>
        <div class="buku-footer">
          <span class="stok-badge {{ $buku->stok > 0 ? 'stok-ada' : 'stok-habis' }}">
            {{ $buku->stok > 0 ? $buku->stok.' tersedia' : 'Habis' }}
          </span>
          @if($buku->tahun_terbit)
            <span class="tahun-label">{{ $buku->tahun_terbit }}</span>
          @endif
        </div>
      </div>
    </div>

    @if($loop->last)</div>@endif
  @empty
    <div class="empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
      </svg>
      <strong>Buku tidak ditemukan</strong>
      Coba kata kunci lain atau pilih kategori yang berbeda
    </div>
  @endforelse

  <div class="pagi-wrap">{{ $bukus->links() }}</div>
</div>

{{-- SURVEY CTA --}}
<div style="max-width:1100px;margin:0 auto;">
  <div class="survey-cta">
    <div class="survey-cta-text">
      <div class="label">Survey Kepuasan</div>
      <h2>Bantu Kami Meningkatkan Pelayanan</h2>
      <p>Luangkan 2 menit untuk memberi penilaian</p>
    </div>
    <a href="{{ route('publik.survey') }}" class="btn-survey">Isi Survey Sekarang →</a>
  </div>
</div>

<footer>
  © {{ date('Y') }} Perpustakaan SMAN 5 Tebo &nbsp;·&nbsp; Sistem Informasi Manajemen Perpustakaan (SIMPERPUS)
</footer>
</body>
</html>
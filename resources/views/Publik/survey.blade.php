<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Survey Kepuasan — Perpustakaan SMAN 5 Tebo</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--blue:#1d58ce;--blue-dark:#163fa3;--text:#111827;--muted:#6b7280;--border:#e5e7eb;--bg:#f5f7fa;--white:#fff;--green:#16a34a;--red:#dc2626;--r:10px;}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);font-size:14px;min-height:100vh;}
a{text-decoration:none;color:inherit;}

nav{background:var(--blue);padding:0 1.5rem;height:56px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;}
.nav-brand{display:flex;align-items:center;gap:10px;}
.nav-logo{width:32px;height:32px;background:rgba(255,255,255,0.15);border-radius:8px;display:flex;align-items:center;justify-content:center;}
.nav-logo svg{width:18px;height:18px;}
.nav-brand-text .sub{font-size:10px;color:rgba(255,255,255,0.5);letter-spacing:0.08em;text-transform:uppercase;}
.nav-brand-text .name{font-size:15px;font-weight:600;color:#fff;line-height:1.2;}
.btn-back{font-size:13px;color:rgba(255,255,255,0.75);background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:6px;padding:6px 12px;cursor:pointer;transition:all 0.15s;}
.btn-back:hover{color:#fff;background:rgba(255,255,255,0.2);}

.page-wrap{max-width:680px;margin:2rem auto;padding:0 1.25rem 3rem;}

/* Header survey */
.survey-head{text-align:center;margin-bottom:2rem;}
.iso-badge{display:inline-block;background:var(--blue);color:#fff;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;padding:4px 14px;border-radius:100px;margin-bottom:1rem;}
.survey-head h1{font-size:24px;font-weight:700;color:var(--text);margin-bottom:8px;}
.survey-head p{font-size:14px;color:var(--muted);max-width:480px;margin:0 auto;line-height:1.6;}

/* Form card */
.form-card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,0.05);}
.form-section{padding:1.5rem 1.75rem;border-bottom:1px solid var(--border);}
.form-section:last-of-type{border-bottom:none;}
.form-section-title{font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--blue);margin-bottom:1.25rem;display:flex;align-items:center;gap:8px;}
.form-section-title::after{content:'';flex:1;height:1px;background:#dbeafe;}

/* Identitas */
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;}
.field{display:flex;flex-direction:column;gap:5px;}
.field label{font-size:12px;font-weight:600;color:var(--muted);}
.field input,.field select{border:1.5px solid var(--border);border-radius:7px;padding:0.55rem 0.75rem;font-size:13px;font-family:inherit;color:var(--text);outline:none;transition:border 0.15s;background:#fff;}
.field input:focus,.field select:focus{border-color:var(--blue);}
.optional{font-size:10px;color:#b0b7c3;font-weight:400;margin-left:4px;}

/* Rating bintang */
.q-item{margin-bottom:1.5rem;}
.q-item:last-child{margin-bottom:0;}
.q-label{font-size:13px;font-weight:600;color:var(--text);margin-bottom:10px;line-height:1.4;}
.q-klausul{font-size:10px;color:#93c5fd;font-weight:600;letter-spacing:0.05em;margin-bottom:4px;}
.star-group{display:flex;align-items:center;gap:4px;flex-wrap:wrap;}
.star-group input[type=radio]{display:none;}
.star-label{
  display:flex;flex-direction:column;align-items:center;gap:2px;
  cursor:pointer;padding:5px 7px;border-radius:8px;border:1.5px solid var(--border);
  transition:all 0.12s;min-width:40px;text-align:center;
  background:#fff;
}
.star-label:hover{border-color:#93c5fd;background:#eff6ff;}
.star-label .star-icon{font-size:14px;line-height:1;}
.star-label .star-val{font-size:9px;font-weight:700;color:var(--muted);}
.star-label .star-txt{font-size:8px;color:#b0b7c3;line-height:1.2;max-width:48px;}
.star-group input:checked + .star-label{border-color:var(--blue);background:#eff6ff;}
.star-group input:checked + .star-label .star-val{color:var(--blue);}

/* Error */
.err{font-size:11px;color:var(--red);margin-top:4px;}
.field-err input,.field-err select,.field-err .star-label{border-color:#fca5a5!important;}

/* Saran */
.saran-wrap textarea{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:0.75rem;font-size:13px;font-family:inherit;color:var(--text);resize:vertical;min-height:90px;outline:none;transition:border 0.15s;}
.saran-wrap textarea:focus{border-color:var(--blue);}
.char-count{font-size:11px;color:#b0b7c3;text-align:right;margin-top:4px;}

/* Submit */
.form-footer{padding:1.25rem 1.75rem;background:#f9fafb;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;}
.form-footer p{font-size:12px;color:var(--muted);}
.btn-submit{background:var(--blue);color:#fff;border:none;border-radius:8px;padding:0.7rem 2rem;font-size:14px;font-weight:700;font-family:inherit;cursor:pointer;transition:background 0.15s;}
.btn-submit:hover{background:var(--blue-dark);}

/* Progress bar */
.progress-bar{height:4px;background:#dbeafe;border-radius:0;overflow:hidden;margin-bottom:0;}
.progress-fill{height:100%;background:linear-gradient(90deg,var(--blue),#60a5fa);width:0%;transition:width 0.3s;}

footer{background:#fff;border-top:1px solid var(--border);padding:1rem 1.5rem;text-align:center;font-size:12px;color:var(--muted);}
</style>
</head>
<body>

<nav>
  <div class="nav-brand">
    <div class="nav-logo">
      <svg viewBox="0 0 20 20" fill="white"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.396 0 2.7.378 3.8 1.042A7.966 7.966 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
    </div>
    <div class="nav-brand-text">
      <div class="sub">SIMPERPUS</div>
      <div class="name">SMAN 5 Tebo</div>
    </div>
  </div>
  <a href="{{ route('publik.index') }}" class="btn-back">← Kembali</a>
</nav>

<div class="page-wrap">
  <div class="survey-head">
    <div class="iso-badge">ISO 9001:2015 · Klausul 9 — Evaluasi Kinerja</div>
    <h1>Kuesioner Evaluasi Layanan Perpustakaan dengan SIMPERPUS</h1>
    <p>Penilaian Anda membantu kami meningkatkan kualitas layanan. Semua jawaban bersifat anonim dan hanya digunakan untuk evaluasi internal.</p>
  </div>

  @if($errors->any())
  <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:0.75rem 1rem;margin-bottom:1rem;font-size:13px;color:var(--red);">
    <strong>Mohon periksa kembali jawaban Anda:</strong>
    <ul style="margin-top:4px;padding-left:1.25rem;">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
  @endif

  <div class="form-card">
    <div class="progress-bar"><div class="progress-fill" id="progressFill"></div></div>

    <form action="{{ route('publik.survey.simpan') }}" method="POST" id="surveyForm">
      @csrf

      {{-- IDENTITAS --}}
      <div class="form-section">
        <div class="form-section-title">Identitas Responden</div>
        <div class="field-row">
          <div class="field">
            <label>Nama <span class="optional">(opsional)</span></label>
            <input type="text" name="nama_responden" value="{{ old('nama_responden') }}" placeholder="Nama kamu...">
          </div>
          <div class="field">
            <label>Kelas <span class="optional">(opsional)</span></label>
            <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="cth: X IPA 1">
          </div>
        </div>
        <div class="field">
          <label>Status <span style="color:var(--red)">*</span></label>
          <select name="jenis_responden" required>
            <option value="siswa"   {{ old('jenis_responden','siswa') === 'siswa'    ? 'selected':'' }}>Siswa</option>
            <option value="guru"    {{ old('jenis_responden') === 'guru'              ? 'selected':'' }}>Guru</option>
            <option value="lainnya" {{ old('jenis_responden') === 'lainnya'           ? 'selected':'' }}>Lainnya</option>
          </select>
          @error('jenis_responden')<div class="err">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- PENILAIAN LAYANAN (klausul 9.1.2) --}}
      <div class="form-section">
        <div class="form-section-title">Penilaian Layanan · 9.1.2</div>

        @php
          $ratingDef = [
            1=>['⭐','Sangat<br>Tidak Puas'],
            2=>['⭐⭐','Tidak<br>Puas'],
            3=>['⭐⭐⭐','Cukup'],
            4=>['⭐⭐⭐⭐','Puas'],
            5=>['⭐⭐⭐⭐⭐','Sangat<br>Puas'],
          ];
          $qs = [
            'q_kecepatan_layanan'    => ['Bagaimana penilaian Anda mengenai tingkat kecepatan dan efisiensi pelayanan petugas setelah perpustakaan menerapkan sistem digital SIMPERPUS?', '9.1.2 — Kecepatan Layanan'],
            'q_kemudahan_akses'      => ['Apakah kehadiran SIMPERPUS memberikan Anda kemudahan yang signifikan dalam melakukan pencarian (OPAC) dan proses peminjaman buku?',            '9.1.2 — Kemudahan Akses & Sistem'],
            'q_akurasi_ketersediaan' => ['Bagaimana penilaian Anda terhadap keakuratan informasi ketersediaan koleksi buku yang ditampilkan di dalam sistem SIMPERPUS?',                 '9.1.2 — Akurasi & Ketersediaan'],
          ];
        @endphp

        @foreach($qs as $name => [$label, $klausul])
        <div class="q-item">
          <div class="q-klausul">{{ $klausul }}</div>
          <div class="q-label">{{ $label }} <span style="color:var(--red)">*</span></div>
          <div class="star-group">
            @foreach($ratingDef as $val => [$stars, $txt])
            <input type="radio" name="{{ $name }}" id="{{ $name }}_{{ $val }}"
                   value="{{ $val }}" {{ old($name) == $val ? 'checked' : '' }} required>
            <label for="{{ $name }}_{{ $val }}" class="star-label">
              <span class="star-icon">{{ $stars }}</span>
              <span class="star-val">{{ $val }}</span>
              <span class="star-txt">{!! $txt !!}</span>
            </label>
            @endforeach
          </div>
          @error($name)<div class="err">{{ $message }}</div>@enderror
        </div>
        @endforeach
      </div>

      {{-- ANALISIS & EVALUASI (klausul 9.1.3) --}}
      <div class="form-section">
        <div class="form-section-title">Analisis & Evaluasi · 9.1.3</div>
        @php
          $qs2 = [
            'q_efektivitas_belajar' => ['Seberapa besar manfaat dan efektivitas yang Anda rasakan dari fitur-fitur SIMPERPUS dalam mendukung kelancaran kegiatan belajar Anda?',    '9.1.3 — Efektivitas Belajar'],
            'q_rekomendasi'         => ['Seberapa besar kemungkinan Anda akan merekomendasikan platform dan layanan SIMPERPUS ini kepada rekan atau pengguna lain?',                 '9.1.3 — Kepuasan Keseluruhan'],
          ];
        @endphp
        @foreach($qs2 as $name => [$label, $klausul])
        <div class="q-item">
          <div class="q-klausul">{{ $klausul }}</div>
          <div class="q-label">{{ $label }} <span style="color:var(--red)">*</span></div>
          <div class="star-group">
            @foreach($ratingDef as $val => [$stars, $txt])
            <input type="radio" name="{{ $name }}" id="{{ $name }}_{{ $val }}"
                   value="{{ $val }}" {{ old($name) == $val ? 'checked' : '' }} required>
            <label for="{{ $name }}_{{ $val }}" class="star-label">
              <span class="star-icon">{{ $stars }}</span>
              <span class="star-val">{{ $val }}</span>
              <span class="star-txt">{!! $txt !!}</span>
            </label>
            @endforeach
          </div>
          @error($name)<div class="err">{{ $message }}</div>@enderror
        </div>
        @endforeach
      </div>

      {{-- SARAN --}}
      <div class="form-section">
        <div class="form-section-title">Saran & Masukan</div>
        <div class="saran-wrap">
          <textarea name="saran" id="saran" maxlength="1000"
                    placeholder="Ada saran untuk perpustakaan kami? (opsional)">{{ old('saran') }}</textarea>
          <div class="char-count"><span id="charCount">0</span>/1000</div>
        </div>
      </div>

      <div class="form-footer">
        <p>Data Anda bersifat anonim &amp; hanya digunakan untuk evaluasi mutu layanan.</p>
        <button type="submit" class="btn-submit">Kirim Penilaian →</button>
      </div>
    </form>
  </div>
</div>

<footer>
  © {{ date('Y') }} Perpustakaan SMAN 5 Tebo &nbsp;·&nbsp; Survey Kepuasan · ISO 9001:2015 Klausul 9
</footer>

<script>
// Progress bar saat mengisi
const radios = document.querySelectorAll('input[type=radio][required]');
const qNames = [...new Set([...radios].map(r => r.name))];
const fill   = document.getElementById('progressFill');

function updateProgress() {
  const answered = qNames.filter(n => document.querySelector(`input[name="${n}"]:checked`)).length;
  fill.style.width = (answered / qNames.length * 100) + '%';
}
radios.forEach(r => r.addEventListener('change', updateProgress));

// Char count saran
const saran = document.getElementById('saran');
const cnt   = document.getElementById('charCount');
saran.addEventListener('input', () => { cnt.textContent = saran.value.length; });
</script>
</body>
</html>
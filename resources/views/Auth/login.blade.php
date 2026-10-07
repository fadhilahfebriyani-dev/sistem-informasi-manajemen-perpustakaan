<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIMPERPUS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background-image: url('{{ asset("images/bg-login.jpeg") }}');
            background-size: cover;
            background-position: center 45%;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 0;
        }

        /* ── Tombol Kembali ke Halaman Publik ── */
        .btn-back-public {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 10;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.88);
            color: #2563eb;
            border: 1.5px solid rgba(37, 99, 235, 0.2);
            border-radius: 50px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-decoration: none;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.18);
            transition: background 0.2s, color 0.2s, transform 0.2s, box-shadow 0.2s;
        }

        .btn-back-public svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
            transition: transform 0.2s;
        }

        .btn-back-public:hover {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
            transform: translateX(-3px);
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.35);
        }

        .btn-back-public:hover svg {
            transform: translateX(-2px);
        }

        /* ── Card ── */
        .login-wrap {
            position: relative;
            z-index: 1;
            display: flex;
            width: 100%;
            max-width: 630px;
            min-height: 300px;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.2);
        }

        /* Panel kiri */
        .login-left {
            flex: 1;
            background: #0079f3;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.74);
            margin-bottom: 4px;
        }

        .brand-name { font-size: 22px; font-weight: 600; color: #fff; }

        .left-footer { font-size: 12px; color: rgba(255,255,255,0.5); }

        .left-decor {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .decor-box {
            width: 160px;
            height: 160px;
            border-radius: 20px;
            background: hsla(221,83%,53%,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            object-fit: contain;
        }

        /* Panel kanan */
        .login-right {
            width: 300px;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title { font-size: 20px; font-weight: 600; color: #111827; margin-bottom: 4px; }
        .login-sub   { font-size: 13px; color: #6b7280; margin-bottom: 2rem; }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 13px;
            color: #dc2626;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .alert-error svg { flex-shrink: 0; margin-top: 1px; }

        .form-group { margin-bottom: 1rem; }

        label { display: block; font-size: 12px; font-weight: 500; color: #374151; margin-bottom: 6px; }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.65rem 0.875rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: #111827;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: #fff;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        .btn-login {
            width: 100%;
            padding: 0.7rem;
            background: #2563eb;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.15s, transform 0.1s;
            margin-top: 0.5rem;
        }

        .btn-login:hover  { background: #1d4ed8; }
        .btn-login:active { transform: scale(0.99); }

        .role-hint {
            margin-top: 1.25rem;
            padding: 0.75rem;
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            font-size: 11.5px;
            color: #0369a1;
            line-height: 1.6;
        }

        .role-hint strong { font-weight: 600; }

        /* Responsive */
        @media (max-width: 580px) {
            .login-left { display: none; }
            .login-right { width: 100%; }
            .btn-back-public { top: 12px; left: 12px; font-size: 12px; padding: 6px 13px; }
        }
    </style>
</head>
<body>

    {{-- ── Tombol Kembali ke Halaman Publik ── --}}
    <a href="{{ route('publik.index') }}" class="btn-back-public">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali 
    </a>

    <div class="login-wrap">

        {{-- KIRI --}}
        <div class="login-left">
            <div>
                <div class="brand-label">Perpustakaan Digital</div>
                <div class="brand-name">SIMPERPUS</div>
            </div>
            <div class="left-decor">
                <img src="{{ asset('images/logo sma.png') }}" alt="Logo SMAN 5 Tebo" class="decor-box">
            </div>
            <div class="left-footer">Sistem Informasi Manajemen Perpustakaan</div>
        </div>

        {{-- KANAN --}}
        <div class="login-right">
            <div class="login-title">Selamat Datang</div>
            <div class="login-sub">Masuk menggunakan akun Anda</div>

            @if ($errors->any())
                <div class="alert-error">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                        <circle cx="8" cy="8" r="7" stroke="#dc2626" stroke-width="1.5"/>
                        <path d="M8 4.5v4M8 10.5v1" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="email@simperpus.com"
                           required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-login">Masuk</button>
            </form>

            <div class="role-hint">
                <strong>Admin</strong> → diarahkan ke Panel Kepala Perpustakaan<br>
                <strong>Petugas</strong> → diarahkan ke Panel Petugas Perpustakaan
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Coba Lagi'
        });
        @endif

        @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: '{{ session("success") }}',
            timer: 2000,
            showConfirmButton: false
        });
        @endif

        @if (session('error'))
        Swal.fire({
            icon: 'warning',
            title: 'Akses Ditolak',
            text: '{{ session("error") }}',
            confirmButtonColor: '#2563eb',
        });
        @endif

    });
    </script>
</body>
</html>
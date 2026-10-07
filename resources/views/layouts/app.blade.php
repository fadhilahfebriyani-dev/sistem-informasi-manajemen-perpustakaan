<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPERPUS - {{ $title ?? 'Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sidebar-bg: #1d58ce;
            --sidebar-border: rgba(255,255,255,0.07);
            --sidebar-text: rgba(255,255,255,0.5);
            --sidebar-text-active: #ffffff;
            --sidebar-active-bg: rgba(45,156,219,0.12);
            --sidebar-active-border: #2d9cdb;
            --main-bg: #f3f4f6;
            --card-bg: #ffffff;
            --card-border: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --blue: #2563eb;
            --blue-light: #eff6ff;
            --blue-dark: #1d4ed8;
            --green: #16a34a;
            --green-light: #f0fdf4;
            --amber: #d97706;
            --amber-light: #fffbeb;
            --red: #dc2626;
            --red-light: #fef2f2;
            --radius: 10px;
            --radius-sm: 6px;
        }
        html, body { height: 100%; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { display: flex; background: var(--main-bg); color: var(--text-primary); font-size: 14px; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: 230px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid var(--sidebar-border);
        }
        .sidebar-brand .label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            margin-bottom: 3px;
        }
        .sidebar-brand .name {
            font-size: 17px;
            font-weight: 600;
            color: #fff;
        }
        .sidebar-user {
            padding: 0.85rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--sidebar-border);
        }
        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #0aa1ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 500; color: #fff; }
        .user-role { font-size: 11px; color: rgba(255,255,255,0.35); }

        .nav { flex: 1; padding: 0.625rem 0; overflow-y: auto; }
        .nav-section {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 0.75rem 1.25rem 0.25rem;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.6rem 1.25rem;
            font-size: 13px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-left: 2px solid transparent;
            transition: all 0.15s;
        }
        .nav-item:hover {
            color: var(--sidebar-text-active);
            background: rgba(255,255,255,0.04);
        }
        .nav-item.active {
            color: var(--sidebar-text-active);
            background: var(--sidebar-active-bg);
            border-left-color: var(--sidebar-active-border);
        }
        .nav-item svg { flex-shrink: 0; opacity: 0.6; }
        .nav-item.active svg, .nav-item:hover svg { opacity: 1; }

        /* Badge notif di nav-item */
        .nav-badge {
            margin-left: auto;
            background: #dc2626;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 100px;
            line-height: 1.6;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--sidebar-border);
        }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: rgba(255,255,255,0.35);
            text-decoration: none;
            transition: color 0.15s;
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .logout-btn:hover { color: #f87171; }

        /* MAIN */
        .main-wrapper {
            margin-left: 230px;
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--card-border);
            padding: 0 1.5rem;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-left { font-size: 13px; font-weight: 500; color: var(--text-secondary); }
        .topbar-right { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); }
        .topbar-dot { width: 7px; height: 7px; border-radius: 50%; background: #22c55e; }

        .page-content { flex: 1; padding: 1.5rem; }
        .page-header { margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 20px; font-weight: 600; color: var(--text-primary); }
        .page-header p { font-size: 13px; color: var(--text-secondary); margin-top: 3px; }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="label">SIMPERPUS</div>
        <div class="name">SMAN 5 TEBO</div>
    </div>
    <div class="sidebar-user">
        <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        <div>
            <div class="user-name">{{ Auth::user()->name }}</div>
            <div class="user-role">Petugas</div>
        </div>
    </div>
    <nav class="nav">
        <div class="nav-section">Menu</div>

        <a href="{{ url('/') }}" class="nav-item {{ request()->is('/') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <rect x="1" y="1" width="6" height="6" rx="1.5"/>
                <rect x="9" y="1" width="6" height="6" rx="1.5"/>
                <rect x="1" y="9" width="6" height="6" rx="1.5"/>
                <rect x="9" y="9" width="6" height="6" rx="1.5"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('buku.index') }}" class="nav-item {{ request()->is('buku*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h11A1.5 1.5 0 0 1 15 2.5v11a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 1 13.5v-11zM2.5 2a.5.5 0 0 0-.5.5v11a.5.5 0 0 0 .5.5H4V2H2.5zM5 2v12h8.5a.5.5 0 0 0 .5-.5v-11a.5.5 0 0 0-.5-.5H5zM7 5h4v1H7zm0 2h4v1H7zm0 2h2v1H7z"/>
            </svg>
            Buku
        </a>

        <a href="{{ route('kategori.index') }}" class="nav-item {{ request()->is('kategori*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M1 3h6v2H1V3zm0 4h6v2H1V7zm0 4h6v2H1v-2zm8-8h6v2H9V3zm0 4h6v2H9V7zm0 4h6v2H9v-2z"/>
            </svg>
            Kategori
        </a>

        <a href="{{ route('anggota.index') }}" class="nav-item {{ request()->is('anggota*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <circle cx="8" cy="5.5" r="2.5"/>
                <path d="M2.5 13.5c0-3 2.5-5 5.5-5s5.5 2 5.5 5H2.5z"/>
            </svg>
            Anggota
        </a>

        <a href="{{ route('pengunjung.index') }}" class="nav-item {{ request()->is('pengunjung*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <circle cx="6" cy="5" r="2.5"/>
                <path d="M1 13c0-2.8 2.2-5 5-5s5 2.2 5 5H1z"/>
                <circle cx="11.5" cy="4.5" r="2"/>
                <path d="M10 11.5h5c0-2-1.6-3.5-3.5-3.5"/>
            </svg>
            Pengunjung
        </a>

        <a href="{{ route('peminjaman.index') }}" class="nav-item {{ request()->is('peminjaman*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M2 3h12v1.5H2V3zm0 3h8v1.5H2V6zm0 3h10v1.5H2V9zm0 3h6v1.5H2V12z"/>
            </svg>
            Peminjaman
        </a>

        {{-- ── MENU DENDA (BARU) ── --}}
        <a href="{{ route('denda.index') }}" class="nav-item {{ request()->is('denda*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 1.5a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11zM7.25 4v4.25l3.5 2.1.65-1.08-3-1.79V4H7.25z"/>
            </svg>
            Denda
            @php
                $dendaBelumBayar = \App\Models\Denda::where('status', 'belum_bayar')->count();
            @endphp
            @if($dendaBelumBayar > 0)
                <span class="nav-badge">{{ $dendaBelumBayar }}</span>
            @endif
        </a>

        <a href="{{ route('bebaspustaka.index') }}" class="nav-item {{ request()->is('bebaspustaka*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M2 2.5A1.5 1.5 0 0 1 3.5 1h8A1.5 1.5 0 0 1 13 2.5v11a.5.5 0 0 1-.777.416L8 11.101l-4.223 2.815A.5.5 0 0 1 3 13.5v-11zm1.5-.5a.5.5 0 0 0-.5.5v10.3l3.723-2.482a.5.5 0 0 1 .554 0L11 12.8V2.5a.5.5 0 0 0-.5-.5h-7z"/>
                <circle cx="12" cy="12" r="4" fill="#98961d"/>
                <path d="M10.5 12l1 1 2-2" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>
            Bebas Pustaka
        </a>

        <div class="nav-section" style="margin-top:0.5rem;">Laporan</div>
        <a href="{{ url('/laporan') }}" class="nav-item {{ request()->is('laporan*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M3 1h10a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm1 4v1.5h8V5H4zm0 2.5V9h8V7.5H4zm0 2.5v1.5h5V10H4z"/>
            </svg>
            Laporan
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg width="15" height="15" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M6 2H2v12h4v-1.5H3.5v-9H6V2zm4.5 3L14 8l-3.5 3V9H6V7h4.5V5z"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

{{-- MAIN --}}
<div class="main-wrapper">
    <header class="topbar">
        <span class="topbar-left">Sistem Informasi Manajemen Perpustakaan</span>
        <div class="topbar-right">
            <div class="topbar-dot"></div>
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </header>
    <main class="page-content">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
</body>
</html>
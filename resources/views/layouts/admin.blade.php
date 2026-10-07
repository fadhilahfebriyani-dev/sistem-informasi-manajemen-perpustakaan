<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPERPUS Admin - {{ $title ?? 'Dashboard' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --sidebar-bg: #111827;
            --sidebar-border: rgba(255,255,255,0.07);
            --sidebar-text: rgba(255,255,255,0.45);
            --sidebar-text-active: #ffffff;
            --sidebar-active-bg: rgba(255,255,255,0.07);
            --sidebar-active-border: #f59e0b;
            --main-bg: #f3f4f6;
            --card-bg: #ffffff;
            --card-border: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --radius: 10px;
            --radius-sm: 6px;
        }
        html, body { height: 100%; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { display: flex; background: var(--main-bg); color: var(--text-primary); font-size: 14px; }

        /* SIDEBAR */
        .sidebar {
            width: 230px; min-height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column; flex-shrink: 0;
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 100;
        }
        .sidebar-brand {
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid var(--sidebar-border);
        }
        .sidebar-brand .label {
            font-size: 10px; font-weight: 600; letter-spacing: 0.1em;
            text-transform: uppercase; color: rgba(255,255,255,0.25); margin-bottom: 3px;
        }
        .sidebar-brand .name { font-size: 17px; font-weight: 600; color: #fff; }
        .admin-badge {
            display: inline-flex; align-items: center; gap: 4px;
            background: rgba(245,158,11,0.15); color: #f59e0b;
            font-size: 10px; font-weight: 600;
            padding: 2px 8px; border-radius: 100px; margin-top: 6px;
        }
        .sidebar-user {
            padding: 0.85rem 1.25rem; display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid var(--sidebar-border);
        }
        .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: #f59e0b; display: flex; align-items: center;
            justify-content: center; font-size: 13px; font-weight: 600; color: #fff; flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 500; color: #fff; }
        .user-role { font-size: 11px; color: #f59e0b; font-weight: 500; }

        /* NAV — flex:1 agar mengisi ruang dan footer tetap di bawah */
        .nav { flex: 1; padding: 0.625rem 0; overflow-y: auto; }
        .nav-section {
            font-size: 10px; font-weight: 600; letter-spacing: 0.08em;
            text-transform: uppercase; color: rgba(255,255,255,0.2);
            padding: 0.75rem 1.25rem 0.25rem;
        }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 0.6rem 1.25rem; font-size: 13px; color: var(--sidebar-text);
            text-decoration: none; border-left: 2px solid transparent; transition: all 0.15s;
        }
        .nav-item:hover { color: var(--sidebar-text-active); background: rgba(255,255,255,0.04); }
        .nav-item.active {
            color: var(--sidebar-text-active);
            background: var(--sidebar-active-bg);
            border-left-color: var(--sidebar-active-border);
        }
        .nav-item svg { flex-shrink: 0; opacity: 0.6; }
        .nav-item.active svg, .nav-item:hover svg { opacity: 1; }
        .nav-badge {
            margin-left: auto; background: #16a34a; color: #fff;
            font-size: 10px; font-weight: 600; padding: 1px 6px;
            border-radius: 100px; line-height: 1.6;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--sidebar-border);
        }
        .logout-btn {
            display: flex; align-items: center; gap: 10px;
            font-size: 13px; color: rgba(255,255,255,0.3);
            background: none; border: none; cursor: pointer;
            width: 100%; text-decoration: none; transition: color 0.15s;
        }
        .logout-btn:hover { color: #f87171; }

        /* MAIN */
        .main-wrapper { margin-left: 230px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            background: #fff; border-bottom: 1px solid var(--card-border);
            padding: 0 1.5rem; height: 54px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-left { font-size: 13px; font-weight: 500; color: var(--text-secondary); }
        .topbar-right { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-muted); }
        .topbar-dot { width: 7px; height: 7px; border-radius: 50%; background: #f59e0b; }

        .page-content { flex: 1; padding: 1.5rem; }
    </style>
    @stack('styles')
</head>
<body>

{{-- SIDEBAR ADMIN --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="label">SIMPERPUS</div>
        <div class="name">SMAN 5 TEBO</div>
        <div class="admin-badge">
            <svg width="9" height="9" viewBox="0 0 16 16" fill="#f59e0b">
                <path d="M8 1a3 3 0 100 6 3 3 0 000-6zM3 13c0-2.8 2.2-5 5-5s5 2.2 5 5H3z"/>
            </svg>
            Kepala Perpustakaan
        </div>
    </div>

    <div class="sidebar-user">
        <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
        <div>
            <div class="user-name">{{ Auth::user()->name }}</div>
            <div class="user-role">Admin</div>
        </div>
    </div>

    {{-- ▼ SEMUA NAV ITEM HARUS DI DALAM .nav INI ▼ --}}
    <nav class="nav">
        <div class="nav-section">Menu</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <rect x="1" y="1" width="6" height="6" rx="1.5"/>
                <rect x="9" y="1" width="6" height="6" rx="1.5"/>
                <rect x="1" y="9" width="6" height="6" rx="1.5"/>
                <rect x="9" y="9" width="6" height="6" rx="1.5"/>
            </svg>
            Dashboard
        </a>

        <div class="nav-section" style="margin-top:0.5rem;">Manajemen</div>

        <a href="{{ route('admin.petugas.index') }}"
           class="nav-item {{ request()->is('admin/petugas*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <circle cx="8" cy="5.5" r="2.5"/>
                <path d="M2.5 13.5c0-3 2.5-5 5.5-5s5.5 2 5.5 5H2.5z"/>
            </svg>
            Kelola Petugas
        </a>

        <div class="nav-section" style="margin-top:0.5rem;">Laporan</div>

        <a href="{{ route('admin.laporan') }}"
           class="nav-item {{ request()->is('admin/laporan*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M3 1h10a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zm1 4v1.5h8V5H4zm0 2.5V9h8V7.5H4zm0 2.5v1.5h5V10H4z"/>
            </svg>
            Laporan
        </a>

        {{-- ▼ SURVEY — di dalam .nav, posisi tepat di bawah Laporan ▼ --}}
        <div class="nav-section" style="margin-top:0.5rem;">Mutu</div>

        <a href="{{ route('admin.survey.index') }}"
           class="nav-item {{ request()->is('admin/survey*') ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 1.5a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11zM5 8.5l2 2 4-4-.7-.7L7 9.1l-1.3-1.3L5 8.5z"/>
            </svg>
            Survey Kepuasan
            @php
                $surveyHariIni = \App\Models\SurveyKepuasan::whereDate('created_at', today())->count();
            @endphp
            @if($surveyHariIni > 0)
                <span class="nav-badge">{{ $surveyHariIni }}</span>
            @endif
        </a>

    </nav>
    {{-- ▲ TUTUP .nav DI SINI, SEBELUM sidebar-footer ▲ --}}

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
        <span class="topbar-left">Panel Kepala Perpustakaan</span>
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
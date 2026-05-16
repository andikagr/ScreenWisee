<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - ScreenWise</title>
    <meta name="description" content="ScreenWise Tracking System - Membantu siswa membangun kebiasaan digital sehat">
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        // Prevent FOUC
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
    @stack('styles')
</head>
<body>
<div class="app-layout">
    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo" style="background: transparent; box-shadow: none;"><img src="{{ asset('images/logo.svg') }}" alt="ScreenWise" style="width:48px;height:48px;object-fit:contain;border-radius:0;background:transparent;filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));"></div>
            <div>
                <div class="sidebar-title">ScreenWise</div>
                <div class="sidebar-subtitle">Tracking System</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            @if(auth()->user()->isSiswa())
            <div class="nav-section">
                <div class="nav-label">Main Menu 🎮</div>
                <a href="{{ route('siswa.dashboard') }}" class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">🏠</span> Ruang Mainku
                </a>
                <a href="{{ route('siswa.tracking.create') }}" class="nav-link {{ request()->routeIs('siswa.tracking.create') ? 'active' : '' }}">
                    <span class="nav-icon">📝</span> Isi Jurnal Harian
                </a>
                <a href="{{ route('siswa.tracking.history') }}" class="nav-link {{ request()->routeIs('siswa.tracking.history') ? 'active' : '' }}">
                    <span class="nav-icon">📚</span> Buku Petualangan (Riwayat)
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-label">Misi Spesial 🌟</div>
                <a href="{{ route('siswa.pretest') }}" class="nav-link {{ request()->routeIs('siswa.pretest') ? 'active' : '' }}">
                    <span class="nav-icon">🎬</span> Pre-Test (Mulai Cerita)
                </a>
                <a href="{{ route('siswa.posttest') }}" class="nav-link {{ request()->routeIs('siswa.posttest') ? 'active' : '' }}">
                    <span class="nav-icon">🏆</span> Post-Test (Akhir Misi)
                </a>
                <a href="{{ route('siswa.comparison') }}" class="nav-link {{ request()->routeIs('siswa.comparison') ? 'active' : '' }}">
                    <span class="nav-icon">✨</span> Bukti Kehebatanmu!
                </a>
            </div>
            @elseif(auth()->user()->isGuru())
            <div class="nav-section">
                <div class="nav-label">Monitoring</div>
                <a href="{{ route('guru.dashboard') }}" class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">🏠</span> Dashboard
                </a>
            </div>
            @elseif(auth()->user()->isAdmin())
            <div class="nav-section">
                <div class="nav-label">Dashboard</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">🏠</span> Dashboard
                </a>
            </div>
            <div class="nav-section">
                <div class="nav-label">Kelola</div>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span> Kelola User
                </a>
                <a href="{{ route('admin.challenges.index') }}" class="nav-link {{ request()->routeIs('admin.challenges.*') ? 'active' : '' }}">
                    <span class="nav-icon">🎯</span> Kelola Challenge
                </a>
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span> Laporan
                </a>
            </div>
            @endif
        </nav>
        <div class="sidebar-footer">
            <div class="user-info">
                @if(auth()->user()->profile_photo_path)
                    <div class="user-avatar" style="overflow: hidden; padding: 0;">
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @else
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->role }}</div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; margin-top: 12px;">
                <a href="{{ route('profile.edit') }}" class="btn btn-sm" style="flex: 1; justify-content: center; background: var(--dark-100); color: var(--dark-800); border: 2px solid var(--dark-200);">
                    ⚙️ Profil
                </a>
                <a href="{{ route('logout') }}" class="btn btn-sm btn-secondary" style="flex: 1; justify-content: center;">
                    🚪 Logout
                </a>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <main class="main-content">
        <div class="top-bar">
            <button class="hamburger" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
            <h1>@yield('page-title', 'Dashboard')</h1>
            <button id="themeToggle" class="dark-toggle" aria-label="Toggle Dark Mode"></button>
        </div>
        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0;padding-left:20px">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </main>
</div>
<script>
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.querySelector('.hamburger');
    if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !hamburger.contains(e.target)) {
        sidebar.classList.remove('open');
    }
});

// Dark Mode Logic
const themeToggle = document.getElementById('themeToggle');
const currentTheme = document.documentElement.getAttribute('data-theme');
if (currentTheme === 'dark') themeToggle.classList.add('active');

themeToggle.addEventListener('click', () => {
    themeToggle.classList.toggle('active');
    if (themeToggle.classList.contains('active')) {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.removeAttribute('data-theme');
        localStorage.setItem('theme', 'light');
    }
});
</script>
@stack('scripts')
</body>
</html>

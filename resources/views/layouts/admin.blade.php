<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LU Academy - Admin{{ isset($title) ? ' · ' . $title : '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        :root { --lu-deep-purple: #2d1b4e; --lu-purple: #4c1d95; --lu-purple-soft: rgba(45, 27, 78, 0.06); }
        body { font-family: 'DM Sans', sans-serif; background: #f8f7fc; }
        .navbar-lu { background: #fff !important; border-bottom: 1px solid rgba(45,27,78,0.08); }
        .nav-link.active { color: var(--lu-deep-purple) !important; font-weight: 600; }
        .btn-lu-primary { background: var(--lu-deep-purple); color: #fff; border: none; }
        .btn-lu-primary:hover { background: var(--lu-purple); color: #fff; }
        .admin-wrap { display: flex; min-height: calc(100vh - 76px); }
        .admin-sidebar {
            width: 260px;
            flex-shrink: 0;
            background: #fff;
            border-right: 1px solid rgba(45,27,78,0.08);
            padding: 1.5rem 0;
            transition: transform 0.2s ease, width 0.2s ease;
        }
        .admin-sidebar .nav-link {
            color: #475569;
            padding: 0.6rem 1.5rem;
            border-left: 3px solid transparent;
            font-size: 0.9375rem;
        }
        .admin-sidebar .nav-link:hover { color: var(--lu-deep-purple); background: var(--lu-purple-soft); }
        .admin-sidebar .nav-link.active { color: var(--lu-deep-purple); background: var(--lu-purple-soft); border-left-color: var(--lu-deep-purple); }
        .admin-sidebar .nav-link svg { opacity: 0.7; margin-right: 0.5rem; vertical-align: -0.2em; flex-shrink: 0; }
        .admin-main { flex: 1; padding: 2rem; overflow-auto; min-width: 0; }
        .admin-main .admin-course-row:hover { background: var(--lu-purple-soft); }
        .admin-main .admin-course-row { border-radius: 0.5rem; }
        .admin-drawer-toggle {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 1020;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--lu-deep-purple);
            color: #fff;
            border: none;
            box-shadow: 0 4px 14px rgba(45, 27, 78, 0.35);
            display: none;
            align-items: center;
            justify-content: center;
        }
        .admin-drawer-toggle:hover { background: var(--lu-purple); color: #fff; }
        .admin-drawer-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1038;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }
        .admin-drawer-backdrop.show { opacity: 1; visibility: visible; }
        @media (max-width: 991.98px) {
            .admin-sidebar { position: fixed; top: 76px; left: 0; bottom: 0; z-index: 1040; transform: translateX(-100%); width: 280px; }
            .admin-sidebar.drawer-open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,0.08); }
            .admin-drawer-toggle { display: flex; }
            .admin-main { padding: 1.25rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('layouts.navigation')

    <div class="admin-drawer-backdrop" id="adminDrawerBackdrop" aria-hidden="true"></div>
    <button type="button" class="admin-drawer-toggle" id="adminDrawerToggle" aria-label="Open menu">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <div class="admin-wrap">
        <aside class="admin-sidebar" id="adminSidebar" aria-label="Admin navigation">
            <nav class="nav flex-column pt-2">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" data-drawer-close>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Overview
                </a>
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}" data-drawer-close>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Users
                </a>
                <a class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}" data-drawer-close>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Courses
                </a>
                <hr class="my-2 mx-3">
                <a class="nav-link" href="{{ route('courses.index') }}" data-drawer-close>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View site
                </a>
            </nav>
        </aside>
        <main class="admin-main">
            <div class="d-lg-none mb-3">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="adminDrawerToggleTop" aria-label="Open menu">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    Menu
                </button>
            </div>
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            var sidebar = document.getElementById('adminSidebar');
            var toggle = document.getElementById('adminDrawerToggle');
            var backdrop = document.getElementById('adminDrawerBackdrop');
            function open() { sidebar.classList.add('drawer-open'); backdrop.classList.add('show'); document.body.style.overflow = 'hidden'; }
            function close() { sidebar.classList.remove('drawer-open'); backdrop.classList.remove('show'); document.body.style.overflow = ''; }
            function toggleDrawer() { sidebar.classList.contains('drawer-open') ? close() : open(); }
            toggle && toggle.addEventListener('click', toggleDrawer);
            var toggleTop = document.getElementById('adminDrawerToggleTop');
            toggleTop && toggleTop.addEventListener('click', toggleDrawer);
            backdrop && backdrop.addEventListener('click', close);
            document.querySelectorAll('[data-drawer-close]').forEach(function(el) { el.addEventListener('click', close); });
        })();
    </script>
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>

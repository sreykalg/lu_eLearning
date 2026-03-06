<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LU Academy - Instructor{{ isset($title) ? ' · ' . $title : '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        :root { --lu-deep-purple: #2d1b4e; --lu-purple: #4c1d95; --lu-purple-soft: rgba(45, 27, 78, 0.06); }
        body { font-family: 'DM Sans', sans-serif; background: #f8f7fc; }
        .navbar-lu { background: #fff !important; border-bottom: 1px solid rgba(45,27,78,0.08); }
        .nav-link.active { color: var(--lu-deep-purple) !important; font-weight: 600; }
        .btn-lu-primary { background: var(--lu-deep-purple); color: #fff; border: none; }
        .btn-lu-primary:hover { background: var(--lu-purple); color: #fff; }
        .instructor-wrap { display: flex; min-height: calc(100vh - 76px); }
        .instructor-sidebar {
            width: 260px;
            flex-shrink: 0;
            background: #fff;
            border-right: 1px solid rgba(45,27,78,0.08);
            padding: 1.5rem 0;
            transition: transform 0.2s ease, width 0.2s ease;
        }
        .instructor-sidebar .nav-link {
            color: #475569;
            padding: 0.6rem 1.5rem;
            border-left: 3px solid transparent;
            font-size: 0.9375rem;
        }
        .instructor-sidebar .nav-link:hover { color: var(--lu-deep-purple); background: var(--lu-purple-soft); }
        .instructor-sidebar .nav-link.active { color: var(--lu-deep-purple); background: var(--lu-purple-soft); border-left-color: var(--lu-deep-purple); }
        .instructor-sidebar .nav-link svg { opacity: 0.7; margin-right: 0.5rem; vertical-align: -0.2em; flex-shrink: 0; }
        .instructor-main { flex: 1; padding: 2rem; overflow-auto; min-width: 0; }
        .instructor-drawer-toggle {
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
        .instructor-drawer-toggle:hover { background: var(--lu-purple); color: #fff; }
        .instructor-drawer-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1038;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }
        .instructor-drawer-backdrop.show { opacity: 1; visibility: visible; }
        @media (max-width: 991.98px) {
            .instructor-sidebar { position: fixed; top: 76px; left: 0; bottom: 0; z-index: 1040; transform: translateX(-100%); width: 280px; }
            .instructor-sidebar.drawer-open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,0.08); }
            .instructor-drawer-toggle { display: flex; }
            .instructor-main { padding: 1.25rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('layouts.navigation')

    <div class="instructor-drawer-backdrop" id="instructorDrawerBackdrop" aria-hidden="true"></div>
    <button type="button" class="instructor-drawer-toggle" id="instructorDrawerToggle" aria-label="Open menu">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <div class="instructor-wrap">
        <aside class="instructor-sidebar" id="instructorSidebar" aria-label="Instructor navigation">
            <nav class="nav flex-column pt-2">
                <a class="nav-link {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}" href="{{ route('instructor.dashboard') }}" data-drawer-close>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    My courses
                </a>
                <a class="nav-link {{ request()->routeIs('instructor.courses.create') ? 'active' : '' }}" href="{{ route('instructor.courses.create') }}" data-drawer-close>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 5v14M5 12h14"/></svg>
                    Create course
                </a>
                <hr class="my-2 mx-3">
                <a class="nav-link" href="{{ route('courses.index') }}" data-drawer-close>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View site
                </a>
            </nav>
        </aside>
        <main class="instructor-main">
            <div class="d-lg-none mb-3">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="instructorDrawerToggleTop" aria-label="Open menu">
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
            var sidebar = document.getElementById('instructorSidebar');
            var toggle = document.getElementById('instructorDrawerToggle');
            var backdrop = document.getElementById('instructorDrawerBackdrop');
            function open() { sidebar.classList.add('drawer-open'); backdrop.classList.add('show'); document.body.style.overflow = 'hidden'; }
            function close() { sidebar.classList.remove('drawer-open'); backdrop.classList.remove('show'); document.body.style.overflow = ''; }
            function toggleDrawer() { sidebar.classList.contains('drawer-open') ? close() : open(); }
            toggle && toggle.addEventListener('click', toggleDrawer);
            var toggleTop = document.getElementById('instructorDrawerToggleTop');
            toggleTop && toggleTop.addEventListener('click', toggleDrawer);
            backdrop && backdrop.addEventListener('click', close);
            document.querySelectorAll('[data-drawer-close]').forEach(function(el) { el.addEventListener('click', close); });
        })();
    </script>
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>

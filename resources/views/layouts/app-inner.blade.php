<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Life University - {{ $title ?? 'LU Academy' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --lu-sidebar: #0f172a;
            --lu-sidebar-hover: rgba(255,255,255,0.08);
            --lu-sidebar-active: rgba(15, 23, 42, 0.3);
            --lu-accent: #0f172a;
        }
        .btn-primary { background-color: #0f172a !important; border-color: #0f172a !important; }
        .btn-primary:hover { background-color: #1e293b !important; border-color: #1e293b !important; }
        .btn-lu-primary { background-color: #0f172a !important; border-color: #0f172a !important; color: #fff !important; }
        .btn-lu-primary:hover { background-color: #1e293b !important; border-color: #1e293b !important; color: #fff !important; }
        .bg-primary { background-color: #0f172a !important; }
        .text-primary { color: #0f172a !important; }
        .form-check-input:checked { background-color: #0f172a !important; border-color: #0f172a !important; }
        .form-control:focus, .form-select:focus { border-color: #0f172a !important; box-shadow: 0 0 0 0.25rem rgba(15, 23, 42, 0.25) !important; }
        .dropdown-item.active { background-color: #0f172a !important; }
        .dropdown-toggle:focus, .cb-dropdown:focus, .btn.show { border-color: #0f172a !important; box-shadow: 0 0 0 0.25rem rgba(15, 23, 42, 0.25) !important; }
        body { font-family: 'DM Sans', sans-serif; background: #f1f5f9; margin: 0; }
        .inner-wrap { display: flex; min-height: 100vh; }
        .inner-sidebar {
            width: 260px;
            flex-shrink: 0;
            background: var(--lu-sidebar);
            color: #94a3b8;
            padding: 1.25rem 0;
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
        }
        .inner-sidebar .role-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; padding: 0 1.25rem; margin-bottom: 1rem; color: #64748b; }
        .inner-sidebar .nav-link {
            color: #94a3b8;
            padding: 0.6rem 1.25rem;
            font-size: 0.9375rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border-left: 3px solid transparent;
        }
        .inner-sidebar .nav-link:hover { color: #fff; background: var(--lu-sidebar-hover); }
        .inner-sidebar .nav-link.active { color: #fff; background: var(--lu-sidebar-active); border-left-color: var(--lu-accent); }
        .inner-sidebar .nav-link svg { flex-shrink: 0; opacity: 0.8; }
        .inner-sidebar .user-block {
            margin-top: auto;
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .inner-sidebar .user-block .avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: var(--lu-accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .inner-sidebar .user-block .name { color: #fff; font-size: 0.875rem; }
        .inner-sidebar .user-block .role { color: #64748b; font-size: 0.75rem; }
        .inner-main { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .inner-header {
            background: #fff;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .inner-header .logo { display: flex; align-items: center; gap: 0.5rem; color: #0f172a; font-weight: 700; text-decoration: none; }
        .inner-header .search {
            flex: 1;
            max-width: 400px;
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }
        .inner-header .search::placeholder { color: #94a3b8; }
        .inner-header .header-right { display: flex; align-items: center; gap: 1rem; margin-left: auto; }
        .inner-header .header-right .notif { position: relative; }
        .inner-header .header-right .notif .dot { position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; }
        .inner-content { flex: 1; padding: 1.5rem; overflow-auto; }
        .inner-drawer-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1038;
        }
        .inner-drawer-backdrop.show { display: block; }
        .inner-drawer-toggle {
            display: none;
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 1040;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--lu-sidebar);
            color: #fff;
            border: none;
            box-shadow: 0 4px 12px rgba(15,23,42,0.4);
        }
        @media (max-width: 991.98px) {
            .inner-sidebar { position: fixed; top: 0; left: 0; bottom: 0; z-index: 1040; transform: translateX(-100%); width: 260px; }
            .inner-sidebar.drawer-open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,0.2); }
            .inner-drawer-toggle { display: flex; align-items: center; justify-content: center; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="inner-drawer-backdrop" id="innerDrawerBackdrop"></div>
    <button type="button" class="inner-drawer-toggle" id="innerDrawerToggle" aria-label="Menu">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>

    <div class="inner-wrap">
        <aside class="inner-sidebar" id="innerSidebar">
            <div class="role-label">
                @auth
                    @if(auth()->user()->isHeadOfDept())
                        HEAD OF DEPARTMENT
                    @elseif(auth()->user()->isInstructor())
                        INSTRUCTOR
                    @else
                        STUDENT
                    @endif
                @else
                    STUDENT
                @endauth
            </div>
            <nav class="nav flex-column">
                @yield('sidebar-nav')
            </nav>
            <div class="user-block">
                @php
$name = auth()->user()->name ?? 'U';
$parts = array_filter(explode(' ', $name));
$initials = count($parts) >= 2 ? Str::upper(mb_substr($parts[0],0,1).mb_substr($parts[count($parts)-1],0,1)) : Str::upper(mb_substr($name,0,2));
@endphp
                <div class="avatar">{{ $initials }}</div>
                <div>
                    <div class="name">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="role">{{ ucfirst(str_replace('_', ' ', auth()->user()->role ?? 'student')) }}</div>
                </div>
            </div>
        </aside>
        <div class="inner-main">
            <header class="inner-header">
                <a href="{{ auth()->user()->isStudent() ? route('student.dashboard') : (auth()->user()->isInstructor() ? route('instructor.dashboard') : (auth()->user()->isHeadOfDept() ? route('hod.dashboard') : url('/'))) }}" class="logo">
                    <img src="/images/life-university-logo.png" alt="" height="32">
                    <span>Life University</span>
                </a>
                <input type="search" class="search" placeholder="Search courses, lessons..." aria-label="Search">
                <div class="header-right">
                    <a href="#" class="notif text-dark text-decoration-none">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="dot"></span>
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-link p-0 text-decoration-none d-flex align-items-center" data-bs-toggle="dropdown">
                            <div class="avatar" style="width:36px;height:36px;font-size:0.75rem;">{{ $initials }}</div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="dropdown-item">Log out</button></form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            <main class="inner-content">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function(){
            var s=document.getElementById('innerSidebar'),t=document.getElementById('innerDrawerToggle'),b=document.getElementById('innerDrawerBackdrop');
            function open(){s.classList.add('drawer-open');b.classList.add('show');document.body.style.overflow='hidden';}
            function close(){s.classList.remove('drawer-open');b.classList.remove('show');document.body.style.overflow='';}
            t&&t.addEventListener('click',function(){s.classList.contains('drawer-open')?close():open();});
            b&&b.addEventListener('click',close);
            document.querySelectorAll('[data-drawer-close]').forEach(function(el){el.addEventListener('click',close);});
        })();
    </script>
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>

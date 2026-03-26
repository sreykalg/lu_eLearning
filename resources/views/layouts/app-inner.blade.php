<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Life University - {{ $title ?? 'LU Academy' }}</title>
    <link rel="icon" href="/images/life-university-logo.png" type="image/png">
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
            position: relative;
            width: 260px;
            flex-shrink: 0;
            background: var(--lu-sidebar);
            color: #94a3b8;
            padding: 1.25rem 0;
            transition: width 0.25s ease, padding 0.25s ease;
            display: flex;
            flex-direction: column;
            overflow-x: visible;
            overflow-y: auto;
        }
        .inner-sidebar.collapsed {
            width: 72px;
            padding: 1rem 0;
        }
        .inner-sidebar.collapsed .role-label,
        .inner-sidebar.collapsed .nav-link span,
        .inner-sidebar.collapsed .user-block .name,
        .inner-sidebar.collapsed .user-block .role { display: none; }
        .inner-sidebar.collapsed .nav-link { justify-content: center; padding: 0.6rem 1rem; }
        .inner-sidebar.collapsed .nav-link[title] { cursor: pointer; }
        .inner-sidebar.collapsed .user-block { justify-content: center; flex-direction: column; gap: 0.25rem; padding: 1rem 0.5rem; }
        .inner-sidebar .nav-link span { white-space: nowrap; }
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
        .inner-sidebar .user-block .avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
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
        .inner-header .search-form .search {
            flex: 1;
            min-width: 0;
            height: 40px;
            padding: 0.55rem 0.95rem;
            border: 1px solid #dbe3ee;
            border-radius: 0.7rem;
            font-size: 0.875rem;
            background: #f8fafc;
            transition: all 0.2s ease;
        }
        .inner-header .search-form .search:focus { outline: none; border-color: #94a3b8; background: #fff; box-shadow: 0 0 0 3px rgba(15,23,42,0.08); }
        .inner-header .search::placeholder { color: #94a3b8; }
        .inner-header .search-form { max-width: 400px; width: 100%; min-width: 0; position: relative; }
        .inner-header .search-form .search { border-top-right-radius: 0.65rem; border-bottom-right-radius: 0.65rem; }
        .inner-header .search-form .search-btn { margin-left: 0.35rem; border-radius: 0.65rem; height: 40px; min-width: 40px; padding: 0.45rem 0.7rem; background: #0f172a; color: #fff; border: 1px solid #0f172a; }
        .inner-header .search-form .search-btn:hover { background: #1e293b; color: #fff; border-color: #1e293b; }
        .inner-header .header-center { flex: 1; display: flex; justify-content: center; min-width: 0; }
        .inner-header .search-history { position: absolute; top: 100%; left: 0; right: 0; margin-top: 0.25rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-height: 220px; overflow-y: auto; z-index: 1050; display: none; }
        .inner-header .search-history.show { display: block; }
        .inner-header .search-history-item { padding: 0.5rem 1rem; font-size: 0.875rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; color: #334155; border-bottom: 1px solid #f1f5f9; }
        .inner-header .search-history-item:last-child { border-bottom: none; }
        .inner-header .search-history-item:hover { background: #f8fafc; }
        .inner-header .search-history-item svg { flex-shrink: 0; opacity: 0.6; }
        .inner-header .header-right { display: flex; align-items: center; gap: 1rem; margin-left: auto; }
        .inner-header .header-right .notif { position: relative; }
        .inner-header .header-right .notif .dot { position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; display: none; }
        .inner-header .header-right .notif.has-unread .dot { display: block; }
        .inner-header .notif-dropdown { min-width: 320px; max-width: 360px; max-height: 400px; overflow-y: auto; }
        .inner-header .notif-item { padding: 0.75rem 1rem; border-bottom: 1px solid #f1f5f9; display: block; color: inherit; text-decoration: none; font-size: 0.875rem; }
        .inner-header .notif-item:hover { background: #f8fafc; }
        .inner-header .notif-item:last-child { border-bottom: none; }
        .inner-header .notif-item.unread { background: #f8fafc; }
        .inner-header .notif-item .notif-text { color: #334155; }
        .inner-header .notif-item .notif-time { font-size: 0.75rem; color: #94a3b8; }
        .inner-header .profile-dropdown .dropdown-toggle { border: none; background: transparent; padding: 0.25rem; border-radius: 0.5rem; transition: background 0.2s; }
        .inner-header .profile-dropdown .dropdown-toggle:hover { background: #f1f5f9; }
        .inner-header .profile-dropdown .dropdown-menu { min-width: 260px; padding: 0; border: 1px solid #e2e8f0; border-radius: 0.75rem; box-shadow: 0 10px 40px rgba(0,0,0,0.12); margin-top: 0.5rem; overflow: hidden; }
        .inner-header .profile-dropdown .dropdown-header-custom { padding: 1rem 1.25rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; }
        .inner-header .profile-dropdown .dropdown-header-custom .avatar { width: 48px; height: 48px; border-radius: 50%; background: rgba(255,255,255,0.2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 600; }
        .inner-header .profile-dropdown .dropdown-header-custom .avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
        .inner-header .profile-dropdown .dropdown-header-custom .name { font-weight: 600; font-size: 0.9375rem; }
        .inner-header .profile-dropdown .dropdown-header-custom .email { font-size: 0.75rem; opacity: 0.85; }
        .inner-header .profile-dropdown .dropdown-item { padding: 0.65rem 1.25rem; display: flex; align-items: center; gap: 0.6rem; }
        .inner-header .profile-dropdown .dropdown-item svg { width: 18px; height: 18px; opacity: 0.7; flex-shrink: 0; }
        .inner-header .profile-dropdown .dropdown-item:hover { background: #f8fafc; }
        .inner-header .profile-dropdown .dropdown-divider { margin: 0; }
        .inner-header .profile-dropdown .dropdown-menu > li:first-child { list-style: none; }
        .inner-content { flex: 1; padding: 1.5rem; overflow-auto; }
        .inner-drawer-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1038;
        }
        .inner-drawer-backdrop.show { display: block; }
        .inner-sidebar-toggle {
            position: absolute;
            right: -18px;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fff;
            color: var(--lu-sidebar);
            border: 2px solid var(--lu-sidebar);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .inner-sidebar-toggle:hover { transform: translateY(-50%) scale(1.08); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
        .inner-sidebar.collapsed .inner-sidebar-toggle { right: -12px; }
        .inner-sidebar-toggle svg { transition: transform 0.25s; width: 18px; height: 18px; }
        .inner-sidebar.collapsed .inner-sidebar-toggle svg { transform: rotate(180deg); }
        .inner-drawer-toggle {
            display: none;
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 40px;
            height: 40px;
            border-radius: 0.5rem;
            background: transparent;
            color: #0f172a;
            border: none;
            align-items: center;
            justify-content: center;
        }
        .inner-drawer-toggle:hover { background: #f1f5f9; color: #0f172a; }
        @media (max-width: 991.98px) {
            .inner-sidebar { position: fixed; top: 0; left: 0; bottom: 0; z-index: 1040; transform: translateX(-100%); width: 260px !important; }
            .inner-sidebar.collapsed { width: 260px !important; }
            .inner-sidebar.drawer-open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,0.2); }
            .inner-sidebar-toggle { display: none; }
            .inner-header { position: relative; padding-left: 3rem; }
            .inner-drawer-toggle { display: flex; }
        }
        @media (min-width: 992px) {
            .inner-drawer-toggle { display: none !important; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('layouts.partials.screen-size-restriction')
    <div class="inner-drawer-backdrop" id="innerDrawerBackdrop"></div>

    <div class="inner-wrap">
        <aside class="inner-sidebar" id="innerSidebar">
            <button type="button" class="inner-sidebar-toggle" id="innerSidebarToggle" aria-label="Toggle sidebar">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
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
                    GUEST
                @endauth
            </div>
            <nav class="nav flex-column">
                @yield('sidebar-nav')
            </nav>
            <div class="user-block">
                @auth
                    @php
                        $name = auth()->user()->name ?? 'U';
                        $parts = array_filter(explode(' ', $name));
                        $initials = count($parts) >= 2 ? Str::upper(mb_substr($parts[0],0,1).mb_substr($parts[count($parts)-1],0,1)) : Str::upper(mb_substr($name,0,2));
                        $profilePhotoUrl = !empty(auth()->user()->profile_photo_path) ? asset('storage/' . auth()->user()->profile_photo_path) : null;
                    @endphp
                    <div class="avatar">
                        @if($profilePhotoUrl)
                            <img src="{{ $profilePhotoUrl }}" alt="{{ auth()->user()->name ?? 'User' }}">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <div>
                        <div class="name">{{ auth()->user()->name ?? 'User' }}</div>
                        <div class="role">{{ ucfirst(str_replace('_', ' ', auth()->user()->role ?? 'student')) }}</div>
                    </div>
                @else
                    @hasSection('sidebar-guest-block')
                        @yield('sidebar-guest-block')
                    @else
                        <a href="{{ route('login') }}" class="nav-link py-2" data-drawer-close><span>Log in</span></a>
                        <a href="{{ route('register') }}" class="nav-link py-2" data-drawer-close><span>Register</span></a>
                    @endif
                @endauth
            </div>
        </aside>
        <div class="inner-main">
            <header class="inner-header">
                <button type="button" class="inner-drawer-toggle" id="innerDrawerToggle" aria-label="Menu">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <a href="{{ auth()->check() ? (auth()->user()->isStudent() ? route('student.dashboard') : (auth()->user()->isInstructor() ? route('instructor.dashboard') : (auth()->user()->isHeadOfDept() ? route('hod.dashboard') : url('/')))) : route('courses.index') }}" class="logo flex-shrink-0">
                    <img src="/images/life-university-logo.png" alt="" height="32">
                    <span>Life University</span>
                </a>
                <div class="header-center">
                <form action="{{ auth()->check() && auth()->user()->isStudent() ? route('student.courses') : route('courses.index') }}" method="get" class="search-form d-flex align-items-center" id="headerSearchForm">
                    <input type="search" name="{{ auth()->check() && auth()->user()->isStudent() ? 'search' : 'q' }}" class="search" placeholder="Search courses, lessons..." value="{{ auth()->check() && auth()->user()->isStudent() ? request('search') : request('q') }}" aria-label="Search" id="headerSearchInput" autocomplete="off">
                    <button type="submit" class="search-btn" aria-label="Search" title="Search">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    <div class="search-history" id="searchHistoryDropdown" role="listbox">
                        <div class="search-history-item text-muted small px-3 py-2" id="searchHistoryEmpty" style="cursor:default;">No recent searches</div>
                    </div>
                </form>
                </div>
                <div class="header-right flex-shrink-0">
                    @auth
                    @php
                        $unreadNotifications = auth()->user()->unreadNotifications()->take(20)->get();
                        $hasUnread = $unreadNotifications->isNotEmpty();
                    @endphp
                    <div class="dropdown notif {{ $hasUnread ? 'has-unread' : '' }}">
                        <button type="button" class="notif btn btn-link text-dark text-decoration-none p-0 border-0 bg-transparent dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span class="dot"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notif-dropdown">
                            <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                                <span class="fw-semibold small">Notifications</span>
                                @if($hasUnread)
                                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="mb-0">
                                    @csrf
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-primary text-decoration-none">Mark all read</button>
                                </form>
                                @endif
                            </div>
                            @forelse($unreadNotifications->filter(fn($n) => str_contains($n->type ?? '', 'MentionInDiscussion')) as $n)
                                <a href="{{ route('notifications.read', $n->id) }}" class="notif-item unread">
                                    <div class="notif-text">{{ $n->data['mentioner_name'] ?? 'Someone' }} mentioned you in a discussion</div>
                                    @if(!empty($n->data['excerpt']))
                                    <div class="notif-time mt-1">{{ Str::limit($n->data['excerpt'], 60) }}</div>
                                    @endif
                                    <div class="notif-time">{{ $n->created_at->diffForHumans() }}</div>
                                </a>
                            @empty
                                <div class="px-3 py-4 text-center text-muted small">No new notifications</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="dropdown profile-dropdown">
                        <button class="dropdown-toggle btn d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar d-flex align-items-center justify-content-center rounded-circle overflow-hidden" style="width:40px;height:40px;font-size:0.875rem;font-weight:600;background:#0f172a;color:#fff;">
                                @if(!empty($profilePhotoUrl))
                                    <img src="{{ $profilePhotoUrl }}" alt="{{ auth()->user()->name ?? 'User' }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    {{ $initials ?? 'U' }}
                                @endif
                            </div>
                            <span class="d-none d-md-inline text-dark fw-medium" style="font-size:0.9rem;">{{ auth()->user()->name ?? 'User' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="px-0">
                                <div class="dropdown-header-custom d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        @if(!empty($profilePhotoUrl))
                                            <img src="{{ $profilePhotoUrl }}" alt="{{ auth()->user()->name ?? 'User' }}">
                                        @else
                                            {{ $initials ?? 'U' }}
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-grow-1">
                                        <div class="name text-truncate">{{ auth()->user()->name ?? 'User' }}</div>
                                        <div class="email text-truncate">{{ auth()->user()->email ?? '' }}</div>
                                        <div class="small mt-0" style="opacity:0.75;">{{ ucfirst(str_replace('_',' ', auth()->user()->role ?? 'student')) }}</div>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" data-action="show-profile">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="mb-0">@csrf
                                    <button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start d-flex align-items-center gap-2">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Log out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="nav-link text-dark text-decoration-none">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                    @endauth
                </div>
            </header>
            <main class="inner-content">
                <div id="pageContent">
                    @yield('content')
                </div>
                <div id="profileContent" style="display:none;">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none mb-2 p-0" data-action="hide-profile" aria-label="Back">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1 align-text-bottom"><path stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        {{ __('Back') }}
                    </button>
                    @include('profile.partials.panel-content', ['user' => auth()->user()])
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function(){
            var pageContent=document.getElementById('pageContent'), profileContent=document.getElementById('profileContent');
            document.querySelectorAll('[data-action="show-profile"]').forEach(function(btn){
                btn.addEventListener('click',function(e){ e.preventDefault(); if(pageContent){ pageContent.style.display='none'; } if(profileContent){ profileContent.style.display='block'; } });
            });
            document.querySelectorAll('[data-action="hide-profile"]').forEach(function(btn){
                btn.addEventListener('click',function(){ if(pageContent){ pageContent.style.display='block'; } if(profileContent){ profileContent.style.display='none'; } });
            });
        })();
    </script>
    <script>
        (function(){
            var s=document.getElementById('innerSidebar'),t=document.getElementById('innerDrawerToggle'),b=document.getElementById('innerDrawerBackdrop'),toggle=document.getElementById('innerSidebarToggle');
            var STORAGE_KEY='lu-sidebar-collapsed';
            function open(){s.classList.add('drawer-open');b.classList.add('show');document.body.style.overflow='hidden';}
            function close(){s.classList.remove('drawer-open');b.classList.remove('show');document.body.style.overflow='';}
            t&&t.addEventListener('click',function(){s.classList.contains('drawer-open')?close():open();});
            b&&b.addEventListener('click',close);
            document.querySelectorAll('[data-drawer-close]').forEach(function(el){el.addEventListener('click',close);});
            if(toggle&&s){
                var collapsed=localStorage.getItem(STORAGE_KEY)==='1';
                if(collapsed)s.classList.add('collapsed');
                toggle.addEventListener('click',function(){
                    s.classList.toggle('collapsed');
                    localStorage.setItem(STORAGE_KEY,s.classList.contains('collapsed')?'1':'0');
                });
                s.querySelectorAll('.nav-link').forEach(function(link){
                    var span=link.querySelector('span');
                    if(span)link.setAttribute('title',span.textContent.trim());
                });
            }
        })();
    </script>
    <script>
    (function(){
        var STORAGE_KEY='lu-search-history', MAX=10, form=document.getElementById('headerSearchForm'), input=document.getElementById('headerSearchInput'), dropdown=document.getElementById('searchHistoryDropdown'), emptyEl=document.getElementById('searchHistoryEmpty');
        function getHistory(){ try{ var j=localStorage.getItem(STORAGE_KEY); return j?JSON.parse(j):[]; }catch(e){ return []; } }
        function saveHistory(arr){ try{ localStorage.setItem(STORAGE_KEY,JSON.stringify(arr.slice(0,MAX))); }catch(e){} }
        function addToHistory(q){ if(!q||!q.trim())return; var h=getHistory(); h=h.filter(function(x){ return x!==q.trim(); }); h.unshift(q.trim()); saveHistory(h); }
        function renderHistory(){ var h=getHistory(); emptyEl.style.display=h.length?'none':'block'; dropdown.querySelectorAll('.search-history-item[data-query]').forEach(function(el){ el.remove(); }); h.forEach(function(q){ var a=document.createElement('div'); a.className='search-history-item'; a.setAttribute('data-query',q); a.setAttribute('role','option'); a.innerHTML='<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span>'+q.replace(/</g,'&lt;').replace(/>/g,'&gt;')+'</span>'; a.addEventListener('mousedown',function(e){ e.preventDefault(); input.value=q; dropdown.classList.remove('show'); form.submit(); }); dropdown.insertBefore(a,emptyEl); }); }
        function showDropdown(){ renderHistory(); dropdown.classList.add('show'); }
        function hideDropdown(){ setTimeout(function(){ dropdown.classList.remove('show'); },180); }
        if(form&&input&&dropdown){
            form.addEventListener('submit',function(){ addToHistory(input.value); });
            input.addEventListener('focus',showDropdown);
            input.addEventListener('blur',hideDropdown);
            input.addEventListener('input',function(){ if(input.value)showDropdown(); else renderHistory(); });
            document.addEventListener('click',function(e){ if(!form.contains(e.target))hideDropdown(); });
        }
    })();
    </script>
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>

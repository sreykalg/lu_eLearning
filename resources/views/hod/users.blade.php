@extends('layouts.hod-inner')

@push('styles')
<style>
    .hod-usr-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 1.35rem 1.5rem;
        color: #fff;
        margin-bottom: 1.25rem;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2);
    }
    .hod-usr-hero .hero-left { display: flex; align-items: center; gap: 1rem; }
    .hod-usr-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .hod-usr-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .hod-usr-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; }
    .hod-usr-toolbar {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .hod-usr-toolbar__body {
        padding: 1rem 1.15rem 1.15rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1rem 1.25rem;
    }
    .hod-users-search-wrap { position: relative; flex: 0 1 320px; min-width: 200px; }
    .hod-users-search-wrap svg {
        position: absolute;
        left: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }
    .hod-users-search {
        width: 100%;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 0.65rem;
        padding: 0.55rem 1rem 0.55rem 2.65rem;
        font-size: 0.9rem;
        transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
    }
    .hod-users-search:focus {
        outline: none;
        border-color: #0f172a;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
    }
    .hod-usr-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
        flex: 1;
        min-width: 0;
    }
    @media (max-width: 767.98px) {
        .hod-usr-chips {
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 0.25rem;
            -webkit-overflow-scrolling: touch;
        }
    }
    .hod-role-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.9rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 9999px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #334155;
        text-decoration: none;
        white-space: nowrap;
        flex-shrink: 0;
        transition: background 0.15s, color 0.15s, border-color 0.15s, box-shadow 0.15s;
    }
    .hod-role-chip:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .hod-role-chip.active {
        background: #0f172a;
        color: #fff;
        border-color: #0f172a;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.2);
    }
    .hod-usr-panel {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .hod-usr-table thead th {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 0.85rem 1.25rem !important;
        white-space: nowrap;
    }
    .hod-usr-table tbody td {
        padding: 1rem 1.25rem !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .hod-usr-table tbody tr:nth-child(even) { background: #fafbfc; }
    .hod-usr-table tbody tr:hover { background: #f1f5f9; }
    .hod-role-pill {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.35rem 0.7rem;
        border-radius: 9999px;
        letter-spacing: 0.02em;
    }
    .hod-role-student { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
    .hod-role-instructor { background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }
    .hod-role-head_of_dept, .hod-role-admin { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .hod-status-pill {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.35rem 0.7rem;
        border-radius: 9999px;
    }
    .hod-status-active { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .hod-status-inactive { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
    .hod-user-cell { display: flex; align-items: center; gap: 0.85rem; }
    .hod-user-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        background: linear-gradient(145deg, #0f172a 0%, #334155 100%);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: 700;
        flex-shrink: 0;
        border: 1px solid #e2e8f0;
    }
    .hod-user-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .hod-user-name { font-weight: 600; color: #0f172a; }
    .hod-usr-empty {
        text-align: center;
        padding: 2.5rem 1.5rem;
        color: #64748b;
    }
</style>
@endpush

@section('content')
<div class="hod-usr-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m20 0v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <div>
            <h1 class="hero-title">User management</h1>
            <p class="hero-subtitle">{{ $users->total() }} {{ Str::plural('user', $users->total()) }} on the platform</p>
        </div>
    </div>
</div>

<form method="get" action="{{ route('hod.users') }}" class="hod-usr-toolbar">
    <div class="hod-usr-toolbar__body">
        <div class="hod-users-search-wrap">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <label class="visually-hidden" for="hod-users-search">Search users</label>
            <input id="hod-users-search" type="search" name="q" class="hod-users-search" placeholder="Search by name or email…" value="{{ request('q') }}" autocomplete="off">
        </div>
        @if(request('role'))
            <input type="hidden" name="role" value="{{ request('role') }}">
        @endif
        <div class="hod-usr-chips" role="group" aria-label="Filter by role">
            <a href="{{ route('hod.users', request()->except('role', 'page')) }}" class="hod-role-chip {{ !request('role') ? 'active' : '' }}">All roles</a>
            <a href="{{ route('hod.users', array_merge(request()->except('role', 'page'), ['role' => 'student'])) }}" class="hod-role-chip {{ request('role') === 'student' ? 'active' : '' }}">Student</a>
            <a href="{{ route('hod.users', array_merge(request()->except('role', 'page'), ['role' => 'instructor'])) }}" class="hod-role-chip {{ request('role') === 'instructor' ? 'active' : '' }}">Instructor</a>
            <a href="{{ route('hod.users', array_merge(request()->except('role', 'page'), ['role' => 'head_of_dept'])) }}" class="hod-role-chip {{ request('role') === 'head_of_dept' ? 'active' : '' }}">Head of Dept</a>
        </div>
    </div>
</form>

<div class="hod-usr-panel">
    <div class="table-responsive">
        <table class="table hod-usr-table mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>
                            <div class="hod-user-cell">
                                @php
                                    $name = $u->name ?? 'U';
                                    $parts = array_filter(explode(' ', $name));
                                    $initials = count($parts) >= 2 ? Str::upper(mb_substr($parts[0],0,1).mb_substr($parts[count($parts)-1],0,1)) : Str::upper(mb_substr($name,0,2));
                                    $photoUrl = !empty($u->profile_photo_path) ? asset('storage/' . $u->profile_photo_path) : null;
                                @endphp
                                <div class="hod-user-avatar">
                                    @if($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="{{ $u->name }}">
                                    @else
                                        {{ $initials }}
                                    @endif
                                </div>
                                <span class="hod-user-name">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="text-secondary small">{{ $u->email }}</td>
                        <td>
                            @php
                                $roleClass = match($u->role) {
                                    'student' => 'hod-role-student',
                                    'instructor' => 'hod-role-instructor',
                                    'head_of_dept', 'admin' => 'hod-role-head_of_dept',
                                    default => 'hod-role-student',
                                };
                                $roleLabel = match($u->role) {
                                    'head_of_dept' => 'Head of Dept',
                                    'admin' => 'Admin',
                                    default => ucfirst($u->role ?? 'Student'),
                                };
                            @endphp
                            <span class="hod-role-pill {{ $roleClass }}">{{ $roleLabel }}</span>
                        </td>
                        <td>
                            <span class="hod-status-pill hod-status-active">Active</span>
                        </td>
                        <td class="text-secondary small">{{ $u->created_at->format('M j, Y') }}</td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-link p-0 text-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Actions for {{ $u->name }}">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 py-2">
                                    <li><a class="dropdown-item rounded-2" href="#">View</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="hod-usr-empty">No users match your search or filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="p-3 border-top bg-light">{{ $users->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection

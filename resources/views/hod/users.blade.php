@extends('layouts.hod-inner')

@push('styles')
<style>
    .hod-users-search {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem 0.5rem 2.5rem;
        font-size: 0.9375rem;
        width: 100%;
        max-width: 320px;
    }
    .hod-users-search:focus { outline: none; border-color: #0f172a; box-shadow: 0 0 0 3px rgba(15,23,42,0.1); }
    .hod-users-search-wrap { position: relative; }
    .hod-users-search-wrap svg { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af; pointer-events: none; }
    .hod-role-chip {
        padding: 0.4rem 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #374151;
        text-decoration: none;
        transition: all 0.15s;
    }
    .hod-role-chip:hover { background: #f9fafb; border-color: #d1d5db; }
    .hod-role-chip.active { background: #0f172a; color: #fff; border-color: #0f172a; }
    .hod-role-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 9999px; }
    .hod-role-student { background: #dbeafe; color: #1e40af; }
    .hod-role-instructor { background: #ffedd5; color: #c2410c; }
    .hod-role-head_of_dept, .hod-role-admin { background: #dcfce7; color: #166534; }
    .hod-status-pill { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 9999px; }
    .hod-status-active { background: #dcfce7; color: #166534; }
    .hod-status-inactive { background: #f3f4f6; color: #6b7280; }
    .hod-user-cell { display: flex; align-items: center; gap: 0.75rem; }
    .hod-user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #0f172a; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; font-weight: 600; flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">User Management</h1>
    <p class="text-muted mb-0">{{ $users->total() }} users on platform</p>
</div>

<form method="get" class="mb-4">
    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
        <div class="hod-users-search-wrap">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="search" name="q" class="hod-users-search" placeholder="Search users..." value="{{ request('q') }}" aria-label="Search users">
        </div>
        @if(request('role'))
            <input type="hidden" name="role" value="{{ request('role') }}">
        @endif
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('hod.users', request()->except('role', 'page')) }}" class="hod-role-chip {{ !request('role') ? 'active' : '' }}">All Roles</a>
            <a href="{{ route('hod.users', array_merge(request()->except('role', 'page'), ['role' => 'student'])) }}" class="hod-role-chip {{ request('role') === 'student' ? 'active' : '' }}">Student</a>
            <a href="{{ route('hod.users', array_merge(request()->except('role', 'page'), ['role' => 'instructor'])) }}" class="hod-role-chip {{ request('role') === 'instructor' ? 'active' : '' }}">Instructor</a>
            <a href="{{ route('hod.users', array_merge(request()->except('role', 'page'), ['role' => 'head_of_dept'])) }}" class="hod-role-chip {{ request('role') === 'head_of_dept' ? 'active' : '' }}">Head of Dept</a>
        </div>
    </div>
</form>

<div class="rounded-3 bg-white shadow-sm border overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background: #f9fafb;">
                <tr>
                    <th class="border-0 py-3 px-4 fw-semibold">User</th>
                    <th class="border-0 py-3 px-4 fw-semibold">Email</th>
                    <th class="border-0 py-3 px-4 fw-semibold">Role</th>
                    <th class="border-0 py-3 px-4 fw-semibold">Status</th>
                    <th class="border-0 py-3 px-4 fw-semibold">Joined</th>
                    <th class="border-0 py-3 px-4 fw-semibold text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td class="py-3 px-4">
                            <div class="hod-user-cell">
                                @php
                                    $name = $u->name ?? 'U';
                                    $parts = array_filter(explode(' ', $name));
                                    $initials = count($parts) >= 2 ? Str::upper(mb_substr($parts[0],0,1).mb_substr($parts[count($parts)-1],0,1)) : Str::upper(mb_substr($name,0,2));
                                @endphp
                                <div class="hod-user-avatar">{{ $initials }}</div>
                                <span>{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-muted">{{ $u->email }}</td>
                        <td class="py-3 px-4">
                            @php
                                $roleClass = match($u->role) {
                                    'student' => 'hod-role-student',
                                    'instructor' => 'hod-role-instructor',
                                    'head_of_dept', 'admin' => 'hod-role-head_of_dept',
                                    default => 'hod-role-student',
                                };
                                $roleLabel = match($u->role) {
                                    'head_of_dept' => 'Head of Dept',
                                    default => ucfirst($u->role ?? 'Student'),
                                };
                            @endphp
                            <span class="hod-role-pill {{ $roleClass }}">{{ $roleLabel }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="hod-status-pill hod-status-active">Active</span>
                        </td>
                        <td class="py-3 px-4 text-muted small">{{ $u->created_at->format('M j, Y') }}</td>
                        <td class="py-3 px-4 text-end">
                            <div class="dropdown">
                                <button class="btn btn-link p-0 text-secondary" type="button" data-bs-toggle="dropdown" aria-label="Actions">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">View</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted text-center py-5">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="p-3 border-top">{{ $users->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection

@extends('layouts.admin')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="page-hero">
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m20 0v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div>
                <h1 class="h3 hero-title">User Management</h1>
                <p class="hero-subtitle">Manage user accounts and roles.</p>
            </div>
        </div>
    </div>

    <div class="rounded-3 p-4 mb-4" style="background: #fff; border: 1px solid rgba(45,27,78,0.08);">
        <form method="get" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-0">Search</label>
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Name or email" value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-0">Role</label>
                <select name="role" class="form-select form-select-sm">
                    <option value="">All roles</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="instructor" {{ request('role') === 'instructor' ? 'selected' : '' }}>Instructor</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-lu-primary btn-sm w-100">Filter</button>
            </div>
        </form>
    </div>

    <div class="rounded-3 overflow-hidden" style="background: #fff; border: 1px solid rgba(45,27,78,0.08);">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: rgba(45,27,78,0.04);">
                    <tr>
                        <th class="border-0 py-3 ps-4 fw-semibold" style="color: var(--lu-deep-purple); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">User</th>
                        <th class="border-0 py-3 fw-semibold" style="color: var(--lu-deep-purple); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Role</th>
                        <th class="border-0 py-3 pe-4 fw-semibold text-end" style="color: var(--lu-deep-purple); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-medium">{{ $user->name }}</div>
                                <div class="text-muted small">{{ $user->email }}</div>
                            </td>
                            <td class="py-3">
                                <span class="rounded-pill px-2 py-1 small" style="background: rgba(45,27,78,0.08); color: var(--lu-deep-purple);">{{ ucfirst($user->role) }}</span>
                            </td>
                            <td class="py-3 pe-4 text-muted small text-end">{{ $user->created_at->format('M j, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-muted text-center py-5">No users match your filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-4 py-3 border-top" style="background: rgba(45,27,78,0.02);">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

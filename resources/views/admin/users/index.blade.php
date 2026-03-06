@extends('layouts.admin')

@section('content')
<div class="admin-page">
    <p class="text-muted mb-4">Manage user accounts and roles.</p>

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

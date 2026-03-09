@extends('layouts.hod-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">User Management</h1>
    <p class="text-muted mb-0">{{ $users->total() }} users on platform</p>
</div>

<div class="rounded-3 p-4 mb-4 bg-white shadow-sm border">
    <form method="get" class="row g-2">
        <div class="col-md-6">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search users..." value="{{ request('q') }}">
        </div>
        <div class="col-md-4">
            <select name="role" class="form-select form-select-sm">
                <option value="">All Roles</option>
                <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                <option value="instructor" {{ request('role') === 'instructor' ? 'selected' : '' }}>Instructor</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
        </div>
    </form>
</div>

<div class="rounded-3 bg-white shadow-sm border overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 py-3">User</th>
                    <th class="border-0 py-3">Email</th>
                    <th class="border-0 py-3">Role</th>
                    <th class="border-0 py-3">Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td class="py-3">{{ $u->name }}</td>
                        <td class="py-3">{{ $u->email }}</td>
                        <td class="py-3"><span class="badge bg-secondary">{{ ucfirst($u->role) }}</span></td>
                        <td class="py-3 text-muted small">{{ $u->created_at->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted text-center py-4">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="p-3 border-top">{{ $users->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection

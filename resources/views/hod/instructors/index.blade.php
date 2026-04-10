@extends('layouts.hod-inner')

@push('styles')
<style>
    .hod-ins-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%); border-radius: 1rem; padding: 1.35rem 1.5rem; color: #fff; margin-bottom: 1.25rem; box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2); }
    .hod-ins-hero .hero-left { display: flex; align-items: center; gap: 1rem; }
    .hod-ins-hero .hero-icon { width: 48px; height: 48px; border-radius: 0.85rem; background: rgba(255,255,255,0.14); display: flex; align-items: center; justify-content: center; }
    .hod-ins-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .hod-ins-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; }
    .hod-ins-panel { border: 1px solid #e2e8f0; border-radius: 1rem; background: #fff; box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06); overflow: hidden; margin-bottom: 1rem; }
    .hod-ins-panel-head { padding: 1rem 1.15rem; border-bottom: 1px solid #f1f5f9; background: linear-gradient(180deg, #fafbfc 0%, #fff 100%); display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .hod-ins-search { max-width: 320px; width: 100%; }
    .hod-ins-table th { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .hod-ins-table td, .hod-ins-table th { padding: 0.85rem 1rem; vertical-align: middle; }
    .hod-ins-table tbody tr:nth-child(even) { background: #fafbfc; }
</style>
@endpush

@section('content')
<div class="hod-ins-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                <path stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" d="M5 20a7 7 0 0114 0"/>
            </svg>
        </div>
        <div>
            <h1 class="hero-title">Manage instructors</h1>
            <p class="hero-subtitle">Create and manage instructor accounts for your department.</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 rounded-3 shadow-sm">{{ session('success') }}</div>
@endif

<div class="hod-ins-panel">
    <div class="hod-ins-panel-head">
        <form method="get" action="{{ route('hod.instructors.index') }}" class="d-flex gap-2 hod-ins-search">
            <input type="search" class="form-control" name="q" placeholder="Search by name or email" value="{{ request('q') }}">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </form>
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createInstructorModal">Add instructor</button>
    </div>
    <div class="table-responsive">
        <table class="table hod-ins-table mb-0">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($instructors as $ins)
                <tr>
                    <td>{{ $ins->name }}</td>
                    <td>{{ $ins->email }}</td>
                    <td>{{ $ins->department ?: '—' }}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editInstructorModal{{ $ins->id }}">Edit</button>
                        <form method="post" action="{{ route('hod.instructors.destroy', $ins) }}" class="d-inline" onsubmit="return confirm('Delete this instructor account?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>

                <div class="modal fade" id="editInstructorModal{{ $ins->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-4 border-0 shadow">
                            <form method="post" action="{{ route('hod.instructors.update', $ins) }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-bold">Edit instructor</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body pt-0">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $ins->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $ins->email }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">New password (optional)</label>
                                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                                    </div>
                                    <div>
                                        <label class="form-label">Department</label>
                                        <input type="text" name="department" class="form-control" value="{{ auth()->user()->isAdmin() ? ($ins->department ?? '') : (auth()->user()->department ?? '') }}" {{ auth()->user()->isAdmin() ? '' : 'readonly' }} required>
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-dark">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No instructors found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($instructors->hasPages())
        <div class="p-3 border-top bg-light">{{ $instructors->links('pagination::bootstrap-5') }}</div>
    @endif
</div>

<div class="modal fade" id="createInstructorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <form method="post" action="{{ route('hod.instructors.store') }}">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Add instructor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" minlength="8" required>
                    </div>
                    <div>
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="{{ auth()->user()->department ?? '' }}" {{ auth()->user()->isAdmin() ? '' : 'readonly' }} required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.hod-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Head of Department Dashboard</h1>
    <p class="text-muted mb-0">Welcome, {{ auth()->user()->name }} · Department Overview</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2" style="background: rgba(37,99,235,0.1);">
                    <svg width="28" height="28" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold">{{ $stats['courses'] }}</h3>
                    <p class="text-muted small mb-0">Total Courses</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <h3 class="mb-0 fw-bold">{{ $stats['students'] }}</h3>
            <p class="text-muted small mb-0">Students</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <h3 class="mb-0 fw-bold">{{ $stats['instructors'] }}</h3>
            <p class="text-muted small mb-0">Instructors</p>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <h3 class="mb-0 fw-bold">{{ $stats['pending'] }}</h3>
            <p class="text-muted small mb-0">Pending Approvals</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="rounded-3 bg-white shadow-sm border p-4">
            <h5 class="fw-semibold mb-3">Instructors & Active Courses</h5>
            @forelse($instructors as $i)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                    <div>
                        <div class="fw-medium">{{ $i->name }}</div>
                        <small class="text-muted">{{ $i->email }}</small>
                    </div>
                    <span class="badge {{ $i->courses_count > 0 ? 'bg-success' : 'bg-secondary' }}">{{ $i->courses_count }} courses · {{ $i->courses_count > 0 ? 'Active' : 'Inactive' }}</span>
                </div>
            @empty
                <p class="text-muted mb-0">No instructors yet.</p>
            @endforelse
        </div>
    </div>
    <div class="col-lg-6">
        <div class="rounded-3 bg-white shadow-sm border p-4">
            <h5 class="fw-semibold mb-3">Platform Completion Rates</h5>
            @forelse($completion as $c)
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $c->title }}</span>
                        <span>{{ $c->completion_pct }}%</span>
                    </div>
                    <div class="progress" style="height:8px;">
                        <div class="progress-bar bg-primary" style="width:{{ $c->completion_pct }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">No data yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

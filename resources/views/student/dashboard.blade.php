@extends('layouts.student-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Student Dashboard</h1>
    <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2" style="background: rgba(37,99,235,0.1);">
                    <svg width="28" height="28" fill="none" stroke="#0f172a" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold">{{ $enrollments->count() }}</h3>
                    <p class="text-muted small mb-0">Enrolled courses</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2" style="background: rgba(34,197,94,0.1);">
                    <svg width="28" height="28" fill="none" stroke="#22c55e" viewBox="0 0 24 24"><path stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold">{{ $avgProgress }}%</h3>
                    <p class="text-muted small mb-0">Avg. progress</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rounded-3 bg-white shadow-sm border p-4">
    <h5 class="fw-semibold mb-3">My courses</h5>
    @forelse($enrollments as $e)
        <a href="{{ route('courses.show', $e->course) }}" class="d-block text-decoration-none text-dark py-3 border-bottom border-light">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-medium">{{ $e->course->title }}</span>
                <span class="badge bg-primary">View</span>
            </div>
        </a>
    @empty
        <p class="text-muted mb-0">You are not enrolled in any courses yet. <a href="{{ route('courses.index') }}">Browse courses</a></p>
    @endforelse
</div>
@endsection

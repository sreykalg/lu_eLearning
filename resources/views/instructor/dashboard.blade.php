@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1">Instructor Dashboard</h1>
        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('instructor.announcements.create') }}" class="btn btn-outline-danger">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            Announcement
        </a>
        <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M12 5v14M5 12h14"/></svg>
            Create course
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <h3 class="mb-0 fw-bold">{{ $stats['courses'] }}</h3>
            <p class="text-muted small mb-0">Active courses</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <h3 class="mb-0 fw-bold">{{ $stats['enrollments'] }}</h3>
            <p class="text-muted small mb-0">Total students</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <h3 class="mb-0 fw-bold">{{ $stats['lessons'] > 0 && $stats['enrollments'] > 0 ? round(min(100, ($stats['lessons'] ?? 0) / max(1, $stats['courses']) / 10 * 100)) : 0 }}%</h3>
            <p class="text-muted small mb-0">Avg completion</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="rounded-3 bg-white shadow-sm border p-4">
            <h5 class="fw-semibold mb-3">My courses</h5>
            @forelse($courses as $course)
                <a href="{{ route('instructor.courses.edit', $course) }}" class="d-block text-decoration-none text-dark py-3 border-bottom border-light">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold">{{ $course->title }}</div>
                            <small class="text-muted">{{ $course->lessons_count }} lessons · {{ $course->enrollments_count }} students</small>
                        </div>
                        <span class="badge {{ $course->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $course->is_published ? 'Active' : 'Draft' }}</span>
                    </div>
                    <div class="progress mt-2" style="height:6px;">
                        <div class="progress-bar bg-primary" style="width:50%"></div>
                    </div>
                </a>
            @empty
                <p class="text-muted mb-0">No courses yet. <a href="{{ route('instructor.courses.create') }}">Create your first course</a></p>
            @endforelse
        </div>
    </div>
</div>
@endsection

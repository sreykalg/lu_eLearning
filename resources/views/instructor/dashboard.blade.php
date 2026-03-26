@extends('layouts.instructor-inner')

@push('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
        border-radius: 1rem;
        padding: 1.25rem 1.4rem;
        color: #fff;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .hero-actions { display: flex; align-items: center; gap: 0.5rem; }
    .hero-actions .btn { border: 1px solid rgba(255,255,255,0.35); color: #fff; }
    .hero-actions .btn:hover { background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.5); }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z"/></svg>
        </div>
        <div>
            <h1 class="h3 hero-title">Instructor Dashboard</h1>
            <p class="hero-subtitle">Welcome back, {{ auth()->user()->name }}</p>
        </div>
    </div>
    <div class="hero-actions">
        <a href="{{ route('instructor.announcements.create') }}" class="btn btn-sm">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            Announcement
        </a>
        <a href="{{ route('instructor.courses.create') }}" class="btn btn-sm">
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

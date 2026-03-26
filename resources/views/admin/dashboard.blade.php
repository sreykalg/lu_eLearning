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
<div class="admin-overview">
    <div class="page-hero">
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z"/></svg>
            </div>
            <div>
                <h1 class="h3 hero-title">Admin Dashboard</h1>
                <p class="hero-subtitle">Welcome back. Here’s what’s happening across LU Academy.</p>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="d-flex flex-wrap align-items-center gap-3 text-muted" style="font-size: 0.9375rem;">
            <span><strong class="text-dark" style="color: var(--lu-deep-purple) !important;">{{ $stats['users'] }}</strong> users</span>
            <span class="opacity-50">·</span>
            <span><strong class="text-dark" style="color: var(--lu-deep-purple) !important;">{{ $stats['courses'] }}</strong> courses</span>
            <span class="opacity-50">·</span>
            <span><strong class="text-dark" style="color: var(--lu-deep-purple) !important;">{{ $stats['enrollments'] }}</strong> enrollments</span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="rounded-3 p-4 h-100" style="background: #fff; border: 1px solid rgba(45,27,78,0.08);">
                <h6 class="text-uppercase mb-3 fw-semibold" style="color: var(--lu-deep-purple); font-size: 0.75rem; letter-spacing: 0.06em;">Users by role</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(['student' => 'Students', 'instructor' => 'Instructors', 'admin' => 'Admins'] as $role => $label)
                        <span class="rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2" style="background: rgba(45,27,78,0.06); color: var(--lu-deep-purple); font-size: 0.875rem;">
                            {{ $label }}
                            <span class="fw-bold">{{ $usersByRole[$role] ?? 0 }}</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="rounded-3 p-4 h-100" style="background: #fff; border: 1px solid rgba(45,27,78,0.08);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-uppercase mb-0 fw-semibold" style="color: var(--lu-deep-purple); font-size: 0.75rem; letter-spacing: 0.06em;">Recent courses</h6>
                    <a href="{{ route('courses.index') }}" class="btn btn-sm btn-link text-decoration-none px-0" style="color: var(--lu-purple); font-size: 0.875rem;">View all</a>
                </div>
                @forelse($recentCourses as $course)
                    <a href="{{ route('courses.show', $course) }}" class="admin-course-row d-flex align-items-center gap-3 text-decoration-none text-dark py-3 border-bottom border-light{{ $loop->last ? ' border-bottom-0' : '' }}" style="transition: background 0.15s ease;">
                        <div class="rounded-2 flex-shrink-0 d-flex align-items-center justify-content-center overflow-hidden" style="width: 56px; height: 56px; background: rgba(45,27,78,0.08);">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="" class="w-100 h-100 object-fit-cover">
                            @else
                                <svg width="24" height="24" fill="none" stroke="var(--lu-deep-purple)" viewBox="0 0 24 24" style="opacity: 0.5;"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-grow-1">
                            <div class="fw-medium text-truncate">{{ $course->title }}</div>
                            <div class="text-muted small">{{ $course->instructor->name ?? '—' }}</div>
                        </div>
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-muted flex-shrink-0"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @empty
                    <p class="text-muted small mb-0 py-2">No courses yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

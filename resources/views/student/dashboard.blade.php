@extends('layouts.student-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .continue-card { background: #0f172a; color: #fff; border-radius: 0.75rem; overflow: hidden; }
    .continue-card .progress { height: 8px; background: rgba(255,255,255,0.2); }
    .continue-card .progress-bar { background: #fff; }
    .continue-card .btn-resume { background: #fff; color: #0f172a; border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; }
    .continue-card .btn-resume:hover { background: #f1f5f9; color: #0f172a; }
    .enrolled-card { background: #fff; border-radius: 0.5rem; border: 1px solid #e5e7eb; overflow: hidden; transition: box-shadow 0.2s; }
    .enrolled-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .enrolled-card-top { min-height: 60px; background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%); }
    .course-level-badge { font-size: 0.65rem; font-weight: 600; padding: 0.2rem 0.45rem; border-radius: 0.25rem; text-transform: uppercase; }
    .course-level-beginner { background: #dbeafe; color: #1e40af; }
    .course-level-intermediate { background: #fef3c7; color: #b45309; }
    .course-level-advanced { background: #fce7f3; color: #9d174d; }
    .deadline-item { padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #e5e7eb; margin-bottom: 0.5rem; background: #fff; }
    .deadline-item:hover { background: #f9fafb; }
    .student-ann-card { border-left-width: 3px !important; padding: 0.9rem !important; }
    .student-ann-title { margin-bottom: 0.85rem !important; font-size: 0.98rem; }
    .student-ann-item { padding: 0.75rem !important; }
    .student-ann-body { font-size: 0.875rem; margin-bottom: 0.45rem; }
    .student-ann-meta { font-size: 0.75rem; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
        </div>
        <div>
            <h1 class="h4 hero-title">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="hero-subtitle">@if($deadlines->count() > 0)You have {{ $deadlines->count() }} upcoming {{ Str::plural('deadline', $deadlines->count()) }}.@else Continue learning or explore new courses.@endif</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background: #0f172a; color: #fff;">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        <span class="fw-bold">{{ $totalPoints }}</span>
        <span class="small opacity-75">points</span>
    </div>
</div>

@if($announcements->isNotEmpty())
<div class="rounded-3 bg-white border p-2 mb-4 student-ann-card" style="border-left: 4px solid #dc3545 !important;">
    <h5 class="fw-semibold mb-3 student-ann-title d-flex align-items-center gap-2">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        Announcements
    </h5>
    <div class="d-flex flex-column gap-3">
        @foreach($announcements as $a)
            <div class="p-2 rounded-2 student-ann-item" style="background: #fef2f2;">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                    <span class="fw-semibold" style="font-size: 0.95rem;">{{ $a->title }}</span>
                    <span class="badge bg-danger bg-opacity-10 text-danger small">{{ $a->course->title }}</span>
                </div>
                <p class="text-secondary mb-1 student-ann-body" style="white-space: pre-wrap;">{{ Str::limit($a->body, 200) }}</p>
                <small class="text-muted student-ann-meta">{{ $a->instructor->name ?? '—' }} · {{ $a->created_at->diffForHumans() }}</small>
            </div>
        @endforeach
    </div>
</div>
@endif

@if($continueCourse)
<div class="card border-0 shadow-sm mb-4 continue-card">
    <div class="card-body p-4">
        <h5 class="text-white-50 small text-uppercase mb-2">Continue Learning</h5>
        <h4 class="fw-bold mb-1">{{ $continueCourse->course->title }}</h4>
        <p class="text-white-50 small mb-3">{{ $continueCourse->course->instructor->name ?? '—' }}</p>
        <div class="d-flex align-items-center gap-3 mb-3">
            <span class="small">{{ $continueCourse->progress_done ?? 0 }} of {{ $continueCourse->progress_total ?? 0 }} lessons</span>
            <span class="small fw-semibold">{{ $continueCourse->progress_pct ?? 0 }}%</span>
        </div>
        <div class="progress mb-3">
            <div class="progress-bar" style="width: {{ $continueCourse->progress_pct ?? 0 }}%;"></div>
        </div>
        <a href="{{ route('courses.show', $continueCourse->course) }}" class="btn-resume text-decoration-none">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            Resume
        </a>
    </div>
</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <h5 class="fw-semibold mb-3">Enrolled Courses</h5>
        <div class="row g-3">
            @forelse($enrollments as $e)
                <div class="col-md-6">
                    <a href="{{ route('courses.show', $e->course) }}" class="text-decoration-none text-dark d-block h-100">
                        <div class="enrolled-card p-3 h-100 d-flex flex-column">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <span class="course-level-badge course-level-{{ $e->course->level ?? 'beginner' }}">{{ strtoupper($e->course->level ?? 'beginner') }}</span>
                            </div>
                            <h6 class="fw-semibold mb-1">{{ Str::limit($e->course->title, 40) }}</h6>
                            <p class="text-muted small mb-2">{{ $e->course->instructor->name ?? '—' }}</p>
                            <div class="d-flex align-items-center gap-2 small mb-2">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                                <span class="text-muted">{{ $e->progress_done }}/{{ $e->progress_total }} lessons</span>
                            </div>
                            <div class="progress mt-auto" style="height: 6px; background: #e5e7eb;">
                                <div class="progress-bar" style="width: {{ $e->progress_pct }}%; background: #0f172a;"></div>
                            </div>
                            <span class="small text-muted mt-1">{{ $e->progress_pct }}%</span>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="rounded-3 bg-white shadow-sm border p-4 text-center">
                        <p class="text-muted mb-2">You are not enrolled in any courses yet.</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-sm" style="background:#0f172a;color:#fff;">Browse courses</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    <div class="col-lg-4">
        <h5 class="fw-semibold mb-3">Upcoming Deadlines</h5>
        <div class="rounded-3 bg-white border p-3">
            @forelse($deadlines as $d)
                <a href="{{ route('courses.show', $d->course) }}" class="text-decoration-none text-dark d-block deadline-item">
                    <div class="d-flex align-items-start gap-2">
                        <svg width="18" height="18" fill="none" stroke="currentColor" class="text-muted flex-shrink-0 mt-0" viewBox="0 0 24 24"><path stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <div class="min-w-0">
                            <div class="fw-medium small">{{ Str::limit($d->title, 30) }}</div>
                            <div class="text-muted small">{{ Str::limit($d->course->title, 20) }}</div>
                            <div class="text-muted small">{{ $d->due_at->format('M j, g:i A') }}</div>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-muted small mb-0">No upcoming deadlines.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

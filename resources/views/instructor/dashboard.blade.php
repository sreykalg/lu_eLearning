@extends('layouts.instructor-inner')

@push('styles')
<style>
    .ins-dash-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 1.35rem 1.5rem;
        color: #fff;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2);
    }
    .ins-dash-hero .hero-left { display: flex; align-items: center; gap: 1rem; }
    .ins-dash-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center;
    }
    .ins-dash-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .ins-dash-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.82); font-size: 0.9rem; }
    .ins-dash-hero .hero-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; }
    .ins-dash-hero .hero-actions .btn {
        border: 1px solid rgba(255,255,255,0.38);
        color: #fff;
        font-weight: 600;
        border-radius: 0.65rem;
        padding: 0.45rem 0.85rem;
    }
    .ins-dash-hero .hero-actions .btn:hover { background: rgba(255,255,255,0.12); color: #fff; border-color: rgba(255,255,255,0.55); }
    .ins-stat-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.9rem;
        background: #fff;
        padding: 1.15rem 1.2rem;
        height: 100%;
        transition: box-shadow 0.2s, transform 0.15s;
    }
    .ins-stat-card:hover {
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        transform: translateY(-2px);
    }
    .ins-stat-card .stat-icon {
        width: 44px; height: 44px; border-radius: 0.75rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .ins-stat-card .stat-icon--courses { background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); color: #1e40af; }
    .ins-stat-card .stat-icon--students { background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%); color: #166534; }
    .ins-stat-card .stat-icon--completion { background: linear-gradient(135deg, #e0e7ff 0%, #eef2ff 100%); color: #3730a3; }
    .ins-stat-card .stat-value { font-size: 1.65rem; font-weight: 800; letter-spacing: -0.03em; color: #0f172a; line-height: 1.1; }
    .ins-stat-card .stat-label { font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; margin-top: 0.15rem; }
    .ins-panel {
        border: 1px solid #e2e8f0;
        border-radius: 0.9rem;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .ins-panel-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
    }
    .ins-panel-head h2 { margin: 0; font-size: 1rem; font-weight: 700; color: #0f172a; }
    .ins-panel-head p { margin: 0.2rem 0 0; font-size: 0.8rem; color: #64748b; }
    .ins-course-row {
        display: block;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s;
    }
    .ins-course-row:last-child { border-bottom: none; }
    .ins-course-row:hover { background: #f8fafc; color: inherit; }
    .ins-course-row .top { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; }
    .ins-course-row .title { font-weight: 700; color: #0f172a; font-size: 0.95rem; }
    .ins-course-row .meta { font-size: 0.8rem; color: #64748b; margin-top: 0.25rem; }
    .ins-badge {
        font-size: 0.72rem; font-weight: 700;
        padding: 0.35rem 0.65rem; border-radius: 9999px;
        white-space: nowrap;
    }
    .ins-badge--published { background: #dcfce7; color: #166534; }
    .ins-badge--draft { background: #f1f5f9; color: #64748b; }
    .ins-progress-track {
        margin-top: 0.65rem;
        height: 9px;
        border-radius: 9999px;
        background: #f1f5f9;
        overflow: hidden;
    }
    .ins-progress-track .fill {
        height: 100%;
        border-radius: 9999px;
        background: linear-gradient(90deg, #0f172a 0%, #334155 100%);
        transition: width 0.35s ease;
    }
    .ins-progress-track .fill.fill--low { background: linear-gradient(90deg, #94a3b8 0%, #cbd5e1 100%); }
    .ins-course-row .pct-label { font-size: 0.72rem; font-weight: 700; color: #64748b; margin-top: 0.35rem; }
</style>
@endpush

@section('content')
<div class="ins-dash-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z"/></svg>
        </div>
        <div>
            <h1 class="hero-title">Instructor dashboard</h1>
            <p class="hero-subtitle">Welcome back, {{ auth()->user()->name }} · your courses and learners at a glance</p>
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
    <div class="alert alert-success border-0 rounded-3 shadow-sm mb-3">{{ session('success') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="ins-stat-card">
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon--courses">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['courses'] }}</div>
                    <div class="stat-label">Your courses</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ins-stat-card">
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon--students">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['enrollments'] }}</div>
                    <div class="stat-label">Total enrollments</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ins-stat-card">
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon--completion">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['avg_completion'] }}%</div>
                    <div class="stat-label">Avg completion</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="ins-panel">
            <div class="ins-panel-head">
                <h2>My courses</h2>
                <p>Lesson progress across all enrolled students (click a row to edit)</p>
            </div>
            @forelse($courses as $course)
                <a href="{{ route('instructor.courses.edit', $course) }}" class="ins-course-row">
                    <div class="top">
                        <div class="min-w-0">
                            <div class="title text-truncate">{{ $course->title }}</div>
                            <div class="meta">
                                {{ $course->lessons_count }} {{ Str::plural('lesson', $course->lessons_count) }}
                                ·
                                {{ $course->enrollments_count }} {{ Str::plural('student', $course->enrollments_count) }}
                            </div>
                        </div>
                        <span class="ins-badge {{ $course->is_published ? 'ins-badge--published' : 'ins-badge--draft' }}">
                            {{ $course->is_published ? 'Active' : 'Draft' }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="ins-progress-track flex-grow-1 me-2">
                            <div class="fill{{ $course->completion_pct < 20 ? ' fill--low' : '' }}" style="width: {{ min(100, max(0, $course->completion_pct)) }}%;"></div>
                        </div>
                        <span class="pct-label flex-shrink-0">{{ $course->completion_pct }}%</span>
                    </div>
                </a>
            @empty
                <div class="p-4 text-muted text-center small">
                    No courses yet. <a href="{{ route('instructor.courses.create') }}">Create your first course</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

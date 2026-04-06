@extends('layouts.hod-inner')

@push('styles')
<style>
    .hod-se-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 1.35rem 1.5rem;
        color: #fff;
        margin-bottom: 1.25rem;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2);
    }
    .hod-se-hero .hero-left { display: flex; align-items: center; gap: 1rem; }
    .hod-se-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .hod-se-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .hod-se-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; }
    .hod-se-card {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        padding: 1.15rem 1.25rem;
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .hod-se-card:hover {
        box-shadow: 0 8px 32px rgba(15, 23, 42, 0.1);
        border-color: #cbd5e1;
    }
    .hod-se-card .course-icon {
        width: 48px; height: 48px; border-radius: 0.65rem;
        background: linear-gradient(145deg, #0f172a 0%, #334155 100%);
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .hod-se-card .course-title { font-weight: 700; color: #0f172a; margin: 0 0 0.2rem; font-size: 1rem; }
    .hod-se-card .course-meta { font-size: 0.875rem; color: #64748b; margin: 0; }
    .hod-se-badge {
        font-size: 0.8125rem;
        font-weight: 600;
        padding: 0.4rem 0.85rem;
        border-radius: 9999px;
        white-space: nowrap;
    }
    .hod-se-badge--on { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .hod-se-badge--zero { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
    .hod-se-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-weight: 600;
        font-size: 0.8125rem;
        padding: 0.45rem 0.95rem;
        border-radius: 0.65rem;
        border: 1px solid #93c5fd;
        color: #1d4ed8;
        background: #fff;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
    }
    .hod-se-btn:hover { background: #eff6ff; border-color: #60a5fa; color: #1e40af; }
    .hod-se-empty {
        border: 1px dashed #cbd5e1;
        border-radius: 1rem;
        background: #f8fafc;
        text-align: center;
        padding: 2.75rem 1.5rem;
        color: #64748b;
    }
    .hod-se-empty svg { opacity: 0.45; margin-bottom: 0.75rem; }
    .hod-se-empty .hod-se-empty-title { font-weight: 600; color: #334155; margin-bottom: 0.35rem; }
</style>
@endpush

@section('content')
<div class="hod-se-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
        </div>
        <div>
            <h1 class="hero-title">Student Enrollment</h1>
            <p class="hero-subtitle">View enrolled students by course, manage enrollments, and track performance · {{ $courses->count() }} published {{ Str::plural('course', $courses->count()) }}</p>
        </div>
    </div>
</div>

<div class="d-flex flex-column gap-3">
    @forelse($courses as $c)
        <div class="hod-se-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3 min-w-0">
                    <div class="course-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="course-title">{{ $c->title }}</p>
                        <p class="course-meta">{{ $c->instructor->name ?? '—' }} · instructor</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                    @php $n = (int) $c->enrollments_count; @endphp
                    <span class="hod-se-badge {{ $n > 0 ? 'hod-se-badge--on' : 'hod-se-badge--zero' }}">{{ $n }} enrolled</span>
                    <a href="{{ route('hod.students.show', $c) }}" class="hod-se-btn">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        View Students
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="hod-se-empty">
            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="d-block mx-auto"><path stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <div class="hod-se-empty-title">No published courses yet</div>
            <p class="mb-0 small">When courses are published, they will appear here with enrollment counts.</p>
        </div>
    @endforelse
</div>
@endsection

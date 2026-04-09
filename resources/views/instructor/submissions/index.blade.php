@extends('layouts.instructor-inner')

@push('styles')
<style>
    .submissions-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 2rem;
        color: #fff;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .submissions-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 60%;
        height: 200%;
        background: radial-gradient(ellipse, rgba(255,255,255,0.06) 0%, transparent 70%);
        pointer-events: none;
    }
    .submissions-hero .icon-wrap {
        width: 52px;
        height: 52px;
        background: rgba(255,255,255,0.12);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .submissions-hero .hero-head { display: flex; align-items: center; gap: 1rem; }
    .submissions-hero .hero-copy { min-width: 0; }
    .submissions-hero h1 { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em; }
    .submissions-hero h1 { margin: 0; }
    .submissions-hero p { opacity: 0.85; font-size: 0.9375rem; margin: 0.35rem 0 0; }
    @media (max-width: 575.98px) {
        .submissions-hero .hero-head { align-items: flex-start; }
    }
    .submissions-grid { --bs-gutter-x: 1rem; --bs-gutter-y: 1rem; }
    .submissions-course-card {
        display: block;
        text-decoration: none;
        color: inherit;
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(15,23,42,0.05);
        transition: box-shadow .18s ease, border-color .18s ease, transform .18s ease;
        height: 100%;
    }
    .submissions-course-card:hover {
        color: inherit;
        border-color: #cbd5e1;
        box-shadow: 0 12px 28px rgba(15,23,42,0.09);
        transform: translateY(-2px);
    }
    .submissions-course-head {
        padding: 1rem 1.1rem 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }
    .submissions-course-title {
        margin: 0;
        font-size: 1.03rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
    }
    .submissions-course-meta {
        margin: 0.35rem 0 0;
        font-size: 0.8rem;
        color: #64748b;
    }
    .submissions-course-body {
        padding: 0.9rem 1.1rem 1rem;
    }
    .submissions-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }
    .submissions-card-foot {
        margin-top: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    .submissions-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.74rem;
        font-weight: 700;
        border-radius: 9999px;
        padding: 0.22rem 0.55rem;
        border: 1px solid #e2e8f0;
        color: #334155;
        background: #f8fafc;
    }
    .submissions-chip.total { background: #eef2ff; border-color: #c7d2fe; color: #3730a3; }
    .submissions-card-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: #0f172a;
    }
    .submissions-card-action svg { opacity: .8; }
    @media (max-width: 575.98px) {
        .submissions-card-foot { justify-content: flex-start; }
    }
    .submissions-empty-global {
        background: #fff;
        border-radius: 1rem;
        border: 1px dashed #cbd5e1;
        text-align: center;
        padding: 4rem 2rem;
    }
    .submissions-empty-global .icon-empty {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.25rem;
        background: #f1f5f9;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }
</style>
@endpush

@section('content')
<div class="submissions-hero">
    <div class="hero-head">
        <div class="icon-wrap">
            <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <div class="hero-copy">
            <h1>Submissions</h1>
            <p>View and grade assignments, quizzes & exams across your courses</p>
        </div>
    </div>
</div>

@if($courses->isEmpty())
    <div class="submissions-empty-global">
        <div class="icon-empty">
            <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <p class="text-muted mb-4" style="font-size: 1rem;">No courses yet. Create a course in Course Builder to get started.</p>
        <a href="{{ route('instructor.courses.index') }}" class="btn btn-primary">Go to Course Builder</a>
    </div>
@else
    <div class="row submissions-grid">
        @foreach($courses as $course)
            @php
                $assignmentItems = (int) ($course->assignments_count ?? 0);
                $quizItems = (int) ($course->quizzes_count ?? 0);
                $totalItems = $assignmentItems + $quizItems;
                $totalResponses = $course->assignments->sum('submissions_count') + $course->quizzes->sum('attempts_count');
            @endphp
            <div class="col-lg-6">
                <a href="{{ route('instructor.submissions.show', $course) }}" class="submissions-course-card">
                    <div class="submissions-course-head">
                        <h3 class="submissions-course-title">{{ $course->title }}</h3>
                        <p class="submissions-course-meta">Click to view assignment and quiz submission details</p>
                    </div>
                    <div class="submissions-course-body">
                        <div class="submissions-chips">
                            <span class="submissions-chip">Assignments: {{ $assignmentItems }}</span>
                            <span class="submissions-chip">Quizzes: {{ $quizItems }}</span>
                            <span class="submissions-chip total">Total items: {{ $totalItems }}</span>
                            <span class="submissions-chip">Responses: {{ $totalResponses }}</span>
                        </div>
                        <div class="submissions-card-foot">
                            <span class="submissions-card-action">
                                View details
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endif
@endsection

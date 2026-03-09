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
        margin-bottom: 1rem;
    }
    .submissions-hero h1 { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em; }
    .submissions-hero p { opacity: 0.85; font-size: 0.9375rem; margin-bottom: 0; }
    .submissions-course-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: box-shadow 0.2s, border-color 0.2s;
        margin-bottom: 1.25rem;
    }
    .submissions-course-card:hover {
        box-shadow: 0 8px 24px rgba(15,23,42,0.08);
        border-color: #cbd5e1;
    }
    .submissions-course-header {
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        font-weight: 600;
        color: #0f172a;
        font-size: 1rem;
    }
    .submissions-course-body { padding: 1rem 1.25rem; }
    .submissions-empty-state {
        padding: 2rem 1.5rem;
        text-align: center;
        color: #64748b;
        font-size: 0.9375rem;
    }
    .submissions-section-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        margin-bottom: 0.5rem;
    }
    .submissions-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }
    .submissions-item:last-child { border-bottom: none; }
    .submissions-item:hover { background: #f8fafc; }
    .submissions-item .title { font-weight: 500; color: #334155; }
    .submissions-item .badge-sm {
        font-size: 0.65rem;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-weight: 600;
        background: #e2e8f0;
        color: #475569;
    }
    .submissions-item .badge-sm.quiz-practice { background: #dbeafe; color: #1e40af; }
    .submissions-item .badge-sm.quiz-exam { background: #fee2e2; color: #b91c1c; }
    .submissions-view-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.85rem;
        font-size: 0.8125rem;
        font-weight: 600;
        background: #0f172a;
        color: #fff !important;
        border-radius: 0.5rem;
        text-decoration: none;
        transition: background 0.15s, transform 0.1s;
    }
    .submissions-view-btn:hover {
        background: #1e293b;
        color: #fff !important;
        transform: translateY(-1px);
    }
    .submissions-view-btn .count {
        background: rgba(255,255,255,0.2);
        padding: 0.1rem 0.4rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
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
    <div class="icon-wrap">
        <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    </div>
    <h1>Submissions</h1>
    <p>View and grade assignments, quizzes & exams across your courses</p>
</div>

@forelse($courses as $course)
    <div class="submissions-course-card">
        <div class="submissions-course-header">{{ $course->title }}</div>
        <div class="submissions-course-body">
            @php
                $hasAssignments = $course->assignments->isNotEmpty();
                $hasQuizzes = $course->quizzes->isNotEmpty();
            @endphp
            @if(!$hasAssignments && !$hasQuizzes)
                <p class="submissions-empty-state mb-0">No assignments or quizzes in this course.</p>
            @else
                @if($hasAssignments)
                    <div class="submissions-section-label">Assignments</div>
                    <div class="mb-4">
                        @foreach($course->assignments as $a)
                            <div class="submissions-item">
                                <span class="title">{{ $a->title }}</span>
                                <a href="{{ route('instructor.assignments.submissions', [$course, $a]) }}" class="submissions-view-btn">
                                    View <span class="count">{{ $a->submissions_count ?? 0 }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
                @if($hasQuizzes)
                    <div class="submissions-section-label">Quizzes & Exams</div>
                    <div>
                        @foreach($course->quizzes as $q)
                            <div class="submissions-item">
                                <span>
                                    <span class="title">{{ $q->title }}</span>
                                    <span class="badge-sm quiz-{{ strtolower($q->type) }} ms-2">{{ ucfirst($q->type) }}</span>
                                </span>
                                <a href="{{ route('instructor.quizzes.attempts', [$course, $q]) }}" class="submissions-view-btn">
                                    View attempts <span class="count">{{ $q->attempts_count ?? 0 }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
@empty
    <div class="submissions-empty-global">
        <div class="icon-empty">
            <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <p class="text-muted mb-4" style="font-size: 1rem;">No courses yet. Create a course in Course Builder to get started.</p>
        <a href="{{ route('instructor.courses.index') }}" class="btn btn-primary">Go to Course Builder</a>
    </div>
@endforelse
@endsection

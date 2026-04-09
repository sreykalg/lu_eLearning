@extends('layouts.instructor-inner')

@push('styles')
<style>
    .submissions-detail-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 1.5rem;
        color: #fff;
        margin-bottom: 1.25rem;
    }
    .submissions-detail-back {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: rgba(255, 255, 255, 0.86);
        text-decoration: none;
        font-size: 0.84rem;
        margin-bottom: 0.7rem;
    }
    .submissions-detail-back:hover { color: #fff; }
    .submissions-detail-title {
        margin: 0;
        font-size: clamp(1.25rem, 1.1rem + 0.6vw, 1.7rem);
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .submissions-detail-sub {
        margin: 0.35rem 0 0;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.82);
    }
    .submissions-course-card {
        background: #fff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        overflow: hidden;
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
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        margin-bottom: 0.5rem;
    }
    .submissions-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
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
        white-space: nowrap;
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
</style>
@endpush

@section('content')
<div class="submissions-detail-hero">
    <a href="{{ route('instructor.submissions') }}" class="submissions-detail-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to submissions
    </a>
    <h1 class="submissions-detail-title">{{ $course->title }}</h1>
    <p class="submissions-detail-sub">Assignments and quizzes for this course.</p>
</div>

<div class="submissions-course-card">
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
@endsection

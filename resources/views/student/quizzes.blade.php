@extends('layouts.student-inner')

@push('styles')
<style>
    .quiz-card { transition: box-shadow 0.2s; }
    .quiz-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .quiz-type-pill { font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 0.25rem; }
    .quiz-type-practice { background: #dbeafe; color: #1e40af; }
    .quiz-type-midterm { background: #fef3c7; color: #b45309; }
    .quiz-type-final { background: #fce7f3; color: #9d174d; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Quizzes & Exams</h1>
    @php
        $upcoming = $quizzes->filter(fn($q) => !$attempts->has($q->id));
        $completed = $quizzes->filter(fn($q) => $attempts->has($q->id));
    @endphp
    <p class="text-muted mb-0">{{ $upcoming->count() }} upcoming · {{ $completed->count() }} completed</p>
</div>

@if($upcoming->isNotEmpty())
    <div class="mb-4">
        <h5 class="d-flex align-items-center gap-2 mb-3 fw-semibold">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Upcoming
        </h5>
        <div class="d-flex flex-column gap-3">
            @foreach($upcoming as $q)
                <a href="{{ route('student.quizzes.show', [$q->course, $q]) }}" class="text-decoration-none text-dark">
                    <div class="card border-0 shadow-sm quiz-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                        <svg width="20" height="20" fill="none" stroke="#64748b" viewBox="0 0 24 24" class="flex-shrink-0"><path stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <h6 class="mb-0 fw-semibold">{{ $q->title }}</h6>
                                        <span class="quiz-type-pill quiz-type-{{ $q->type ?? 'practice' }}">{{ ucfirst($q->type ?? 'Quiz') }}</span>
                                    </div>
                                    <p class="text-muted small mb-2">{{ $q->course->title }}</p>
                                    <div class="d-flex align-items-center gap-3 small text-muted flex-wrap">
                                        @if($q->duration_minutes)
                                            <span><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="d-inline-block align-text-bottom me-1"><path stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $q->duration_minutes }} min</span>
                                        @endif
                                        <span><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="d-inline-block align-text-bottom me-1"><path stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $q->questions_count ?? 0 }} questions</span>
                                        @if($q->total_points > 0)
                                            <span>{{ $q->total_points }} pts</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="btn btn-outline-secondary btn-sm">View Details</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif

@if($completed->isNotEmpty())
    <div>
        <h5 class="d-flex align-items-center gap-2 mb-3 fw-semibold">
            <svg width="20" height="20" fill="#16a34a" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            Completed
        </h5>
        <div class="d-flex flex-column gap-3">
            @foreach($completed as $q)
                @php $attempt = $attempts->get($q->id); @endphp
                <a href="{{ route('student.quizzes.show', [$q->course, $q]) }}" class="text-decoration-none text-dark">
                    <div class="card border-0 shadow-sm quiz-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: #dcfce7;">
                                            <svg width="14" height="14" fill="#16a34a" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                        </span>
                                        <span class="badge bg-success">Completed</span>
                                        <h6 class="mb-0 fw-semibold">{{ $q->title }}</h6>
                                        <span class="quiz-type-pill quiz-type-{{ $q->type ?? 'practice' }}">{{ ucfirst($q->type ?? 'Quiz') }}</span>
                                    </div>
                                    <p class="text-muted small mb-2">{{ $q->course->title }}</p>
                                    @if($attempt)
                                        <span class="small fw-medium">Score: {{ $attempt->score }}/{{ $attempt->total_points }} @if($attempt->passed !== null) ({{ $attempt->passed ? 'Passed' : 'Not passed' }}) @endif</span>
                                    @endif
                                </div>
                                <span class="btn btn-outline-secondary btn-sm">View Details</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif

@if($quizzes->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <svg class="mb-3 text-muted" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-muted mb-0">No quizzes or exams yet.</p>
        </div>
    </div>
@endif
@endsection

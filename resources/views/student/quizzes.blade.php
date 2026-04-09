@extends('layouts.student-inner')

@push('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
        border-radius: 1rem;
        padding: 1.25rem 1.4rem;
        color: #fff;
        margin-bottom: 1rem;
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.18);
    }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.015em; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .quiz-section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.8rem;
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: -0.015em;
        color: #0f172a;
    }
    .quiz-card { transition: box-shadow 0.2s; }
    .quiz-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .quiz-type-pill { font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 0.25rem; }
    .quiz-type-practice { background: #dbeafe; color: #1e40af; }
    .quiz-type-midterm { background: #fef3c7; color: #b45309; }
    .quiz-type-final { background: #fce7f3; color: #9d174d; }
    .upcoming-card { border: 1px solid #e2e8f0 !important; border-radius: 0.95rem !important; background: #fff; overflow: hidden; }
    .upcoming-card .card-body {
        padding: 0.95rem 1rem !important;
    }
    .upcoming-card .upcoming-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.2rem;
    }
    .upcoming-card .upcoming-course {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
    .upcoming-card .upcoming-meta {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        flex-wrap: wrap;
        color: #475569;
        font-size: 0.82rem;
        font-weight: 500;
    }
    .upcoming-card .upcoming-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 9999px;
        padding: 0.18rem 0.5rem;
    }
    .upcoming-card .upcoming-cta {
        border-radius: 0.55rem;
        border-color: #cbd5e1;
        color: #0f172a;
        font-weight: 600;
        padding: 0.35rem 0.65rem;
    }
    .upcoming-card .upcoming-cta:hover {
        background: #0f172a;
        border-color: #0f172a;
        color: #fff;
    }
    .completed-card {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.95rem !important;
        background: #fff;
    }
    .completed-score {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 9999px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 0.2rem 0.5rem;
        font-size: 0.79rem;
        color: #334155;
    }
</style>
@endpush

@section('content')
@php
    $upcoming = $quizzes->filter(fn($q) => !$attempts->has($q->id));
    $completed = $quizzes->filter(fn($q) => $attempts->has($q->id));
@endphp
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
        </div>
        <div>
            <h1 class="h3 hero-title">Quizzes & Exams</h1>
            <p class="hero-subtitle">{{ $upcoming->count() }} upcoming · {{ $completed->count() }} completed</p>
        </div>
    </div>
</div>

@if($upcoming->isNotEmpty())
    <div class="mb-4">
        <h5 class="quiz-section-title">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Upcoming
        </h5>
        <div class="d-flex flex-column gap-3">
            @foreach($upcoming as $q)
                <a href="{{ route('student.quizzes.show', [$q->course, $q]) }}" class="text-decoration-none text-dark">
                    <div class="card border-0 shadow-sm quiz-card upcoming-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                        <svg width="20" height="20" fill="none" stroke="#64748b" viewBox="0 0 24 24" class="flex-shrink-0"><path stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <h6 class="upcoming-title">{{ $q->title }}</h6>
                                        <span class="quiz-type-pill quiz-type-{{ $q->type ?? 'practice' }}">{{ ucfirst($q->type ?? 'Quiz') }}</span>
                                    </div>
                                    <p class="upcoming-course">{{ $q->course->title }}</p>
                                    <div class="upcoming-meta">
                                        @if($q->duration_minutes)
                                            <span class="upcoming-meta-item"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $q->duration_minutes }} min</span>
                                        @endif
                                        <span class="upcoming-meta-item"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $q->questions_count ?? 0 }} questions</span>
                                        @if($q->total_points > 0)
                                            <span class="upcoming-meta-item">{{ $q->total_points }} pts</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="btn btn-outline-secondary btn-sm upcoming-cta">View Details</span>
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
        <h5 class="quiz-section-title">
            <svg width="20" height="20" fill="#16a34a" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
            Completed
        </h5>
        <div class="d-flex flex-column gap-3">
            @foreach($completed as $q)
                @php $attempt = $attempts->get($q->id); @endphp
                <a href="{{ route('student.quizzes.show', [$q->course, $q]) }}" class="text-decoration-none text-dark">
                    <div class="card border-0 shadow-sm quiz-card completed-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: #dcfce7;">
                                            <svg width="14" height="14" fill="#16a34a" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                        </span>
                                        <h6 class="mb-0 fw-semibold">{{ $q->title }}</h6>
                                        <span class="quiz-type-pill quiz-type-{{ $q->type ?? 'practice' }}">{{ ucfirst($q->type ?? 'Quiz') }}</span>
                                    </div>
                                    <p class="text-muted small mb-2">{{ $q->course->title }}</p>
                                    @if($attempt)
                                        <span class="completed-score">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                            {{ $attempt->score }}/{{ $attempt->total_points }}
                                            @if($attempt->passed !== null)
                                                · {{ $attempt->passed ? 'Passed' : 'Not passed' }}
                                            @endif
                                        </span>
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

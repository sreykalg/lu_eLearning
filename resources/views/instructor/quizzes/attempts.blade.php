@extends('layouts.instructor-inner')

@php
    $attemptCount = $attempts->count();
@endphp

@push('styles')
<style>
    .qatt-back {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.84rem;
        font-weight: 700;
        text-decoration: none;
    }
    .qatt-hero .qatt-back {
        align-self: flex-start;
        color: rgba(255, 255, 255, 0.82);
    }
    .qatt-hero .qatt-back:hover {
        color: #fff;
    }
    .qatt-breadcrumb {
        font-size: 0.8125rem;
        margin-bottom: 1rem;
    }
    .qatt-breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }
    .qatt-breadcrumb a:hover { color: #0f172a; }
    .qatt-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 1.35rem 1.5rem;
        color: #fff;
        margin-bottom: 1.25rem;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0.85rem;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2);
    }
    .qatt-hero__row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .qatt-hero .hero-left { display: flex; align-items: flex-start; gap: 1rem; }
    .qatt-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .qatt-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .qatt-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; max-width: 42rem; line-height: 1.45; }
    .qatt-hero .hero-meta { text-align: right; font-size: 0.875rem; color: rgba(255,255,255,0.9); }
    .qatt-hero .hero-meta .label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        opacity: 0.75;
        display: block;
        margin-bottom: 0.25rem;
    }
    .qatt-type-pill {
        display: inline-block;
        margin-top: 0.35rem;
        padding: 0.2rem 0.55rem;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        border-radius: 9999px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
    }
    .qatt-panel {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        margin-bottom: 1.25rem;
        min-height: 220px;
        display: flex;
        flex-direction: column;
    }
    .qatt-panel__head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .qatt-panel__head h2 {
        margin: 0;
        font-size: 0.8125rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
    }
    .qatt-panel__head .meta { font-size: 0.8rem; color: #64748b; }
    .qatt-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.12s;
    }
    .qatt-row:nth-child(even) { background: #fafbfc; }
    .qatt-row:last-child { border-bottom: none; }
    .qatt-row:hover { background: #f1f5f9; }
    .qatt-student {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 0;
    }
    .qatt-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 800;
        color: #fff;
        background: linear-gradient(145deg, #0f172a 0%, #334155 100%);
        flex-shrink: 0;
    }
    .qatt-student .name { font-weight: 700; color: #0f172a; font-size: 0.95rem; }
    .qatt-student .time { font-size: 0.8125rem; color: #64748b; margin-top: 0.1rem; }
    .qatt-score {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .qatt-score-num {
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
        font-variant-numeric: tabular-nums;
    }
    .qatt-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.35rem 0.65rem;
        border-radius: 9999px;
        letter-spacing: 0.02em;
    }
    .qatt-badge--pass { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .qatt-badge--fail { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
    .qatt-badge--neutral { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
    .qatt-empty {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2.75rem 1.5rem;
        text-align: center;
        color: #64748b;
    }
    .qatt-empty svg { opacity: 0.35; margin-bottom: 0.85rem; color: #94a3b8; }
    .qatt-footer { margin-top: 0.5rem; }
</style>
@endpush

@section('content')
<div class="qatt-breadcrumb">
    <a href="{{ route('instructor.submissions') }}">Submissions</a>
    <span class="text-muted"> / </span>
    <span class="text-dark fw-semibold">Quiz attempts</span>
</div>

<div class="qatt-hero">
    <a href="{{ route('instructor.submissions.show', $course) }}" class="qatt-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Course
    </a>
    <div class="qatt-hero__row">
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <div>
                <h1 class="hero-title">Quiz attempts · {{ $quiz->title }}</h1>
                <p class="hero-subtitle">{{ $course->title }}</p>
                <span class="qatt-type-pill">{{ ucfirst(str_replace('_', ' ', $quiz->type)) }}</span>
            </div>
        </div>
        <div class="hero-meta">
            <span class="label">Attempts recorded</span>
            <span class="fw-bold">{{ $attemptCount }} {{ Str::plural('attempt', $attemptCount) }}</span>
        </div>
    </div>
</div>

<div class="qatt-panel">
    <div class="qatt-panel__head">
        <h2>Results</h2>
        <span class="meta">Newest first</span>
    </div>
    @if($attemptCount === 0)
        <div class="qatt-empty">
            <svg width="56" height="56" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="fw-semibold text-secondary mb-1">No attempts yet</p>
            <p class="small mb-0" style="max-width: 22rem;">When students submit this quiz, each attempt will show here with score and pass/fail when applicable.</p>
        </div>
    @else
        <div>
            @foreach($attempts as $a)
                <div class="qatt-row">
                    <div class="qatt-student">
                        @php
                            $name = $a->user->name ?? 'Unknown';
                            $parts = array_filter(explode(' ', $name));
                            $initials = count($parts) >= 2
                                ? Str::upper(mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1))
                                : Str::upper(mb_substr($name, 0, 2));
                        @endphp
                        <div class="qatt-avatar">{{ $initials }}</div>
                        <div class="min-w-0">
                            <div class="name text-truncate">{{ $name }}</div>
                            <div class="time">{{ $a->submitted_at?->format('M j, Y g:i A') ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="qatt-score">
                        <span class="qatt-score-num">{{ $a->score }}/{{ $a->total_points }}</span>
                        @if($a->passed === true)
                            <span class="qatt-badge qatt-badge--pass">Passed</span>
                        @elseif($a->passed === false)
                            <span class="qatt-badge qatt-badge--fail">Not passed</span>
                        @else
                            <span class="qatt-badge qatt-badge--neutral">Scored</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection

@extends('layouts.instructor-inner')

@push('styles')
<style>
    .asub-back {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.84rem;
        font-weight: 700;
        text-decoration: none;
    }
    .asub-hero .asub-back {
        align-self: flex-start;
        color: rgba(255, 255, 255, 0.82);
    }
    .asub-hero .asub-back:hover {
        color: #fff;
    }
    .asub-breadcrumb {
        font-size: 0.8125rem;
        margin-bottom: 1rem;
    }
    .asub-breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }
    .asub-breadcrumb a:hover { color: #0f172a; }
    .asub-hero {
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
    .asub-hero__row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .asub-hero .hero-left { display: flex; align-items: flex-start; gap: 1rem; }
    .asub-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .asub-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .asub-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; max-width: 40rem; }
    .asub-hero .hero-meta {
        text-align: right;
        font-size: 0.875rem;
        color: rgba(255,255,255,0.88);
    }
    .asub-hero .hero-meta .label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        opacity: 0.75;
        display: block;
        margin-bottom: 0.25rem;
    }
    .asub-panel {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .asub-panel__head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
    }
    .asub-panel__head h2 {
        margin: 0;
        font-size: 0.8125rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
    }
    .asub-panel__body { padding: 1rem 1.25rem 1.25rem; }
    .asub-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.9rem;
        background: #fff;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .asub-card:last-child { margin-bottom: 0; }
    .asub-card__head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        background: #fafbfc;
    }
    .asub-student {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .asub-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: 800;
        color: #fff;
        background: linear-gradient(145deg, #0f172a 0%, #334155 100%);
        flex-shrink: 0;
    }
    .asub-student .name { font-weight: 700; color: #0f172a; font-size: 1rem; }
    .asub-student .time { font-size: 0.8125rem; color: #64748b; margin-top: 0.15rem; }
    .asub-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 0.85rem;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 9999px;
        letter-spacing: 0.02em;
    }
    .asub-badge--graded { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .asub-badge--pending { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .asub-card__body { padding: 1.15rem 1.25rem 1.25rem; }
    .asub-file {
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem;
        background: #fafbfc;
        padding: 1rem 1.15rem;
        margin-bottom: 1rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .asub-file-info {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        min-width: 0;
        flex: 1 1 200px;
    }
    .asub-file-icon {
        width: 44px;
        height: 44px;
        border-radius: 0.65rem;
        background: linear-gradient(145deg, #dbeafe 0%, #eff6ff 100%);
        color: #1d4ed8;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid #bfdbfe;
    }
    .asub-file-meta { min-width: 0; }
    .asub-file-label {
        display: block;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.25rem;
    }
    .asub-file-name {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        word-break: break-word;
        line-height: 1.35;
    }
    @media (min-width: 480px) {
        .asub-file-name {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    }
    .asub-download-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        flex-shrink: 0;
        padding: 0.55rem 1.15rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #0f172a;
        border-radius: 0.65rem;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.15);
        transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
    }
    .asub-download-btn:hover {
        color: #fff;
        background: #0f172a;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.22);
        transform: translateY(-1px);
    }
    .asub-download-btn:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.25);
    }
    .asub-download-btn svg {
        flex-shrink: 0;
    }
    .asub-note {
        border-radius: 0.75rem;
        padding: 0.9rem 1rem;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        line-height: 1.5;
    }
    .asub-note strong { color: #0f172a; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em; }
    .asub-grade {
        margin-top: 0.6rem;
        padding-top: 0.7rem;
        border-top: 1px solid #f1f5f9;
    }
    .asub-grade .form-label {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.35rem;
    }
    .asub-grade-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.6rem;
    }
    .asub-grade-title {
        margin: 0;
        font-size: 0.74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
    }
    .asub-grade-max {
        font-size: 0.76rem;
        font-weight: 600;
        color: #475569;
    }
    .asub-grade .form-control,
    .asub-grade .form-label {
        font-size: 0.875rem;
    }
    .asub-grade .form-control {
        border-radius: 0.6rem;
        border-color: #cbd5e1;
        background: #fff;
        min-height: 42px;
    }
    .asub-grade .form-control:focus {
        border-color: #0f172a;
        box-shadow: 0 0 0 0.2rem rgba(15, 23, 42, 0.08);
    }
    .asub-grade .btn-submit {
        border-radius: 0.6rem;
        font-weight: 700;
        padding: 0.5rem 1.15rem;
    }
    .asub-empty {
        text-align: center;
        padding: 2.5rem 1.5rem;
        color: #64748b;
    }
    .asub-empty svg { opacity: 0.35; margin-bottom: 0.75rem; color: #94a3b8; }
    .asub-footer-back {
        margin-top: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="asub-breadcrumb">
    <a href="{{ route('instructor.courses.edit', $course) }}">Course Builder</a>
    <span class="text-muted"> / </span>
    <a href="{{ route('instructor.assignments.edit', [$course, $assignment]) }}">Assignment</a>
    <span class="text-muted"> / </span>
    <span class="text-dark fw-semibold">Submissions</span>
</div>

<div class="asub-hero">
    <a href="{{ route('instructor.assignments.edit', [$course, $assignment]) }}" class="asub-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to assignment
    </a>
    <div class="asub-hero__row">
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h1 class="hero-title">Submissions · {{ $assignment->title }}</h1>
                <p class="hero-subtitle">Review files, notes, and grades for {{ $course->title }}.</p>
            </div>
        </div>
        <div class="hero-meta">
            <span class="label">Total</span>
            <span class="fw-bold">{{ $submissions->count() }} {{ Str::plural('submission', $submissions->count()) }}</span>
            <span class="d-block mt-2 small opacity-90">Max score {{ $assignment->max_score }}</span>
        </div>
    </div>
</div>

<div class="asub-panel">
    <div class="asub-panel__head">
        <h2>Student work</h2>
    </div>
    <div class="asub-panel__body">
        @forelse($submissions as $sub)
            <div class="asub-card">
                <div class="asub-card__head">
                    <div class="asub-student">
                        @php
                            $name = $sub->user->name ?? 'Unknown';
                            $parts = array_filter(explode(' ', $name));
                            $initials = count($parts) >= 2
                                ? Str::upper(mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1))
                                : Str::upper(mb_substr($name, 0, 2));
                        @endphp
                        <div class="asub-avatar">{{ $initials }}</div>
                        <div>
                            <div class="name">{{ $name }}</div>
                            <div class="time">Submitted {{ $sub->submitted_at?->format('M j, Y g:i A') ?? '—' }}</div>
                        </div>
                    </div>
                    @if($sub->isGraded())
                        <span class="asub-badge asub-badge--graded">Graded {{ $sub->score }}/{{ $assignment->max_score }}</span>
                    @else
                        <span class="asub-badge asub-badge--pending">Needs grading</span>
                    @endif
                </div>
                <div class="asub-card__body">
                    @if($sub->file_path)
                        @php $basename = basename($sub->file_path); @endphp
                        <div class="asub-file">
                            <div class="asub-file-info">
                                <div class="asub-file-icon" aria-hidden="true">
                                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="asub-file-meta">
                                    <span class="asub-file-label">Submitted file</span>
                                    <span class="asub-file-name" title="{{ $basename }}">{{ $basename }}</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $sub->file_path) }}"
                               class="asub-download-btn"
                               download="{{ e($basename) }}">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1.25c0 1.519 1.231 2.75 2.75 2.75h10.5A2.75 2.75 0 0020 17.25V16M16 12l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download file
                            </a>
                        </div>
                    @endif
                    @if($sub->content)
                        <div class="asub-note">
                            <strong class="d-block mb-2">Student notes</strong>
                            {!! nl2br(e($sub->content)) !!}
                        </div>
                    @endif

                    <form action="{{ route('instructor.assignments.submissions.grade', [$course, $assignment, $sub]) }}" method="post" class="asub-grade">
                        @csrf
                        @method('PUT')
                        <div class="asub-grade-head">
                            <h3 class="asub-grade-title">Grade submission</h3>
                            <span class="asub-grade-max">Max {{ $assignment->max_score }}</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label" for="score-{{ $sub->id }}">Score</label>
                                <input id="score-{{ $sub->id }}" type="number" name="score" class="form-control" min="0" max="{{ $assignment->max_score }}" value="{{ old('score', $sub->score ?? 0) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="feedback-{{ $sub->id }}">Feedback to student</label>
                                <textarea id="feedback-{{ $sub->id }}" name="feedback" class="form-control" rows="3" placeholder="Optional feedback visible to the student">{{ old('feedback', $sub->feedback) }}</textarea>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-dark btn-submit">
                                    {{ $sub->isGraded() ? 'Update grade' : 'Save grade' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="asub-empty">
                <svg width="52" height="52" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="fw-semibold text-secondary mb-1">No submissions yet</p>
                <p class="small mb-0">When students submit this assignment, they will appear here.</p>
            </div>
        @endforelse
    </div>
</div>

<div class="asub-footer-back">
    <a href="{{ route('instructor.submissions') }}" class="btn btn-outline-secondary rounded-3 px-4">Back to submissions</a>
</div>
@endsection

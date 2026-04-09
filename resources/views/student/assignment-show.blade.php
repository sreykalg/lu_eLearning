@extends('layouts.student-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-row { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .page-hero .back-link {
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        margin-bottom: 0.65rem;
    }
    .page-hero .back-link:hover { color: #fff; }
    .assignment-shell { background: #fff; border: 1px solid #e2e8f0; border-radius: 0.95rem; overflow: hidden; box-shadow: 0 4px 20px rgba(15,23,42,.06); }
    .assignment-intro { padding: 1rem 1.2rem; border-bottom: 1px solid #f1f5f9; background: linear-gradient(180deg, #fff 0%, #fafbfc 100%); }
    .assignment-content { padding: 0.8rem 1.2rem 1.1rem; }
    .assignment-meta { color: #64748b; font-size: 0.85rem; }
    .assignment-section { padding: 0.85rem 0.1rem; margin: 0; border-bottom: 1px solid #f1f5f9; background: transparent; }
    .assignment-section:last-of-type { border-bottom: none; }
    .assignment-section-title { font-size: 0.92rem; font-weight: 800; color: #0f172a; margin-bottom: 0.4rem; letter-spacing: -0.01em; }
    .assignment-note { background: transparent; border: 0; }
    .assignment-submission-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; margin-bottom: 0.85rem; }
    .assignment-download-btn {
        background: #0f172a;
        border-color: #0f172a;
        color: #fff;
    }
    .assignment-download-btn:hover {
        background: #1e293b;
        border-color: #1e293b;
        color: #fff;
    }
    .assignment-feedback {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        border-radius: 0.7rem;
        padding: 0.75rem 0.8rem;
    }
    .assignment-actions { border-top: 1px solid #e5e7eb; padding-top: 0.85rem; margin-top: 0.85rem; display: flex; gap: 0.6rem; flex-wrap: wrap; }
    .assignment-primary-btn { background: #0f172a; color: #fff; border: none; border-radius: 0.55rem; padding: 0.5rem 0.95rem; font-weight: 600; }
    .assignment-primary-btn:hover { background: #1e293b; color: #fff; }
    .assignment-status-chip { font-size: 0.76rem; font-weight: 700; padding: 0.22rem 0.55rem; border-radius: 9999px; }
    .assignment-resource-list { display: grid; gap: 0.45rem; }
    .assignment-resource-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        width: fit-content;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        color: #0f172a;
        border-radius: 9999px;
        padding: 0.3rem 0.62rem;
        font-size: 0.8rem;
        background: #fff;
    }
    .assignment-resource-link:hover { color: #1e293b; border-color: #cbd5e1; background: #f8fafc; }
    @media (max-width: 991.98px) {
        .assignment-submission-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <a href="{{ route('student.assignments') }}" class="back-link">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Assignments
    </a>
    <div class="hero-row">
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <h1 class="h3 hero-title">{{ $assignment->title }}</h1>
                <p class="hero-subtitle">{{ $assignment->course->title }}</p>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="assignment-shell mb-4">
    <div class="assignment-intro">
        <div class="d-flex align-items-center gap-2 mb-3">
            @if($submission)
                @if($submission->isGraded())
                    <span class="badge bg-success assignment-status-chip">Graded</span>
                    <span class="fw-semibold">Score: {{ $submission->score }}/{{ $assignment->max_score }}</span>
                @else
                    <span class="badge bg-info assignment-status-chip">Submitted</span>
                @endif
            @else
                @if(!$assignment->canSubmit())
                    <span class="badge bg-secondary assignment-status-chip">Closed</span>
                @else
                    <span class="badge bg-warning text-dark assignment-status-chip">Pending</span>
                @endif
            @endif
        </div>
        <div class="assignment-meta">
            Due: {{ $assignment->due_at?->format('M j, g:i A') ?? '—' }} · Max: {{ $assignment->max_score }} pts
        </div>
    </div>
    <div class="assignment-content">
        @if($assignment->instructions)
            <div class="assignment-section">
                <h6 class="assignment-section-title">Instructions</h6>
                <p class="text-muted mb-0">{!! nl2br(e($assignment->instructions)) !!}</p>
            </div>
        @endif
        @if($assignment->attachments->isNotEmpty())
            <div class="assignment-section">
                <h6 class="assignment-section-title">Assignment files</h6>
                <div class="assignment-resource-list">
                    @foreach($assignment->attachments as $file)
                        <a href="{{ asset('storage/' . $file->path) }}" target="_blank" class="assignment-resource-link">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ $file->original_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
        @if($submission)
            @if($submission->file_path || $submission->feedback)
                <div class="assignment-submission-grid">
                    @if($submission->file_path)
                        <div class="assignment-section">
                            <h6 class="assignment-section-title">Your submitted file</h6>
                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="btn btn-sm assignment-download-btn">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Download submitted file
                            </a>
                        </div>
                    @else
                        <div class="assignment-section"></div>
                    @endif
                    @if($submission->feedback)
                        <div class="assignment-section">
                            <h6 class="assignment-section-title">Feedback</h6>
                            <div class="assignment-feedback">
                                <p class="mb-0">{!! nl2br(e($submission->feedback)) !!}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            @if($submission->content)
                <div class="assignment-section assignment-note">
                    <h6 class="assignment-section-title">Your notes</h6>
                    <p class="text-muted mb-0">{!! nl2br(e($submission->content)) !!}</p>
                </div>
            @endif
            <div class="assignment-actions">
                <a href="{{ route('courses.show', $assignment->course) }}" class="btn btn-outline-secondary btn-sm">Back to course</a>
            </div>
        @elseif(!$assignment->canSubmit())
            <div class="alert alert-secondary mb-0">
                <strong>Submission closed.</strong> The due date has passed and late submissions are not allowed for this assignment.
            </div>
            <div class="assignment-actions">
                <a href="{{ route('student.assignments') }}" class="btn btn-outline-secondary btn-sm">Back to assignments</a>
            </div>
        @else
            @if($assignment->isPastDue())
                <div class="alert alert-warning mb-3">
                    <strong>Past due.</strong> Late submissions are allowed for this assignment.
                </div>
            @endif
            <form action="{{ route('student.assignments.submit', [$course, $assignment]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Your submission (optional)</label>
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="4" placeholder="Add notes or description (optional)...">{{ old('content') }}</textarea>
                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Attach file <span class="text-danger">*</span></label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required accept=".pdf,.doc,.docx,.txt,.zip,.rar,image/*">
                    <div class="form-text">A file attachment is required to submit. Accepted: PDF, Word, images, or ZIP.</div>
                    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="assignment-actions">
                    <button type="submit" class="btn assignment-primary-btn">Submit assignment</button>
                    <a href="{{ route('student.assignments') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection

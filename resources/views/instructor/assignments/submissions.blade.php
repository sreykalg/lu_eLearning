@extends('layouts.instructor-inner')

@push('styles')
<style>
    .sub-page-header { margin-bottom: 1.25rem; }
    .sub-summary { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; border-radius: 0.85rem; padding: 1rem 1.25rem; margin-bottom: 1rem; display: flex; justify-content: space-between; gap: 0.75rem; align-items: center; }
    .sub-summary .label { font-size: 0.8rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.06em; }
    .sub-card { border: 1px solid #e2e8f0; border-radius: 0.85rem; background: #fff; padding: 1rem; margin-bottom: 0.9rem; }
    .sub-meta { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 0.75rem; flex-wrap: wrap; }
    .sub-user { font-weight: 700; color: #0f172a; }
    .sub-time { color: #64748b; font-size: 0.83rem; }
    .sub-file { border: 1px dashed #cbd5e1; border-radius: 0.7rem; background: #f8fafc; padding: 0.7rem; margin-bottom: 0.7rem; }
    .sub-note, .sub-feedback { border-radius: 0.7rem; padding: 0.75rem; font-size: 0.88rem; margin-bottom: 0.7rem; }
    .sub-note { background: #f8fafc; border: 1px solid #e2e8f0; color: #334155; }
    .sub-feedback { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
    .sub-grade-wrap { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 0.75rem; }
    .sub-grade-wrap .form-label { font-size: 0.76rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
</style>
@endpush

@section('content')
<div class="sub-page-header">
    <a href="{{ route('instructor.assignments.edit', [$course, $assignment]) }}" class="text-decoration-none small text-muted d-inline-block mb-1">&larr; Edit assignment</a>
    <h1 class="h3 fw-bold mb-1">Submissions: {{ $assignment->title }}</h1>
    <p class="text-muted small mb-0">{{ $course->title }}</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="sub-summary">
    <div>
        <div class="label">Total Submissions</div>
        <div class="fw-bold">{{ $submissions->count() }} submission(s)</div>
    </div>
    <div class="small opacity-75">Max Score: {{ $assignment->max_score }}</div>
</div>

<div class="card border-0 shadow-sm p-3">
    <div class="card-body p-2 p-md-3">
        @forelse($submissions as $sub)
            <div class="sub-card">
                <div class="sub-meta">
                    <div>
                        <div class="sub-user">{{ $sub->user->name ?? 'Unknown' }}</div>
                        <div class="sub-time">Submitted {{ $sub->submitted_at?->format('M j, Y g:i A') ?? '—' }}</div>
                    </div>
                    @if($sub->isGraded())
                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Graded: {{ $sub->score }}/{{ $assignment->max_score }}</span>
                    @else
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Pending</span>
                    @endif
                </div>
                @if($sub->file_path)
                    <div class="sub-file">
                        <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download submitted file
                        </a>
                        <span class="text-muted small ms-2">{{ basename($sub->file_path) }}</span>
                    </div>
                @endif
                @if($sub->content)
                    <div class="sub-note">
                        <strong>Notes:</strong><br>
                        {!! nl2br(e($sub->content)) !!}
                    </div>
                @endif
                @if($sub->isGraded() && $sub->feedback)
                    <div class="sub-feedback">
                        <strong>Your feedback:</strong><br>
                        {!! nl2br(e($sub->feedback)) !!}
                    </div>
                @endif
                <form action="{{ route('instructor.assignments.submissions.grade', [$course, $assignment, $sub]) }}" method="post" class="sub-grade-wrap">
                    @csrf
                    @method('PUT')
                    <div class="row g-2 align-items-end">
                        <div class="col-auto">
                            <label class="form-label small mb-0">Score (max {{ $assignment->max_score }})</label>
                            <input type="number" name="score" class="form-control form-control-sm" style="width: 80px" min="0" max="{{ $assignment->max_score }}" value="{{ old('score', $sub->score ?? 0) }}" required>
                        </div>
                        <div class="col">
                            <label class="form-label small mb-0">Feedback</label>
                            <input type="text" name="feedback" class="form-control form-control-sm" placeholder="Optional feedback" value="{{ old('feedback', $sub->feedback) }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-sm">{{ $sub->isGraded() ? 'Update grade' : 'Grade' }}</button>
                        </div>
                    </div>
                </form>
            </div>
        @empty
            <p class="text-muted mb-0">No submissions yet.</p>
        @endforelse
    </div>
</div>

<a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary btn-sm mt-3">Back to course</a>
@endsection

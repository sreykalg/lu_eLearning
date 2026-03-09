@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4">
    <a href="{{ route('instructor.assignments.edit', [$course, $assignment]) }}" class="text-decoration-none small text-muted d-inline-block mb-1">&larr; Edit assignment</a>
    <h1 class="h3 fw-bold mb-1">Submissions: {{ $assignment->title }}</h1>
    <p class="text-muted small mb-0">{{ $course->title }}</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <p class="text-muted mb-4">{{ $submissions->count() }} submission(s)</p>
        @forelse($submissions as $sub)
            <div class="border-bottom pb-4 mb-4 {{ !$loop->last ? 'border-light' : '' }}">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                    <div>
                        <strong>{{ $sub->user->name ?? 'Unknown' }}</strong>
                        <span class="text-muted small ms-2">Submitted {{ $sub->submitted_at?->format('M j, Y g:i A') ?? '—' }}</span>
                    </div>
                    @if($sub->isGraded())
                        <span class="badge bg-success">Graded: {{ $sub->score }}/{{ $assignment->max_score }}</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </div>
                @if($sub->file_path)
                    <div class="mb-2">
                        <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download submitted file
                        </a>
                        <span class="text-muted small ms-2">{{ basename($sub->file_path) }}</span>
                    </div>
                @endif
                @if($sub->content)
                    <div class="mb-3 p-3 rounded bg-light small">
                        <strong>Notes:</strong><br>
                        {!! nl2br(e($sub->content)) !!}
                    </div>
                @endif
                @if($sub->isGraded() && $sub->feedback)
                    <div class="mb-3 p-3 rounded small" style="background: #f0fdf4;">
                        <strong>Your feedback:</strong><br>
                        {!! nl2br(e($sub->feedback)) !!}
                    </div>
                @endif
                <form action="{{ route('instructor.assignments.submissions.grade', [$course, $assignment, $sub]) }}" method="post" class="mt-2">
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

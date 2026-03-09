@extends('layouts.student-inner')

@section('content')
<div class="mb-4">
    <a href="{{ route('student.assignments') }}" class="text-decoration-none small text-muted d-inline-block mb-1">&larr; Assignments</a>
    <h1 class="h3 fw-bold mb-0" style="color: #0f172a;">{{ $assignment->title }}</h1>
    <p class="text-muted small mb-0">{{ $assignment->course->title }}</p>
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

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 mb-3">
            @if($submission)
                @if($submission->isGraded())
                    <span class="badge bg-success">Graded</span>
                    <span class="fw-semibold">Score: {{ $submission->score }}/{{ $assignment->max_score }}</span>
                @else
                    <span class="badge bg-info">Submitted</span>
                @endif
            @else
                <span class="badge bg-warning text-dark">Pending</span>
            @endif
        </div>
        @if($assignment->instructions)
            <div class="mb-4">
                <h6 class="fw-semibold mb-2">Instructions</h6>
                <p class="text-muted mb-0">{!! nl2br(e($assignment->instructions)) !!}</p>
            </div>
        @endif
        <div class="small text-muted mb-3">
            Due: {{ $assignment->due_at?->format('M j, g:i A') ?? '—' }} · Max: {{ $assignment->max_score }} pts
        </div>
        @if($submission)
            @if($submission->feedback)
                <div class="mt-3 p-3 rounded" style="background: #f8fafc;">
                    <h6 class="fw-semibold mb-2">Feedback</h6>
                    <p class="mb-0">{!! nl2br(e($submission->feedback)) !!}</p>
                </div>
            @endif
            <a href="{{ route('courses.show', $assignment->course) }}" class="btn btn-outline-secondary btn-sm mt-3">Back to course</a>
        @else
            <form action="{{ route('student.assignments.submit', [$course, $assignment]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Your submission</label>
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="5" placeholder="Type your response here...">{{ old('content') }}</textarea>
                    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Attach file (optional)</label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Submit assignment</button>
                <a href="{{ route('student.assignments') }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        @endif
    </div>
</div>
@endsection

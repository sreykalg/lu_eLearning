@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4">
    <a href="{{ route('instructor.submissions') }}" class="text-decoration-none small text-muted d-inline-block mb-1">&larr; Submissions</a>
    <h1 class="h3 fw-bold mb-1">Quiz attempts: {{ $quiz->title }}</h1>
    <p class="text-muted small mb-0">{{ $course->title }} · {{ ucfirst($quiz->type) }}</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <p class="text-muted mb-4">{{ $attempts->count() }} attempt(s)</p>
        @forelse($attempts as $a)
            <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-light">
                <div>
                    <strong>{{ $a->user->name ?? 'Unknown' }}</strong>
                    <span class="text-muted small ms-2">{{ $a->submitted_at?->format('M j, Y g:i A') ?? '—' }}</span>
                </div>
                <div>
                    <span class="badge {{ $a->passed ? 'bg-success' : ($a->passed === false ? 'bg-danger' : 'bg-secondary') }}">
                        {{ $a->score }}/{{ $a->total_points }}
                        @if($a->passed !== null)
                            ({{ $a->passed ? 'Passed' : 'Not passed' }})
                        @endif
                    </span>
                </div>
            </div>
        @empty
            <p class="text-muted mb-0">No attempts yet.</p>
        @endforelse
    </div>
</div>

<a href="{{ route('instructor.submissions') }}" class="btn btn-outline-secondary btn-sm mt-3">Back to Submissions</a>
@endsection

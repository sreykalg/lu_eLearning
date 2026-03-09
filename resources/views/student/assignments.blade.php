@extends('layouts.student-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Assignments</h1>
    @php
        $pending = $assignments->filter(fn($a) => !$submissions->has($a->id))->count();
        $graded = $assignments->filter(fn($a) => ($submissions->get($a->id)?->isGraded() ?? false))->count();
    @endphp
    <p class="text-muted mb-0">{{ $pending }} pending · {{ $graded }} graded</p>
</div>

<div class="rounded-3 bg-white shadow-sm border overflow-hidden">
    @forelse($assignments as $a)
        @php $sub = $submissions->get($a->id); $isGraded = $sub?->isGraded() ?? false; @endphp
        <div class="p-4 border-bottom border-light">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge {{ $isGraded ? 'bg-success' : 'bg-warning text-dark' }}">{{ $isGraded ? 'Graded' : 'Pending' }}</span>
                        <h6 class="mb-0 fw-semibold">{{ $a->title }}</h6>
                    </div>
                    <p class="text-muted small mb-0">{{ $a->course->title }}</p>
                    <p class="small mb-0 mt-1">{{ Str::limit($a->instructions, 80) }}</p>
                    <div class="small text-muted mt-1">
                        Due: {{ $a->due_at?->format('M j, g:i A') ?? '—' }} · Max: {{ $a->max_score }} pts
                    </div>
                    @if($isGraded && $sub)
                        <div class="small mt-1 fw-medium">Score: {{ $sub->score }}/{{ $a->max_score }}</div>
                    @endif
                </div>
                @if(!$isGraded)
                    <a href="{{ route('courses.show', $a->course) }}" class="btn btn-primary btn-sm">View</a>
                @endif
            </div>
        </div>
    @empty
        <div class="p-5 text-center text-muted">No assignments yet.</div>
    @endforelse
</div>
@endsection

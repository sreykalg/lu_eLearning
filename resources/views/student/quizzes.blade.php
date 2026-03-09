@extends('layouts.student-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Quizzes & Exams</h1>
    @php
        $completed = $quizzes->filter(fn($q) => $attempts->has($q->id))->count();
    @endphp
    <p class="text-muted mb-0">{{ $quizzes->count() - $completed }} upcoming · {{ $completed }} completed</p>
</div>

<div class="rounded-3 bg-white shadow-sm border overflow-hidden">
    @forelse($quizzes as $q)
        @php $attempt = $attempts->get($q->id); @endphp
        <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge {{ $attempt ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($q->type) }}</span>
                    <h6 class="mb-0 fw-semibold">{{ $q->title }}</h6>
                </div>
                <p class="text-muted small mb-0">{{ $q->course->title }}</p>
                @if($attempt)
                    <div class="small mt-1">Score: {{ $attempt->score }}/{{ $attempt->total_points }}</div>
                @endif
            </div>
            <a href="{{ route('courses.show', $q->course) }}" class="btn btn-outline-primary btn-sm">View details</a>
        </div>
    @empty
        <div class="p-5 text-center text-muted">No quizzes or exams yet.</div>
    @endforelse
</div>
@endsection

@php
$layout = auth()->user()->isStudent()
    ? 'layouts.student-inner'
    : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.instructor-inner');
@endphp
@extends($layout)

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-row { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .page-hero .back-link { color: rgba(255,255,255,0.85); text-decoration: none; font-size: 0.85rem; }
    .page-hero .back-link:hover { color: #fff; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-row">
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
            </div>
            <div>
                <h1 class="h3 hero-title">{{ $quiz->title }}</h1>
                <p class="hero-subtitle">
                    {{ $course->title }}
                    @if($quiz->type !== 'practice')
                        · {{ ucfirst($quiz->type) }}
                    @endif
                </p>
            </div>
        </div>
        <a href="{{ route('courses.show', $course) }}" class="back-link">&larr; {{ $course->title }}</a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($attempts->isNotEmpty())
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-2">Previous attempts</h5>
            <ul class="list-unstyled mb-0 small">
                @foreach($attempts->take(5) as $a)
                    <li class="py-1">{{ $a->submitted_at?->diffForHumans() }} — {{ $a->score }}/{{ $a->total_points }} @if($a->passed !== null) ({{ $a->passed ? 'Passed' : 'Not passed' }}) @endif</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($quiz->description)
            <p class="text-muted mb-4">{{ $quiz->description }}</p>
        @endif
        @php $totalPts = $quiz->total_points ?? $quiz->questions->sum('points'); @endphp
        @if($totalPts > 0)
            <p class="text-muted small mb-3">Total: {{ $totalPts }} points</p>
        @endif
        <form method="POST" action="{{ route('student.quizzes.submit', [$course, $quiz]) }}">
            @csrf
            @foreach($quiz->questions as $index => $q)
                @php
                    $qType = $q->type ?? 'multiple_choice';
                    $opts = $q->options ?? [];
                @endphp
                <div class="mb-4 pb-4 border-bottom">
                    <p class="fw-semibold mb-2">{{ $index + 1 }}. {{ $q->question }} <span class="text-muted small fw-normal">({{ (int)($q->points ?? 1) }} pt{{ (int)($q->points ?? 1) !== 1 ? 's' : '' }})</span></p>
                    @if($qType === 'multiple_choice')
                        @foreach($opts as $i => $opt)
                            <label class="d-flex align-items-center gap-2 mb-2 small cursor-pointer" style="cursor: pointer;">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $i }}" class="form-check-input">
                                <span>{{ $opt['text'] ?? '' }}</span>
                            </label>
                        @endforeach
                    @elseif($qType === 'short_answer')
                        <input type="text" name="answers[{{ $q->id }}]" class="form-control" placeholder="Your answer">
                    @else
                        <textarea name="answers[{{ $q->id }}]" class="form-control font-monospace" rows="6" placeholder="Write your code or answer here"></textarea>
                    @endif
                </div>
            @endforeach
            @if($quiz->questions->isEmpty())
                <p class="text-muted mb-0">No questions in this quiz yet.</p>
            @else
                <button type="submit" class="btn mt-2" style="background: #0f172a; color: #fff;">Submit quiz</button>
            @endif
        </form>
    </div>
</div>
@endsection

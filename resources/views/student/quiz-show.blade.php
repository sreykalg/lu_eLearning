@php
$layout = auth()->user()->isStudent()
    ? 'layouts.student-inner'
    : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.instructor-inner');
@endphp
@extends($layout)

@push('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
        border-radius: 1rem;
        padding: 1.25rem 1.4rem;
        color: #fff;
        margin-bottom: 1rem;
        box-shadow: 0 12px 34px rgba(15, 23, 42, 0.18);
    }
    .page-hero .hero-row { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.015em; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .page-hero .back-link { color: rgba(255,255,255,0.85); text-decoration: none; font-size: 0.85rem; }
    .page-hero .back-link:hover { color: #fff; }
    .quiz-shell { background: #fff; border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden; box-shadow: 0 4px 22px rgba(15, 23, 42, 0.06); }
    .quiz-shell .quiz-intro { padding: 1rem 1.2rem; border-bottom: 1px solid #f1f5f9; background: linear-gradient(180deg, #fff 0%, #fafbfc 100%); }
    .quiz-shell .quiz-content { padding: 1rem 1.2rem 1.2rem; }
    .question-card { border: 1px solid #e5e7eb; border-radius: 0.8rem; background: #fff; padding: 0.95rem; margin-bottom: 0.9rem; }
    .question-title { font-weight: 700; color: #0f172a; margin-bottom: 0.65rem; }
    .question-pts { color: #64748b; font-weight: 500; font-size: 0.82rem; }
    .option-item { border: 1px solid #e2e8f0; border-radius: 0.6rem; padding: 0.55rem 0.65rem; margin-bottom: 0.5rem; background: #f8fafc; }
    .option-item:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .quiz-submit-row { border-top: 1px solid #e5e7eb; margin-top: 0.8rem; padding-top: 0.9rem; display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
    .quiz-submit-btn { background: #0f172a; color: #fff; border-radius: 0.6rem; padding: 0.5rem 1rem; font-weight: 600; border: none; }
    .quiz-submit-btn:hover { background: #1e293b; color: #fff; }
    .attempts-card { border: 1px solid #e5e7eb; border-radius: 0.9rem; background: #fff; box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05); }
    .attempts-list li { padding: 0.5rem 0.25rem; border-bottom: 1px solid #f1f5f9; }
    .attempts-list li:last-child { border-bottom: none; }
    .quiz-total-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 9999px;
        padding: 0.22rem 0.55rem;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #334155;
        font-size: 0.78rem;
        font-weight: 600;
    }
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

@if($attempts->isNotEmpty())
    <div class="card border-0 shadow-sm mb-3 attempts-card">
        <div class="card-body">
            <h5 class="fw-semibold mb-2">Previous attempts</h5>
            <ul class="list-unstyled mb-0 small attempts-list">
                @foreach($attempts->take(5) as $a)
                    <li class="py-1">{{ $a->submitted_at?->diffForHumans() }} — {{ $a->score }}/{{ $a->total_points }} @if($a->passed !== null) ({{ $a->passed ? 'Passed' : 'Not passed' }}) @endif</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="quiz-shell">
    <div class="quiz-intro">
        @if($quiz->description)
            <p class="text-muted mb-2">{{ $quiz->description }}</p>
        @endif
        @php $totalPts = $quiz->total_points ?? $quiz->questions->sum('points'); @endphp
        @if($totalPts > 0)
            <span class="quiz-total-chip">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Total: {{ $totalPts }} points
            </span>
        @endif
    </div>
    <div class="quiz-content">
        <form method="POST" action="{{ route('student.quizzes.submit', [$course, $quiz]) }}">
            @csrf
            @foreach($quiz->questions as $index => $q)
                @php
                    $qType = $q->type ?? 'multiple_choice';
                    $opts = $q->options ?? [];
                @endphp
                <div class="question-card">
                    <p class="question-title mb-2">{{ $index + 1 }}. {{ $q->question }} <span class="question-pts">({{ (int)($q->points ?? 1) }} pt{{ (int)($q->points ?? 1) !== 1 ? 's' : '' }})</span></p>
                    @if($qType === 'multiple_choice')
                        @foreach($opts as $i => $opt)
                            <label class="d-flex align-items-center gap-2 small cursor-pointer option-item" style="cursor: pointer;">
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
                <div class="quiz-submit-row">
                    <span class="text-muted small">Review your answers before submitting.</span>
                    <button type="submit" class="btn quiz-submit-btn">Submit quiz</button>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection

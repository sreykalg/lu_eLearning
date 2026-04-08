@php
$layout = auth()->check()
    ? (auth()->user()->isStudent() ? 'layouts.student-inner' : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.instructor-inner'))
    : 'layouts.app-simple';
@endphp
@extends($layout)

@push('styles')
<style>
    :root { --lu-deep-purple: #0f172a; --lu-purple: #1e293b; --lu-purple-light: rgba(15, 23, 42, 0.08); }
    .course-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
        border-radius: 1rem;
        padding: 1.2rem 1.35rem;
        color: #fff;
        margin-bottom: 1rem;
        box-shadow: 0 12px 36px rgba(15, 23, 42, 0.18);
    }
    .course-back { color: rgba(255,255,255,0.86); text-decoration: none; font-size: 0.84rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem; }
    .course-back:hover { color: #fff; }
    .course-hero-title { margin: 0.55rem 0 0.35rem; font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em; color: #fff; }
    .course-hero-sub { margin: 0; color: rgba(255,255,255,0.82); font-size: 0.9rem; }
    .course-hero-actions { margin-top: 0.75rem; display: flex; align-items: center; gap: 0.55rem; flex-wrap: wrap; }
    .course-card { border: 1px solid #e2e8f0; border-radius: 1rem; overflow: hidden; background: #fff; box-shadow: 0 4px 24px rgba(15,23,42,.06); }
    .course-content-list { max-height: 430px; overflow-y: auto; }
    .course-content-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.78rem 1rem; text-decoration: none; color: #334155; border-bottom: 1px solid #f1f5f9; transition: background 0.15s; }
    .course-content-item:hover { background: #f8fafc; }
    .course-content-item .num { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0; }
    .course-content-item .num.done { background: #10b981; color: #fff; }
    .course-content-item .num.todo { background: #e5e7eb; color: #374151; }
    .btn-enroll { background: var(--lu-deep-purple); color: #fff; border: none; border-radius: 0.6rem; font-weight: 700; }
    .btn-enroll:hover { background: var(--lu-purple); color: #fff; }
    .course-meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border-radius: 9999px;
        padding: 0.35rem 0.65rem;
        font-size: 0.78rem;
        background: rgba(255,255,255,0.16);
        color: #fff;
    }
</style>
@endpush

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="course-hero">
    <a href="{{ auth()->check() ? route('overview') : route('courses.index') }}" class="course-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to courses
    </a>
    <h1 class="course-hero-title">{{ $course->title }}</h1>
    <p class="course-hero-sub">Review lessons, quizzes, and your progress in one place.</p>
    <div class="course-hero-actions">
        @auth
            @if($enrollment)
                <span class="course-meta-badge">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <span class="fw-semibold">{{ $coursePoints ?? 0 }}</span> points in this course
                </span>
            @endif
            @if (!$enrollment)
                <form action="{{ route('courses.enroll', $course) }}" method="POST" class="m-0">@csrf
                    <button type="submit" class="btn btn-enroll btn-sm">Enroll Now</button>
                </form>
            @endif
        @endauth
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="course-card">
            <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                @if ($course->thumbnail)
                    <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->title }}" class="object-fit-cover w-100 h-100">
                @else
                    <svg class="text-secondary" width="64" height="64" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/></svg>
                @endif
            </div>
            <div class="p-4">
                <span class="badge rounded-pill mb-2" style="background: rgba(45,27,78,0.15); color: var(--lu-deep-purple);">{{ ucfirst($course->level) }}</span>
                <p class="text-muted mb-2">{{ $course->description }}</p>
                <p class="small text-muted mb-0"><strong>Instructor:</strong> {{ $course->instructor->name ?? '—' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="course-card">
            <div class="p-3 border-bottom" style="background: #f9fafb;">
                <h5 class="mb-0 fw-semibold" style="color: var(--lu-deep-purple);">Course Content</h5>
            </div>
            <div class="course-content-list">
                @foreach ($course->lessons as $lesson)
                    @php $lessonProgress = $progress->get($lesson->id); $isCompleted = $lessonProgress?->completed ?? false; @endphp
                    <a href="{{ auth()->check() ? route('lessons.show', [$course, $lesson]) : route('login') }}"
                       class="course-content-item">
                        <span class="num {{ $isCompleted ? 'done' : 'todo' }}">
                            @if ($isCompleted)
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </span>
                        <div class="flex-grow-1 min-w-0">
                            <span class="d-block text-truncate fw-medium">{{ $lesson->title }}</span>
                            @if ($lesson->video_duration)
                                <span class="small text-muted">{{ gmdate('i:s', $lesson->video_duration) }}</span>
                            @endif
                        </div>
                        <svg width="16" height="16" fill="currentColor" class="flex-shrink-0 text-muted" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endforeach
            </div>
            @if ($course->quizzes->isNotEmpty())
                <div class="p-3 border-top" style="background: #f9fafb;">
                    <h6 class="small fw-semibold mb-2" style="color: var(--lu-deep-purple);">Quizzes</h6>
                    <ul class="small mb-0 ps-0 list-unstyled">
                        @foreach ($course->quizzes as $quiz)
                            <li class="py-1">
                                @auth
                                    <a href="{{ route('student.quizzes.show', [$course, $quiz]) }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark">
                                        <span class="flex-grow-1">{{ $quiz->title }} @if($quiz->type !== 'practice') ({{ $quiz->type }}) @endif</span>
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                                    </a>
@else
                    <a href="{{ route('login') }}" class="text-muted text-decoration-none">{{ $quiz->title }} @if($quiz->type !== 'practice') ({{ $quiz->type }}) @endif</a>
                                @endauth
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

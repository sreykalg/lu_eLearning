@php
$layout = auth()->check()
    ? (auth()->user()->isStudent() ? 'layouts.student-inner' : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.instructor-inner'))
    : 'layouts.app-simple';
@endphp
@extends($layout)

@push('styles')
<style>
    .course-back { color: #0f172a; text-decoration: none; font-size: 0.875rem; }
    .course-back:hover { color: #1e293b; }
    .course-card { border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; background: #fff; }
    .course-content-list { max-height: 400px; overflow-y: auto; }
    .course-content-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; text-decoration: none; color: #374151; border-bottom: 1px solid #f3f4f6; transition: background 0.15s; }
    .course-content-item:hover { background: #f9fafb; }
    .course-content-item .num { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0; }
    .course-content-item .num.done { background: #10b981; color: #fff; }
    .course-content-item .num.todo { background: #e5e7eb; color: #374151; }
    .btn-enroll { background: #0f172a; color: #fff; border: none; }
    .btn-enroll:hover { background: #1e293b; color: #fff; }
</style>
@endpush

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <a href="{{ auth()->check() ? route('overview') : route('courses.index') }}" class="course-back d-inline-block mb-2">&larr; Back to courses</a>
        <h1 class="h3 fw-bold mb-1" style="color: #0f172a;">{{ $course->title }}</h1>
        @auth
            @if (!$enrollment)
                <form action="{{ route('courses.enroll', $course) }}" method="POST" class="mt-2">@csrf
                    <button type="submit" class="btn btn-enroll btn-sm">Enroll Now</button>
                </form>
            @endif
        @endauth
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="course-card shadow-sm">
            <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                @if ($course->thumbnail)
                    <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->title }}" class="object-fit-cover w-100 h-100">
                @else
                    <svg class="text-secondary" width="64" height="64" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/></svg>
                @endif
            </div>
            <div class="p-4">
                <span class="badge bg-light text-dark mb-2">{{ $course->level }}</span>
                <p class="text-muted mb-2">{{ $course->description }}</p>
                <p class="small text-muted mb-0"><strong>Instructor:</strong> {{ $course->instructor->name ?? '—' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="course-card shadow-sm">
            <div class="p-3 border-bottom" style="background: #f9fafb;">
                <h5 class="mb-0 fw-semibold">Course Content</h5>
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
                    <h6 class="small fw-semibold mb-2">Quizzes</h6>
                    <ul class="small text-muted mb-0 ps-3">
                        @foreach ($course->quizzes as $quiz)
                            <li>{{ $quiz->title }} @if($quiz->type !== 'practice') ({{ $quiz->type }}) @endif</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <a href="{{ route('courses.index') }}" class="text-decoration-none small mb-1 d-inline-block" style="color: var(--lu-purple);">&larr; Back to courses</a>
                <h2 class="mb-0 fw-bold" style="color: var(--lu-deep-purple);">{{ $course->title }}</h2>
            </div>
            @auth
                @if (!$enrollment)
                    <form action="{{ route('courses.enroll', $course) }}" method="POST">@csrf
                        <button type="submit" class="btn btn-lu-primary">Enroll Now</button>
                    </form>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                        @if ($course->thumbnail)
                            <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="object-fit-cover">
                        @else
                            <svg class="text-secondary" width="64" height="64" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/></svg>
                        @endif
                    </div>
                    <div class="card-body">
                        <span class="badge bg-light text-dark mb-2">{{ $course->level }}</span>
                        <p class="text-muted mb-3">{{ $course->description }}</p>
                        <p class="small text-muted mb-0"><strong>Instructor:</strong> {{ $course->instructor->name }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 fw-semibold">Course Content</h5>
                    </div>
                    <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                        @foreach ($course->lessons as $lesson)
                            @php $lessonProgress = $progress->get($lesson->id); $isCompleted = $lessonProgress?->completed ?? false; @endphp
                            <a href="{{ auth()->check() ? route('lessons.show', [$course, $lesson]) : route('login') }}"
                               class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                                <span class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ $isCompleted ? 'bg-success text-white' : 'bg-light' }}" style="width:28px;height:28px;font-size:12px;">
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
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </div>
                    @if ($course->quizzes->isNotEmpty())
                        <div class="card-footer bg-light">
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
    </div>
</x-app-layout>

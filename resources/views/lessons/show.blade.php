<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('courses.show', $course) }}" class="text-decoration-none small mb-1 d-inline-block" style="color: var(--lu-purple);">&larr; {{ $course->title }}</a>
            <h2 class="mb-0 fw-bold" style="color: var(--lu-deep-purple);">{{ $lesson->title }}</h2>
        </div>
    </x-slot>

    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 overflow-hidden rounded-3">
                    <div class="ratio ratio-16x9 bg-dark d-flex align-items-center justify-content-center">
                        @if ($lesson->video_url)
                            <video id="lesson-video" class="w-100 h-100" controls preload="metadata"
                                   data-lesson-id="{{ $lesson->id }}" data-duration="{{ $lesson->video_duration ?? 0 }}">
                                <source src="{{ $lesson->video_url }}" type="video/mp4">
                            </video>
                        @else
                            <div class="text-white-50 text-center">
                                <svg width="64" height="64" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/></svg>
                                <p class="mt-2 mb-0">No video available</p>
                            </div>
                        @endif
                    </div>
                </div>
                @if ($lesson->content)
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Lesson Notes</h5>
                            <p class="text-muted mb-0">{{ $lesson->content }}</p>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top">
                    <div class="card-header bg-light"><h5 class="mb-0 fw-semibold">Course Content</h5></div>
                    <div class="list-group list-group-flush" style="max-height: 350px; overflow-y: auto;">
                        @foreach ($course->lessons as $l)
                            @php $isCurrent = $l->id === $lesson->id; $p = $l->getProgressFor(auth()->user()); $isCompleted = $p?->completed ?? false; @endphp
                            <a href="{{ route('lessons.show', [$course, $l]) }}"
                               class="list-group-item list-group-item-action d-flex align-items-center gap-3 {{ $isCurrent ? 'active' : '' }}">
                                <span class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ $isCompleted ? 'bg-success text-white' : ($isCurrent ? 'bg-white text-dark' : 'bg-light') }}" style="width:28px;height:28px;font-size:12px;">
                                    @if ($isCompleted)
                                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </span>
                                <span class="flex-grow-1 text-truncate fw-medium">{{ $l->title }}</span>
                            </a>
                        @endforeach
                    </div>
                    <div class="card-footer d-flex gap-2">
                        @if ($prevLesson)
                            <a href="{{ route('lessons.show', [$course, $prevLesson]) }}" class="btn btn-outline-secondary btn-sm flex-grow-1">&larr; Previous</a>
                        @else
                            <span class="flex-grow-1"></span>
                        @endif
                        @if ($nextLesson)
                            <a href="{{ route('lessons.show', [$course, $nextLesson]) }}" class="btn btn-lu-primary btn-sm flex-grow-1">Next &rarr;</a>
                        @else
                            <span class="flex-grow-1"></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var video = document.getElementById('lesson-video');
            if (!video || !video.dataset.lessonId) return;
            var lessonId = video.dataset.lessonId, lastSent = 0;
            function send(sec, done) {
                if (sec <= lastSent && !done) return;
                lastSent = sec;
                fetch('{{ route("lessons.progress") }}', {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ lesson_id: lessonId, watched_seconds: Math.floor(sec), completed: done })
                });
            }
            video.addEventListener('timeupdate', function() {
                var d = parseInt(video.dataset.duration || 0);
                send(video.currentTime, d > 0 && video.currentTime >= d * 0.9);
            });
            video.addEventListener('ended', function() { send(video.duration || video.currentTime, true); });
        });
    </script>
    @endpush
</x-app-layout>

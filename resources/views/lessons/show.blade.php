<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('courses.show', $course) }}" class="text-sm text-primary-600 hover:text-primary-800 mb-1 inline-block">
                &larr; {{ $course->title }}
            </a>
            <h2 class="font-semibold text-xl text-primary-950 leading-tight">
                {{ $lesson->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Video Player --}}
                    <div class="bg-primary-950 rounded-xl overflow-hidden aspect-video">
                        @if ($lesson->video_url)
                            <video
                                id="lesson-video"
                                class="w-full h-full"
                                controls
                                preload="metadata"
                                data-lesson-id="{{ $lesson->id }}"
                                data-duration="{{ $lesson->video_duration ?? 0 }}"
                            >
                                <source src="{{ $lesson->video_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white/60">
                                <div class="text-center">
                                    <svg class="w-16 h-16 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/>
                                    </svg>
                                    <p>No video available</p>
                                    <p class="text-sm mt-1">Add a video URL to this lesson</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Lesson Content --}}
                    @if ($lesson->content)
                        <div class="bg-white rounded-xl border border-primary-100 p-6 shadow-sm">
                            <h3 class="font-semibold text-primary-950 mb-4">Lesson Notes</h3>
                            <div class="prose prose-primary max-w-none text-primary-700">
                                {!! nl2br(e($lesson->content)) !!}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar: Course outline --}}
                <div>
                    <div class="bg-white rounded-xl border border-primary-100 shadow-sm overflow-hidden sticky top-4">
                        <div class="px-4 py-3 bg-primary-50 border-b border-primary-100">
                            <h3 class="font-semibold text-primary-950">Course Content</h3>
                        </div>
                        <div class="divide-y divide-primary-50 max-h-[400px] overflow-y-auto">
                            @foreach ($course->lessons as $l)
                                @php
                                    $isCurrent = $l->id === $lesson->id;
                                    $p = $l->getProgressFor(auth()->user());
                                    $isCompleted = $p?->completed ?? false;
                                @endphp
                                <a href="{{ route('lessons.show', [$course, $l]) }}"
                                   class="flex items-center gap-3 px-4 py-3 {{ $isCurrent ? 'bg-primary-50' : 'hover:bg-primary-50' }} transition-colors">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-medium
                                        {{ $isCompleted ? 'bg-green-100 text-green-700' : ($isCurrent ? 'bg-primary-600 text-white' : 'bg-primary-100 text-primary-600') }}">
                                        @if ($isCompleted)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </span>
                                    <span class="flex-1 font-medium text-primary-950 truncate {{ $isCurrent ? 'text-primary-700' : '' }}">{{ $l->title }}</span>
                                </a>
                            @endforeach
                        </div>

                        {{-- Prev / Next --}}
                        <div class="p-4 border-t border-primary-100 flex gap-3">
                            @if ($prevLesson)
                                <a href="{{ route('lessons.show', [$course, $prevLesson]) }}"
                                   class="flex-1 text-center py-2 px-3 rounded-lg border border-primary-200 text-primary-700 hover:bg-primary-50 text-sm font-medium">
                                    &larr; Previous
                                </a>
                            @else
                                <span class="flex-1"></span>
                            @endif
                            @if ($nextLesson)
                                <a href="{{ route('lessons.show', [$course, $nextLesson]) }}"
                                   class="flex-1 text-center py-2 px-3 rounded-lg bg-primary-950 text-white hover:bg-primary-800 text-sm font-medium">
                                    Next &rarr;
                                </a>
                            @else
                                <span class="flex-1"></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('lesson-video');
            if (!video || !video.dataset.lessonId) return;

            const lessonId = video.dataset.lessonId;
            let lastSentSeconds = 0;
            const throttleMs = 5000;

            function sendProgress(seconds, completed = false) {
                if (seconds <= lastSentSeconds && !completed) return;
                lastSentSeconds = seconds;

                fetch('{{ route("lessons.progress") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        lesson_id: lessonId,
                        watched_seconds: Math.floor(seconds),
                        completed: completed,
                    }),
                });
            }

            video.addEventListener('timeupdate', function() {
                if (video.currentTime - lastSentSeconds >= throttleMs / 1000 || lastSentSeconds === 0) {
                    const duration = parseInt(video.dataset.duration || 0);
                    const completed = duration > 0 && video.currentTime >= duration * 0.9;
                    sendProgress(video.currentTime, completed);
                }
            });

            video.addEventListener('ended', function() {
                sendProgress(video.duration || video.currentTime, true);
            });
        });
    </script>
    @endpush
</x-app-layout>

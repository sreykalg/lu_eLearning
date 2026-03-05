<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('courses.index') }}" class="text-sm text-primary-600 hover:text-primary-800 mb-1 inline-block">
                    &larr; Back to courses
                </a>
                <h2 class="font-semibold text-xl text-primary-950 leading-tight">
                    {{ $course->title }}
                </h2>
            </div>
            @auth
                @if (!$enrollment)
                    <form action="{{ route('courses.enroll', $course) }}" method="POST" class="inline">
                        @csrf
                        <x-primary-button>Enroll Now</x-primary-button>
                    </form>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border border-primary-100 overflow-hidden shadow-sm">
                        <div class="aspect-video bg-primary-100 flex items-center justify-center">
                            @if ($course->thumbnail)
                                <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-24 h-24 text-primary-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="p-6">
                            <span class="text-xs font-medium text-primary-500 uppercase tracking-wider">{{ $course->level }}</span>
                            <p class="mt-4 text-primary-700">{{ $course->description }}</p>
                            <div class="mt-4 flex items-center gap-2 text-sm text-primary-600">
                                <span>Instructor:</span>
                                <span class="font-medium">{{ $course->instructor->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="bg-white rounded-xl border border-primary-100 shadow-sm overflow-hidden sticky top-4">
                        <div class="px-4 py-3 bg-primary-50 border-b border-primary-100">
                            <h3 class="font-semibold text-primary-950">Course Content</h3>
                        </div>
                        <div class="divide-y divide-primary-50 max-h-[500px] overflow-y-auto">
                            @foreach ($course->lessons as $lesson)
                                @php
                                    $lessonProgress = $progress->get($lesson->id);
                                    $isCompleted = $lessonProgress?->completed ?? false;
                                @endphp
                                <a href="{{ auth()->check() ? route('lessons.show', [$course, $lesson]) : route('login') }}"
                                   class="flex items-center gap-3 px-4 py-3 hover:bg-primary-50 transition-colors group">
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-medium
                                        {{ $isCompleted ? 'bg-green-100 text-green-700' : 'bg-primary-100 text-primary-600' }}">
                                        @if ($isCompleted)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <span class="block font-medium text-primary-950 group-hover:text-primary-700 truncate">{{ $lesson->title }}</span>
                                        @if ($lesson->video_duration)
                                            <span class="text-xs text-primary-500">{{ gmdate('i:s', $lesson->video_duration) }}</span>
                                        @endif
                                    </div>
                                    <svg class="w-4 h-4 text-primary-400 group-hover:text-primary-600 flex-shrink-0"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>

                        @if ($course->quizzes->isNotEmpty())
                            <div class="px-4 py-3 bg-primary-50/50 border-t border-primary-100">
                                <h4 class="text-sm font-medium text-primary-700 mb-2">Quizzes</h4>
                                <ul class="space-y-1 text-sm text-primary-600">
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
    </div>
</x-app-layout>

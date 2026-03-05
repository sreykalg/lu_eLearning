<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary-950 leading-tight">
            {{ __('Courses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-primary-950">Explore Courses</h1>
                <p class="mt-2 text-primary-600">Short video lessons, quizzes, and assignments. Learn at your own pace.</p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($courses as $course)
                    <a href="{{ route('courses.show', $course) }}"
                       class="group block bg-white rounded-xl border border-primary-100 overflow-hidden shadow-sm hover:shadow-md hover:border-primary-200 transition-all duration-200">
                        <div class="aspect-video bg-primary-100 flex items-center justify-center">
                            @if ($course->thumbnail)
                                <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <svg class="w-16 h-16 text-primary-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="p-4">
                            <span class="text-xs font-medium text-primary-500 uppercase tracking-wider">{{ $course->level }}</span>
                            <h3 class="mt-1 font-semibold text-primary-950 group-hover:text-primary-700 transition-colors">{{ $course->title }}</h3>
                            <p class="mt-1 text-sm text-primary-600 line-clamp-2">{{ Str::limit($course->description, 80) }}</p>
                            <p class="mt-2 text-xs text-primary-500">{{ $course->instructor->name }}</p>
                            @if ($enrolledIds->contains($course->id))
                                <span class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">
                                    Enrolled
                                </span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-16">
                        <svg class="mx-auto h-12 w-12 text-primary-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-primary-950">No courses yet</h3>
                        <p class="mt-1 text-sm text-primary-600">Courses will appear here once they are published.</p>
                    </div>
                @endforelse
            </div>

            @if ($courses->hasPages())
                <div class="mt-8">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

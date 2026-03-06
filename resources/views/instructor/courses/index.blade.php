<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="mb-0 fw-bold" style="color: var(--lu-deep-purple);">My Courses</h2>
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-lu-primary">Create Course</a>
        </div>
    </x-slot>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="list-group">
            @forelse($courses as $course)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $course->title }}</h5>
                        <small class="text-muted">{{ $course->lessons_count }} lessons · {{ $course->quizzes_count }} quizzes · {{ $course->assignments_count }} assignments</small>
                    </div>
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-sm btn-lu-primary">Edit</a>
                </div>
            @empty
                <div class="list-group-item text-center py-5">
                    <p class="text-muted mb-3">No courses yet.</p>
                    <a href="{{ route('instructor.courses.create') }}" class="btn btn-lu-primary">Create Course</a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

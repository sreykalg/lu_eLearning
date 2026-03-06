<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="mb-0 fw-bold" style="color: var(--lu-deep-purple);">Instructor Dashboard</h2>
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-lu-primary">Create Course</a>
        </div>
    </x-slot>

    <div class="container">
        <p class="text-muted mb-4">Manage your courses, lessons, quizzes, and assignments.</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @forelse($courses as $course)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0" style="color: var(--lu-deep-purple);">{{ $course->title }}</h5>
                                @if($course->is_published)
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </div>
                            <p class="text-muted small mb-3">{{ Str::limit($course->description, 80) }}</p>
                            <div class="d-flex gap-2 text-muted small mb-3">
                                <span>{{ $course->lessons_count }} lessons</span>
                                <span>{{ $course->quizzes_count }} quizzes</span>
                                <span>{{ $course->assignments_count }} assignments</span>
                                <span>{{ $course->enrollments_count }} enrolled</span>
                            </div>
                            <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-sm btn-outline-secondary">Manage</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <p class="text-muted mb-3">You haven't created any courses yet.</p>
                            <a href="{{ route('instructor.courses.create') }}" class="btn btn-lu-primary">Create your first course</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

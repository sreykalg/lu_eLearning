@extends('layouts.instructor')

@section('content')
<div class="instructor-overview">
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <p class="text-muted mb-0">Welcome back. Here’s an overview of your courses.</p>
        </div>
        <a href="{{ route('instructor.courses.create') }}" class="btn btn-lu-primary">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M12 5v14M5 12h14"/></svg>
            Create course
        </a>
    </div>

    <div class="mb-4">
        <div class="d-flex flex-wrap align-items-center gap-3 text-muted" style="font-size: 0.9375rem;">
            <span><strong class="text-dark" style="color: var(--lu-deep-purple) !important;">{{ $stats['courses'] }}</strong> courses</span>
            <span class="opacity-50">·</span>
            <span><strong class="text-dark" style="color: var(--lu-deep-purple) !important;">{{ $stats['lessons'] }}</strong> lessons</span>
            <span class="opacity-50">·</span>
            <span><strong class="text-dark" style="color: var(--lu-deep-purple) !important;">{{ $stats['enrollments'] }}</strong> students enrolled</span>
        </div>
    </div>

    @if($courses->isEmpty())
        <div class="rounded-3 p-5 text-center" style="background: #fff; border: 1px solid rgba(45,27,78,0.08);">
            <div class="mb-3">
                <svg width="64" height="64" fill="none" stroke="rgba(45,27,78,0.2)" viewBox="0 0 24 24"><path stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <p class="text-muted mb-4">You haven’t created any courses yet.</p>
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-lu-primary">Create your first course</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($courses as $course)
                <div class="col-sm-6 col-lg-4">
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="text-decoration-none text-dark d-block h-100 instructor-course-card">
                        <div class="rounded-3 h-100 overflow-hidden" style="background: #fff; border: 1px solid rgba(45,27,78,0.08); transition: box-shadow 0.2s ease, border-color 0.2s ease;">
                            <div class="ratio ratio-16x9" style="background: rgba(45,27,78,0.06);">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="" class="object-fit-cover">
                                @else
                                    <div class="d-flex align-items-center justify-content-center">
                                        <svg width="48" height="48" fill="none" stroke="var(--lu-deep-purple)" viewBox="0 0 24 24" style="opacity: 0.25;"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <h6 class="mb-0 fw-semibold text-truncate flex-grow-1" style="color: var(--lu-deep-purple);">{{ $course->title }}</h6>
                                    @if($course->is_published)
                                        <span class="badge bg-success flex-shrink-0">Published</span>
                                    @else
                                        <span class="badge bg-secondary flex-shrink-0">Draft</span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-3" style="line-height: 1.4;">{{ Str::limit($course->description, 72) }}</p>
                                <div class="d-flex flex-wrap gap-2 text-muted small mb-3">
                                    <span>{{ $course->lessons_count }} lessons</span>
                                    <span>·</span>
                                    <span>{{ $course->quizzes_count }} quizzes</span>
                                    <span>·</span>
                                    <span>{{ $course->assignments_count }} assignments</span>
                                    <span>·</span>
                                    <span>{{ $course->enrollments_count }} enrolled</span>
                                </div>
                                <span class="text-decoration-none fw-medium small" style="color: var(--lu-purple);">Manage course →</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.instructor-course-card:hover .rounded-3 { box-shadow: 0 8px 24px rgba(45, 27, 78, 0.1); border-color: rgba(45, 27, 78, 0.12) !important; }
</style>
@endsection

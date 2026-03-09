@extends('layouts.student-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">My Courses</h1>
    <p class="text-muted mb-0">{{ $courses->count() }} enrolled courses</p>
</div>

<div class="row g-4">
    @forelse($courses as $course)
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('courses.show', $course) }}" class="text-decoration-none text-dark d-block h-100">
                <div class="rounded-3 bg-white shadow-sm border h-100 overflow-hidden">
                    <div class="ratio ratio-16x9 bg-light">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="" class="object-fit-cover">
                        @else
                            <div class="d-flex align-items-center justify-content-center">
                                <svg width="48" height="48" fill="none" stroke="#94a3b8" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <h6 class="fw-semibold mb-1">{{ $course->title }}</h6>
                        <p class="text-muted small mb-2">{{ $course->instructor->name ?? '—' }}</p>
                        <div class="small mb-2">
                            <span class="text-muted">{{ $course->progress_done }}/{{ $course->lessons_count }} lessons</span>
                            <span class="ms-2 fw-medium">{{ $course->progress_pct }}%</span>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-primary" style="width:{{ $course->progress_pct }}%"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="rounded-3 bg-white shadow-sm border p-5 text-center">
                <p class="text-muted mb-3">You are not enrolled in any courses yet.</p>
                <a href="{{ route('courses.index') }}" class="btn btn-primary">Browse courses</a>
            </div>
        </div>
    @endforelse
</div>
@endsection

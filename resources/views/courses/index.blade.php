@extends('layouts.public-inner')

@section('content')
    <div class="mb-5">
        <h1 class="h3 fw-bold text-primary">Courses</h1>
        <h2 class="h5 fw-bold text-primary mb-2">Explore Courses</h2>
        <p class="text-muted mb-0">Short video lessons, quizzes, and assignments. Learn at your own pace.</p>
    </div>

    <div class="row g-4">
        @forelse ($courses as $course)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card border-0 shadow-sm h-100 overflow-hidden d-flex flex-column">
                    <a href="{{ route('courses.show', $course) }}" class="text-decoration-none text-dark">
                        <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                            @if ($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="object-fit-cover">
                            @else
                                <svg class="text-secondary" width="48" height="48" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/></svg>
                            @endif
                        </div>
                        <div class="card-body">
                            <span class="badge bg-light text-dark mb-2">{{ $course->level }}</span>
                            <h5 class="card-title text-dark">{{ $course->title }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($course->description, 80) }}</p>
                            <p class="small text-muted mb-2">{{ $course->instructor->name }}</p>
                        </div>
                    </a>
                    <div class="card-body pt-0 mt-auto">
                        @if ($enrolledIds->contains($course->id))
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary btn-sm w-100">View Course</a>
                        @elseif (auth()->check())
                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm w-100">Enroll</button>
                            </form>
                        @else
                            <a href="{{ route('login', ['intended' => route('courses.show', $course)]) }}" class="btn btn-primary btn-sm w-100">Enroll</a>
                            <p class="small text-muted text-center mt-1 mb-0">Log in or register to enroll</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <svg class="mb-2" width="48" height="48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                <p class="mb-0">@if(request('q'))No courses match "{{ request('q') }}".@else No courses yet. Courses will appear here once published.@endif</p>
                @if(request('q'))<a href="{{ route('courses.index') }}" class="text-primary small">Clear search</a>@endif
            </div>
        @endforelse
    </div>

    @if ($courses->hasPages())
        <div class="mt-4">{{ $courses->links() }}</div>
    @endif
@endsection

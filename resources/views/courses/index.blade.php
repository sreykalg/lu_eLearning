@extends('layouts.public-inner')

@push('styles')
<style>
    .guest-courses-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 58%, #334155 100%);
        border-radius: 1rem;
        padding: 1.25rem 1.4rem;
        color: #fff;
        margin-bottom: 1rem;
    }
    .guest-courses-title {
        margin: 0;
        font-size: clamp(1.45rem, 1.2rem + 0.8vw, 2rem);
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .guest-courses-subtitle {
        margin: 0.3rem 0 0;
        color: rgba(255, 255, 255, 0.84);
        max-width: 720px;
    }
    .guest-grid { --bs-gutter-x: 1rem; --bs-gutter-y: 1rem; }
    .guest-course-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.95rem;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        transition: transform .16s ease, box-shadow .16s ease, border-color .16s ease;
    }
    .guest-course-card:hover {
        transform: translateY(-2px);
        border-color: #cbd5e1;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.10);
    }
    .guest-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }
    .guest-course-card:hover .guest-thumb { transform: scale(1.02); }
    .guest-fallback {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        background: #e2e8f0;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .guest-card-body { padding: 0.85rem 0.95rem; }
    .guest-level {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: lowercase;
        letter-spacing: 0.01em;
        color: #334155;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 9999px;
        padding: 0.2rem 0.5rem;
        margin-bottom: 0.45rem;
    }
    .guest-course-title {
        margin: 0;
        color: #0f172a;
        font-size: 1.1rem;
        font-weight: 700;
        line-height: 1.3;
    }
    .guest-course-desc {
        margin: 0.45rem 0 0;
        color: #475569;
        font-size: 0.9rem;
        line-height: 1.45;
        min-height: 2.6em;
    }
    .guest-course-inst {
        margin: 0.45rem 0 0;
        font-size: 0.83rem;
        color: #64748b;
    }
    .guest-action-wrap {
        border-top: 1px solid #f1f5f9;
        padding: 0.75rem 0.95rem 0.9rem;
    }
    .guest-enroll-btn {
        border-radius: 0.55rem;
        font-weight: 700;
        padding: 0.4rem 0.8rem;
    }
    .guest-login-note {
        font-size: 0.76rem;
        color: #64748b;
        text-align: center;
        margin: 0.4rem 0 0;
    }
    .guest-empty {
        border: 1px dashed #cbd5e1;
        border-radius: 0.9rem;
        background: #f8fafc;
        color: #64748b;
    }
</style>
@endpush

@section('content')
    <div class="guest-courses-hero">
        <h1 class="guest-courses-title">Courses</h1>
        <p class="guest-courses-subtitle">Explore courses with short video lessons, quizzes, and assignments. Learn at your own pace with LU Academy.</p>
    </div>

    <div class="row guest-grid">
        @forelse ($courses as $course)
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="guest-course-card h-100 d-flex flex-column">
                    <a href="{{ route('courses.show', $course) }}" class="text-decoration-none text-dark">
                        <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                            @if ($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="guest-thumb">
                            @else
                                <span class="guest-fallback">
                                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/></svg>
                                </span>
                            @endif
                        </div>
                        <div class="guest-card-body">
                            <span class="guest-level">{{ strtolower((string) $course->level) }}</span>
                            <h5 class="guest-course-title">{{ $course->title }}</h5>
                            <p class="guest-course-desc">{{ Str::limit($course->description, 90) }}</p>
                            <p class="guest-course-inst">[CS] {{ $course->instructor->name }}</p>
                        </div>
                    </a>
                    <div class="guest-action-wrap mt-auto">
                        @if ($enrolledIds->contains($course->id))
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary btn-sm w-100 guest-enroll-btn">View Course</a>
                        @elseif (auth()->check())
                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm w-100 guest-enroll-btn">Enroll</button>
                            </form>
                        @else
                            <a href="{{ route('login', ['intended' => route('courses.show', $course)]) }}" class="btn btn-primary btn-sm w-100 guest-enroll-btn">Enroll</a>
                            <p class="guest-login-note">Log in or register to enroll</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="guest-empty text-center py-5">
                    <svg class="mb-2" width="46" height="46" fill="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                <p class="mb-0">@if(request('q'))No courses match "{{ request('q') }}".@else No courses yet. Courses will appear here once published.@endif</p>
                @if(request('q'))<a href="{{ route('courses.index') }}" class="text-primary small">Clear search</a>@endif
                </div>
            </div>
        @endforelse
    </div>

    @if ($courses->hasPages())
        <div class="mt-4">{{ $courses->links() }}</div>
    @endif
@endsection

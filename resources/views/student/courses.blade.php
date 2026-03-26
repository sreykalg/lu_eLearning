@extends('layouts.student-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .course-card-top { min-height: 80px; background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%); display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .course-level-badge { font-size: 0.65rem; font-weight: 600; padding: 0.25rem 0.5rem; border-radius: 0.25rem; text-transform: uppercase; }
    .course-level-beginner { background: #dbeafe; color: #1e40af; }
    .course-level-intermediate { background: #fef3c7; color: #b45309; }
    .course-level-advanced { background: #fce7f3; color: #9d174d; }
    .filter-pill { padding: 0.35rem 0.85rem; font-size: 0.875rem; border-radius: 9999px; text-decoration: none; border: 1px solid #e5e7eb; background: #fff; color: #64748b; transition: all 0.15s; }
    .filter-pill:hover { background: #f9fafb; color: #0f172a; }
    .filter-pill.active { background: #0f172a; color: #fff; border-color: #0f172a; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
        </div>
        <div>
            <h1 class="h3 hero-title">My Courses</h1>
            <p class="hero-subtitle">{{ $courses->count() }} enrolled courses</p>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background: #0f172a; color: #fff;">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        <span class="fw-bold">{{ $totalPoints }}</span>
        <span class="small opacity-75">points</span>
    </div>
</div>

{{-- Level filters only (search is in header) --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('student.courses', request()->except('level', 'search', 'page')) }}" class="filter-pill {{ !request('level') ? 'active' : '' }}">
        All
    </a>
    @foreach($levels as $key => $label)
        <a href="{{ route('student.courses', array_merge(request()->except('level', 'page'), ['level' => $key])) }}" class="filter-pill {{ request('level') === $key ? 'active' : '' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="row g-4">
    @forelse($courses as $course)
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('courses.show', $course) }}" class="text-decoration-none text-dark d-block h-100">
                <div class="rounded-3 bg-white shadow-sm border h-100 overflow-hidden">
                    <div class="course-card-top ratio ratio-16x9">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->title }}" class="object-fit-cover w-100 h-100">
                        @else
                            <div class="d-flex align-items-center justify-content-center">
                                <svg width="40" height="40" fill="none" stroke="#0f172a" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                            <span class="course-level-badge course-level-{{ $course->level ?? 'beginner' }}">{{ strtoupper($course->level ?? 'beginner') }}</span>
                            <span class="small text-muted">· {{ $course->course_points ?? 0 }} pts</span>
                        </div>
                        <h6 class="fw-semibold mb-1">{{ Str::limit($course->title, 45) }}</h6>
                        <p class="text-muted small mb-2">{{ $course->instructor->name ?? '—' }}</p>
                        <div class="d-flex align-items-center gap-2 small mb-2">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                            <span class="text-muted">{{ $course->progress_done }}/{{ $course->lessons_count }} lessons</span>
                        </div>
                        <div class="progress mb-0" style="height: 6px; background: #e5e7eb;">
                            <div class="progress-bar" style="width: {{ $course->progress_pct }}%; background: #0f172a;"></div>
                        </div>
                        <div class="small text-muted mt-1">{{ $course->progress_pct }}%</div>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="rounded-3 bg-white shadow-sm border p-5 text-center">
                <p class="text-muted mb-3">No courses match your filters. @if(request()->hasAny(['level','search']))<a href="{{ route('student.courses') }}" class="text-primary">Clear filters</a>@else You are not enrolled in any courses yet.@endif</p>
                @if(!request()->hasAny(['level','search']))
                    <a href="{{ route('courses.index') }}" class="btn btn-sm" style="background:#0f172a;color:#fff;">Browse courses</a>
                @endif
            </div>
        </div>
    @endforelse
</div>
@endsection

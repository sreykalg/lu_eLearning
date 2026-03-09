@extends('layouts.student-inner')

@push('styles')
<style>
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
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">My Courses</h1>
    <p class="text-muted mb-0">{{ $courses->count() }} enrolled courses</p>
</div>

{{-- Filters + Search --}}
<div class="d-flex flex-wrap align-items-center gap-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('student.courses', request()->except('level', 'search', 'page')) }}" class="filter-pill {{ !request('level') ? 'active' : '' }}">
            All
        </a>
        @foreach($levels as $key => $label)
            <a href="{{ route('student.courses', array_merge(request()->except('level', 'page'), ['level' => $key])) }}" class="filter-pill {{ request('level') === $key ? 'active' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
    <form action="{{ route('student.courses') }}" method="GET" class="d-flex gap-2 ms-auto">
        @if(request('level'))<input type="hidden" name="level" value="{{ request('level') }}">@endif
        <div class="input-group" style="max-width: 260px;">
            <span class="input-group-text bg-white border-end-0"><svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></span>
            <input type="search" name="search" class="form-control border-start-0" placeholder="Search courses..." value="{{ request('search') }}">
        </div>
    </form>
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

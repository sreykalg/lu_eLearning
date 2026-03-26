@extends('layouts.admin')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
</style>
@endpush

@section('content')
<div class="admin-page">
    <div class="page-hero">
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
            </div>
            <div>
                <h1 class="h3 hero-title">Courses</h1>
                <p class="hero-subtitle">All courses on the platform. View and monitor activity.</p>
            </div>
        </div>
    </div>

    <div class="rounded-3 p-4 mb-4" style="background: #fff; border: 1px solid rgba(45,27,78,0.08);">
        <form method="get" action="{{ route('admin.courses.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small text-muted mb-0">Search</label>
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Course title" value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-0">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-lu-primary btn-sm w-100">Filter</button>
            </div>
        </form>
    </div>

    <div class="row g-4">
        @forelse($courses as $course)
            <div class="col-sm-6 col-lg-4">
                <a href="{{ route('courses.show', $course) }}" class="text-decoration-none text-dark d-block h-100">
                    <div class="rounded-3 h-100 overflow-hidden" style="background: #fff; border: 1px solid rgba(45,27,78,0.08); transition: box-shadow 0.2s ease, border-color 0.2s ease;">
                        <div class="ratio ratio-16x9 bg-light" style="background: rgba(45,27,78,0.06) !important;">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="" class="object-fit-cover">
                            @else
                                <div class="d-flex align-items-center justify-content-center">
                                    <svg width="48" height="48" fill="none" stroke="var(--lu-deep-purple)" viewBox="0 0 24 24" style="opacity: 0.3;"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                <h6 class="mb-0 fw-semibold text-truncate" style="color: var(--lu-deep-purple);">{{ $course->title }}</h6>
                                @if($course->is_published)
                                    <span class="badge bg-success flex-shrink-0">Live</span>
                                @else
                                    <span class="badge bg-secondary flex-shrink-0">Draft</span>
                                @endif
                            </div>
                            <div class="text-muted small">{{ $course->instructor->name ?? '—' }}</div>
                            <div class="mt-2 text-muted small">{{ $course->lessons_count }} lessons · {{ $course->enrollments_count }} enrolled</div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="rounded-3 p-5 text-center" style="background: #fff; border: 1px solid rgba(45,27,78,0.08);">
                    <p class="text-muted mb-0">No courses match your filters.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($courses->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $courses->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

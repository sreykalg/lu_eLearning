@extends('layouts.instructor-inner')

@push('styles')
<style>
    .course-card-inner { transition: box-shadow 0.2s; }
    .course-card-inner:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .course-card-top { min-height: 120px; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .course-level-badge { font-size: 0.65rem; font-weight: 600; padding: 0.25rem 0.5rem; border-radius: 0.25rem; text-transform: uppercase; }
    .course-level-beginner { background: #dbeafe; color: #1e40af; }
    .course-level-intermediate { background: #fef3c7; color: #b45309; }
    .course-level-advanced { background: #fce7f3; color: #9d174d; }
</style>
@endpush

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1 class="h3 fw-bold mb-0">My Courses</h1>
    <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary btn-sm">Create Course</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    @forelse($courses as $course)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden course-card-inner">
                <a href="{{ route('instructor.courses.edit', $course) }}" class="text-decoration-none text-dark">
                    <div class="course-card-top ratio ratio-16x9">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="object-fit-cover w-100 h-100">
                        @else
                            <div class="d-flex align-items-center justify-content-center">
                                <svg width="48" height="48" fill="none" stroke="#64748b" stroke-width="1.5" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="course-level-badge course-level-{{ $course->level ?? 'beginner' }}">{{ strtoupper($course->level ?? 'beginner') }}</span>
                            @php
                                $statusLabel = match($course->approval_status ?? 'draft') {
                                    'pending' => 'Pending Review',
                                    'approved' => 'Active',
                                    'needs_revision' => 'Needs Revision',
                                    default => 'Draft',
                                };
                                $statusClass = match($course->approval_status ?? 'draft') {
                                    'approved' => 'bg-success',
                                    'pending' => 'bg-warning text-dark',
                                    'needs_revision' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                        <h5 class="card-title fw-semibold mb-2">{{ Str::limit($course->title, 50) }}</h5>
                        <p class="text-muted small mb-3">{{ $course->lessons_count }} lessons · {{ $course->quizzes_count }} quizzes · {{ $course->assignments_count }} assignments</p>
                        <span class="btn btn-sm btn-outline-dark">Edit Course</span>
                    </div>
                </a>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <svg class="mb-3 text-muted" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                    <p class="text-muted mb-4">No courses yet. Create your first course to get started.</p>
                    <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">Create Course</a>
                </div>
            </div>
        </div>
    @endforelse
</div>
@endsection

@extends('layouts.hod-inner')

@push('styles')
<style>
    .students-course-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1rem; transition: box-shadow 0.2s; }
    .students-course-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .students-course-card .course-icon { width: 48px; height: 48px; border-radius: 0.5rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .students-course-card .badge-enrolled { font-size: 0.8125rem; font-weight: 600; padding: 0.35rem 0.75rem; border-radius: 9999px; background: #dcfce7; color: #166534; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Student Enrollment</h1>
    <p class="text-muted mb-0">View enrolled students by course, manage enrollments, and track performance</p>
</div>

<div class="d-flex flex-column gap-3">
    @forelse($courses as $c)
        <div class="students-course-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="course-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">{{ $c->title }}</h6>
                        <p class="text-muted small mb-0">{{ $c->instructor->name ?? '—' }}</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge-enrolled">{{ $c->enrollments_count }} enrolled</span>
                    <a href="{{ route('hod.students.show', $c) }}" class="btn btn-outline-primary btn-sm">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        View Students
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-3 bg-white border p-5 text-center text-muted">
            No published courses with enrollments yet.
        </div>
    @endforelse
</div>
@endsection

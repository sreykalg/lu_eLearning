@extends('layouts.instructor-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-row { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.82); font-size: 0.9rem; }
    .my-course-card { border: 1px solid #e2e8f0; border-radius: 0.85rem; background: #fff; }
    .my-course-card.active { border-color: #0f172a; box-shadow: 0 6px 18px rgba(15,23,42,0.08); }
    .student-avatar { width: 34px; height: 34px; border-radius: 50%; background: #0f172a; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.78rem; }
    .student-table thead th { background: #f8fafc; color: #64748b; font-size: 0.74rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-row">
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 7a2 2 0 012-2h11a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/><path stroke-width="2" d="M18 10h1a2 2 0 012 2v5a2 2 0 01-2 2h-1"/></svg>
            </div>
            <div>
                <h1 class="h3 hero-title">My Courses</h1>
                <p class="hero-subtitle">All courses you created, with enrolled student management.</p>
            </div>
        </div>
        <a href="{{ route('instructor.courses.create') }}" class="btn btn-sm btn-outline-light">Create Course</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-warning">{{ session('error') }}</div>
@endif

<div class="row g-3 mb-3">
    @forelse($courses as $course)
        <div class="col-md-6 col-xl-4">
            <a href="{{ route('instructor.my-courses', ['course' => $course->slug]) }}" class="text-decoration-none text-dark">
                <div class="my-course-card p-3 h-100 {{ $selectedCourse && $selectedCourse->id === $course->id ? 'active' : '' }}">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h6 class="mb-0 fw-semibold">{{ Str::limit($course->title, 45) }}</h6>
                        <span class="badge bg-dark-subtle text-dark">{{ strtoupper($course->level ?? 'beginner') }}</span>
                    </div>
                    <p class="text-muted small mb-0">{{ $course->enrollments_count }} students · {{ $course->lessons_count }} lessons · {{ $course->quizzes_count }} quizzes · {{ $course->assignments_count }} assignments</p>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-5 text-center">
                    <p class="text-muted mb-3">No courses created yet.</p>
                    <a href="{{ route('instructor.courses.create') }}" class="btn btn-dark">Create first course</a>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($selectedCourse)
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">{{ $selectedCourse->title }} · Students</h5>
                <small class="text-muted">{{ $students->count() }} enrolled students</small>
            </div>
            <a href="{{ route('instructor.courses.edit', $selectedCourse) }}" class="btn btn-sm btn-outline-dark">Manage Course</a>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle student-table">
                <thead>
                    <tr>
                        <th class="px-3 py-3">Student</th>
                        <th class="px-3 py-3">Last Activity</th>
                        <th class="px-3 py-3 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $enrollment)
                        @php
                            $student = $enrollment->user;
                            $initials = collect(explode(' ', $student->name ?? 'U'))->filter()->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
                            $initials = $initials ? strtoupper($initials) : 'U';
                        @endphp
                        <tr>
                            <td class="px-3 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="student-avatar">{{ $initials }}</span>
                                    <div>
                                        <div class="fw-medium">{{ $student->name }}</div>
                                        <small class="text-muted">{{ $student->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-muted">
                                {{ $enrollment->last_activity_at ? $enrollment->last_activity_at->diffForHumans() : 'No activity yet' }}
                            </td>
                            <td class="px-3 py-3 text-end">
                                <form action="{{ route('instructor.my-courses.students.remove', [$selectedCourse, $student]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this student from the course?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">No students enrolled in this course yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

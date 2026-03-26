@extends('layouts.hod-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .reports-table { --reports-row-hover: rgba(15,23,42,0.03); }
    .reports-table .table { font-size: 0.9375rem; }
    .reports-table thead th { font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; background: #f8fafc; }
    .reports-table tbody tr { transition: background 0.15s; }
    .reports-table tbody tr:hover { background: var(--reports-row-hover); }
    .reports-table .course-cell { display: flex; align-items: center; gap: 0.75rem; }
    .reports-table .course-icon { width: 40px; height: 40px; border-radius: 0.5rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .reports-table .course-title { font-weight: 500; color: #0f172a; }
    .reports-table .badge-students { font-size: 0.8125rem; font-weight: 600; padding: 0.35rem 0.75rem; border-radius: 9999px; min-width: 2.5rem; text-align: center; }
    .reports-table .badge-students--high { background: #dcfce7; color: #166534; }
    .reports-table .badge-students--medium { background: #fef3c7; color: #b45309; }
    .reports-table .badge-students--low { background: #f1f5f9; color: #64748b; }
    .reports-table .view-link { font-size: 0.875rem; font-weight: 500; color: #0f172a; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; opacity: 0.85; }
    .reports-table .view-link:hover { opacity: 1; color: #0f172a; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div>
            <h1 class="h3 hero-title">Reports</h1>
            <p class="hero-subtitle">Platform analytics and performance data</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="rounded-3 p-4 bg-white shadow-sm border text-center">
            <h3 class="fw-bold mb-0">{{ $avgQuiz }}%</h3>
            <p class="text-muted small mb-0">Avg Quiz Score</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="rounded-3 p-4 bg-white shadow-sm border text-center">
            <h3 class="fw-bold mb-0">{{ $submissionRate }}%</h3>
            <p class="text-muted small mb-0">Assignment Submission Rate</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="rounded-3 p-4 bg-white shadow-sm border text-center">
            <h3 class="fw-bold mb-0">{{ $examAttempts }}/{{ max(1, $examQuizzes) }}</h3>
            <p class="text-muted small mb-0">Exams Completed</p>
        </div>
    </div>
</div>

<div class="rounded-3 bg-white shadow-sm border overflow-hidden reports-table">
    <div class="p-4 border-bottom bg-white">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="fw-semibold mb-0 d-flex align-items-center gap-2">
                <span class="rounded d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);color:#fff;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </span>
                Student Performance by Course
            </h5>
            <span class="text-muted small">{{ $courses->count() }} courses</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th class="py-3 px-4">Course</th>
                    <th class="py-3 px-4 text-center" style="width:100px;">Lessons</th>
                    <th class="py-3 px-4 text-end" style="width:120px;">Students</th>
                    <th class="py-3 px-4 text-end" style="width:100px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $c)
                    @php
                        $students = $c->enrollments_count ?? 0;
                        $badgeClass = $students >= 2 ? 'badge-students--high' : ($students >= 1 ? 'badge-students--medium' : 'badge-students--low');
                    @endphp
                    <tr>
                        <td class="px-4 py-3">
                            <div class="course-cell">
                                <div class="course-icon">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <span class="course-title">{{ $c->title }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-muted">{{ $c->lessons_count ?? 0 }}</td>
                        <td class="px-4 py-3 text-end">
                            <span class="badge-students {{ $badgeClass }}">{{ $students }}</span>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <a href="{{ route('hod.students.show', $c) }}" class="view-link">
                                View
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted text-center py-5">No published courses yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

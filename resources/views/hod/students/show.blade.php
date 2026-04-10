@extends('layouts.hod-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-row { display: flex; flex-direction: column; align-items: flex-start; gap: 0.75rem; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .page-hero .back-link { color: rgba(255,255,255,0.85); text-decoration: none; font-size: 0.85rem; }
    .page-hero .back-link:hover { color: #fff; }
    .hod-students-table thead th { font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; background: #f8fafc; }
    .hod-students-table tbody tr:hover { background: rgba(15,23,42,0.03); }
    .hod-students-table .student-cell { display: flex; align-items: center; gap: 0.75rem; }
    .hod-students-table .student-avatar { width: 40px; height: 40px; border-radius: 50%; background: #0f172a; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; font-weight: 600; flex-shrink: 0; }
    .hod-students-table .score-cell { font-weight: 600; min-width: 3rem; text-align: center; }
    .hod-students-table .score-cell--high { color: #166534; }
    .hod-students-table .score-cell--medium { color: #b45309; }
    .hod-students-table .score-cell--low { color: #64748b; }
    .hod-students-table .score-cell--empty { color: #94a3b8; font-weight: 400; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-row">
        <a href="{{ route('hod.students.index') }}" class="back-link d-inline-flex align-items-center gap-1">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to courses
        </a>
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
            </div>
            <div>
                <h1 class="h3 hero-title">{{ $course->title }}</h1>
                <p class="hero-subtitle">Enrolled students and performance · {{ $students->count() }} students</p>
            </div>
        </div>
    </div>
</div>

<div class="rounded-3 bg-white shadow-sm border overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0 align-middle hod-students-table">
            <thead>
                <tr>
                    <th class="py-3 px-4">Student</th>
                    <th class="py-3 px-4 text-center" style="width:90px;">Quiz</th>
                    <th class="py-3 px-4 text-center" style="width:100px;">Assignment</th>
                    <th class="py-3 px-4 text-center" style="width:90px;">Midterm</th>
                    <th class="py-3 px-4 text-center" style="width:90px;">Final</th>
                    <th class="py-3 px-4 text-center" style="width:90px;">Overall</th>
                    <th class="py-3 px-4 text-end" style="width:100px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                    @php
                        $initials = collect(explode(' ', $s->user->name ?? 'U'))->filter()->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
                        $initials = $initials ? strtoupper($initials) : 'U';
                        $scoreClass = fn ($v) => $v === null ? 'score-cell--empty' : ($v >= 70 ? 'score-cell--high' : ($v >= 50 ? 'score-cell--medium' : 'score-cell--low'));
                    @endphp
                    <tr>
                        <td class="px-4 py-3">
                            <div class="student-cell">
                                <div class="student-avatar">{{ $initials }}</div>
                                <div>
                                    <div class="fw-medium">{{ $s->user->name ?? 'Unknown' }}</div>
                                    <small class="text-muted">{{ $s->user->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="score-cell {{ $scoreClass($s->quiz_avg) }}">{{ $s->quiz_avg !== null ? $s->quiz_avg . '%' : '—' }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="score-cell {{ $scoreClass($s->assignment_avg) }}">{{ $s->assignment_avg !== null ? $s->assignment_avg . '%' : '—' }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="score-cell {{ $scoreClass($s->midterm) }}">{{ $s->midterm !== null ? $s->midterm . '%' : '—' }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="score-cell {{ $scoreClass($s->final) }}">{{ $s->final !== null ? $s->final . '%' : '—' }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="score-cell {{ $scoreClass($s->overall) }}">{{ $s->overall !== null ? $s->overall . '%' : '—' }}</span>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <!-- Remove Student Button -->
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#removeStudentModal" data-enrollment-id="{{ $s->enrollment->id }}" data-student-name="{{ e($s->user->name ?? 'Unknown') }}">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted text-center py-5">No students enrolled in this course.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 text-muted small">
    <strong>Score legend:</strong> Quiz = average of practice quizzes; Assignment = average of graded assignments; Midterm/Final = exam scores; Overall = average of available scores.
</div>

{{-- Remove student confirmation modal --}}
<div class="modal fade" id="removeStudentModal" tabindex="-1" aria-labelledby="removeStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('hod.students.remove') }}" method="POST" id="removeStudentForm">
                @csrf
                <input type="hidden" name="enrollment_id" id="removeEnrollmentId" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeStudentModalLabel">Remove student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to remove this student?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Remove</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('removeStudentModal').addEventListener('show.bs.modal', function(e) {
    var btn = e.relatedTarget;
    document.getElementById('removeEnrollmentId').value = btn.getAttribute('data-enrollment-id');
});
</script>
@endsection

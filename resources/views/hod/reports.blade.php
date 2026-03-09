@extends('layouts.hod-inner')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h1 class="h3 fw-bold mb-1">Reports</h1>
        <p class="text-muted mb-0">Platform analytics and performance data</p>
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

<div class="rounded-3 bg-white shadow-sm border overflow-hidden">
    <div class="p-4 border-bottom">
        <h5 class="fw-semibold mb-0">Student Performance by Course</h5>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 py-3">Course</th>
                    <th class="border-0 py-3">Students</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $c)
                    <tr>
                        <td>{{ $c->title }}</td>
                        <td>{{ $c->enrollments_count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-muted text-center py-4">No data yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Student Progress</h1>
    <p class="text-muted mb-0">{{ count($rows) }} students across your courses</p>
</div>

<div class="rounded-3 bg-white shadow-sm border overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 py-3">Student</th>
                    <th class="border-0 py-3">Course</th>
                    <th class="border-0 py-3">Lesson progress</th>
                    <th class="border-0 py-3">Assignments</th>
                    <th class="border-0 py-3">Quiz avg</th>
                    <th class="border-0 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr>
                        <td class="py-3">{{ $r->user->name }}</td>
                        <td class="py-3">{{ $r->course->title }}</td>
                        <td class="py-3">{{ $r->lesson_pct }}%</td>
                        <td class="py-3">{{ $r->assignments }}</td>
                        <td class="py-3">{{ $r->quiz_avg !== null ? $r->quiz_avg.'%' : '—' }}</td>
                        <td class="py-3">
                            @if($r->status === 'excellent')
                                <span class="text-success fw-medium">Excellent</span>
                            @elseif($r->status === 'on_track')
                                <span class="text-primary fw-medium">On Track</span>
                            @else
                                <span class="text-danger fw-medium">At Risk</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted text-center py-5">No enrolled students yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

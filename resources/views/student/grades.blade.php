@extends('layouts.student-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Grades</h1>
    <p class="text-muted mb-0">Your performance across courses</p>
</div>

@php
    $gradedSubs = $submissions->filter(fn($s) => $s->score !== null && $s->assignment && $s->assignment->max_score > 0);
    $allScores = $gradedSubs->map(fn($s) => ($s->score / $s->assignment->max_score) * 100)->concat($attempts->filter(fn($a) => $a->total_points > 0)->map(fn($a) => ($a->score / $a->total_points) * 100));
    $overall = $allScores->isEmpty() ? 0 : (int) round($allScores->avg());
@endphp

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="rounded-3 p-4 bg-white shadow-sm border text-center">
            <h3 class="fw-bold mb-0">{{ $overall }}%</h3>
            <p class="text-muted small mb-0">Overall average</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="rounded-3 p-4 bg-white shadow-sm border text-center">
            <h3 class="fw-bold mb-0">{{ $enrollments->count() }}</h3>
            <p class="text-muted small mb-0">Courses</p>
        </div>
    </div>
</div>

<div class="rounded-3 bg-white shadow-sm border p-4">
    <h5 class="fw-semibold mb-3">By course</h5>
    @forelse($enrollments as $e)
        <div class="border-bottom border-light pb-3 mb-3">
            <h6 class="fw-semibold">{{ $e->course->title }}</h6>
            <p class="text-muted small mb-0">View course for detailed grades</p>
        </div>
    @empty
        <p class="text-muted mb-0">No grades yet.</p>
    @endforelse
</div>
@endsection

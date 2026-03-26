@extends('layouts.hod-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-row { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .stat-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 0.85rem; padding: 1rem; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-row">
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
            </div>
            <div>
                <h1 class="h3 hero-title">{{ $student->name }}</h1>
                <p class="hero-subtitle">{{ $course->title }} · Progress details</p>
            </div>
        </div>
        <a href="{{ route('hod.monitoring.show', $course) }}" class="btn btn-sm btn-outline-light">Back to Students</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4 col-xl-2"><div class="stat-card"><small class="text-muted d-block">Quiz</small><div class="h5 mb-0">{{ $performance['quiz_avg'] !== null ? $performance['quiz_avg'].'%' : '—' }}</div></div></div>
    <div class="col-md-4 col-xl-2"><div class="stat-card"><small class="text-muted d-block">Assignment</small><div class="h5 mb-0">{{ $performance['assignment_avg'] !== null ? $performance['assignment_avg'].'%' : '—' }}</div></div></div>
    <div class="col-md-4 col-xl-2"><div class="stat-card"><small class="text-muted d-block">Midterm</small><div class="h5 mb-0">{{ $performance['midterm'] !== null ? $performance['midterm'].'%' : '—' }}</div></div></div>
    <div class="col-md-4 col-xl-2"><div class="stat-card"><small class="text-muted d-block">Final</small><div class="h5 mb-0">{{ $performance['final'] !== null ? $performance['final'].'%' : '—' }}</div></div></div>
    <div class="col-md-4 col-xl-2"><div class="stat-card"><small class="text-muted d-block">Attendance</small><div class="h5 mb-0">{{ $performance['attendance'] !== null ? $performance['attendance'].'%' : '—' }}</div></div></div>
    <div class="col-md-4 col-xl-2"><div class="stat-card"><small class="text-muted d-block">Overall</small><div class="h5 mb-0">{{ $performance['overall'] !== null ? $performance['overall'].'%' : '—' }}</div></div></div>
</div>
@endsection

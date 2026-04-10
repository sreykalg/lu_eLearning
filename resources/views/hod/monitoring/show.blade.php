@extends('layouts.hod-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-row { display: flex; flex-direction: column; align-items: flex-start; gap: 0.75rem; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .page-hero .back-link { color: rgba(255,255,255,0.88); text-decoration: none; font-size: 0.85rem; }
    .page-hero .back-link:hover { color: #fff; }
    .student-avatar { width: 34px; height: 34px; border-radius: 50%; background: #0f172a; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 700; }
    .btn-progress {
        border-color: #0f172a;
        color: #0f172a;
        font-weight: 600;
    }
    .btn-progress:hover,
    .btn-progress:focus {
        background: #0f172a;
        border-color: #0f172a;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-row">
        <a class="back-link" href="{{ route('hod.monitoring.index') }}">← Back to Course Monitoring</a>
        <div class="hero-left">
            <div class="hero-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <h1 class="h3 hero-title">{{ $course->title }}</h1>
                <p class="hero-subtitle">Students in this ongoing course</p>
            </div>
        </div>
    </div>
</div>

<div class="rounded-3 bg-white border shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="px-3 py-3">Student</th>
                    <th class="px-3 py-3">Overall</th>
                    <th class="px-3 py-3 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $s)
                    @php
                        $initials = collect(explode(' ', $s->user->name ?? 'U'))->filter()->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
                        $initials = $initials ? strtoupper($initials) : 'U';
                    @endphp
                    <tr>
                        <td class="px-3 py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="student-avatar">{{ $initials }}</span>
                                <div>
                                    <div class="fw-medium">{{ $s->user->name }}</div>
                                    <small class="text-muted">{{ $s->user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3">{{ $s->overall !== null ? $s->overall.'%' : '—' }}</td>
                        <td class="px-3 py-3 text-end">
                            <a href="{{ route('hod.monitoring.student', [$course, $s->user]) }}" class="btn btn-sm btn-outline-dark btn-progress">View Progress</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-muted text-center py-5">No students enrolled yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

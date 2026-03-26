@extends('layouts.hod-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .monitor-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 0.95rem; overflow: hidden; transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease; }
    .monitor-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(15,23,42,0.08); border-color: #cbd5e1; }
    .monitor-card-head {
        height: 84px;
        background:
            radial-gradient(circle at top right, rgba(59,130,246,0.28), transparent 58%),
            linear-gradient(135deg, #0f172a 0%, #1e293b 56%, #334155 100%);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 0.8rem;
    }
    .monitor-level-chip { font-size: 0.68rem; letter-spacing: 0.03em; font-weight: 700; border-radius: 9999px; padding: 0.25rem 0.55rem; background: rgba(255,255,255,0.16); color: #fff; }
    .monitor-live-chip { font-size: 0.68rem; font-weight: 700; border-radius: 9999px; padding: 0.25rem 0.55rem; background: #dcfce7; color: #166534; }
    .monitor-card-body { padding: 0.9rem 1rem 1rem; }
    .monitor-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0 0 0.15rem; }
    .monitor-instructor { color: #64748b; font-size: 0.83rem; margin: 0 0 0.6rem; }
    .monitor-meta { display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; margin-bottom: 0.75rem; }
    .monitor-meta-chip { border-radius: 9999px; border: 1px solid #e2e8f0; background: #f8fafc; color: #475569; font-size: 0.75rem; padding: 0.2rem 0.5rem; font-weight: 600; }
    .monitor-action-btn { border-radius: 0.55rem; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 7a2 2 0 012-2h11a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/><path stroke-width="2" d="M19 8v8M15 12h8"/></svg>
        </div>
        <div>
            <h1 class="h3 hero-title">Course Monitoring</h1>
            <p class="hero-subtitle">Track ongoing courses and inspect each student progress.</p>
        </div>
    </div>
</div>

<div class="row g-3">
    @forelse($courses as $course)
        <div class="col-md-6 col-xl-4">
            <div class="monitor-card h-100 d-flex flex-column">
                <div class="monitor-card-head">
                    <span class="monitor-level-chip">{{ strtoupper($course->level ?? 'beginner') }}</span>
                    <span class="monitor-live-chip">ONGOING</span>
                </div>
                <div class="monitor-card-body d-flex flex-column flex-grow-1">
                    <h6 class="monitor-title">{{ Str::limit($course->title, 52) }}</h6>
                    <p class="monitor-instructor">Instructor: {{ $course->instructor->name ?? '—' }}</p>
                    <div class="monitor-meta">
                        <span class="monitor-meta-chip">{{ $course->enrollments_count }} students</span>
                        <span class="monitor-meta-chip">Approved</span>
                    </div>
                    <a href="{{ route('hod.monitoring.show', $course) }}" class="btn btn-sm btn-outline-dark monitor-action-btn mt-auto">View Students</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="rounded-3 bg-white border p-5 text-center text-muted">No ongoing courses available.</div>
        </div>
    @endforelse
</div>
@endsection

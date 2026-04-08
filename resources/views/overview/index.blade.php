@extends($layout)

@push('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
        border-radius: 1rem;
        padding: 1.25rem 1.4rem;
        color: #fff;
        margin-bottom: 1rem;
    }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .ov-card {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        height: 100%;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }
    .ov-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
        border-color: #cbd5e1;
    }
    .ov-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .ov-card-body { padding: 0.9rem 0.95rem 0.95rem; }
    .ov-level {
        display: inline-flex;
        align-items: center;
        border-radius: 9999px;
        background: #eef2ff;
        color: #3730a3;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.22rem 0.58rem;
        text-transform: capitalize;
        margin-bottom: 0.55rem;
    }
    .ov-title {
        color: #0f172a;
        font-weight: 800;
        font-size: 1.35rem;
        letter-spacing: -0.015em;
        line-height: 1.2;
        margin: 0 0 0.45rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .ov-desc {
        color: #64748b;
        font-size: 0.86rem;
        margin: 0 0 0.6rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3.7em;
    }
    .ov-inst {
        color: #475569;
        font-size: 0.82rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .ov-foot {
        margin-top: auto;
        padding: 0 0.95rem 0.95rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ov-status {
        display: inline-flex;
        align-items: center;
        border-radius: 9999px;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.25rem 0.58rem;
    }
    .ov-status--enrolled {
        background: rgba(15, 23, 42, 0.12);
        color: #0f172a;
    }
    .ov-status--open {
        background: #dcfce7;
        color: #166534;
    }
    .ov-arrow { color: #64748b; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
            <h1 class="h3 hero-title">Course Overview</h1>
            <p class="hero-subtitle">All published courses</p>
        </div>
    </div>
</div>

<div class="row g-4">
    @forelse ($courses as $course)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <a href="{{ route('courses.show', $course) }}" class="ov-card">
                <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                    @if ($course->thumbnail)
                        <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->title }}" class="ov-thumb">
                    @else
                        <svg class="text-secondary" width="48" height="48" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/></svg>
                    @endif
                </div>
                <div class="ov-card-body">
                    <span class="ov-level">{{ $course->level }}</span>
                    <h5 class="ov-title">{{ $course->title }}</h5>
                    <p class="ov-desc">{{ Str::limit($course->description, 120) }}</p>
                    <p class="ov-inst">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4" stroke-width="2"/></svg>
                        {{ $course->instructor->name ?? '—' }}
                    </p>
                </div>
                <div class="ov-foot">
                    @if ($enrolledIds->contains($course->id))
                        <span class="ov-status ov-status--enrolled">Enrolled</span>
                    @else
                        <span class="ov-status ov-status--open">Open</span>
                    @endif
                    <svg class="ov-arrow" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="rounded-3 bg-white shadow-sm border p-5 text-center">
                <svg class="mb-2 text-muted" width="48" height="48" fill="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                <p class="text-muted mb-0">No published courses yet.</p>
            </div>
        </div>
    @endforelse
</div>

@if ($courses->hasPages())
    <div class="mt-4">{{ $courses->links() }}</div>
@endif
@endsection

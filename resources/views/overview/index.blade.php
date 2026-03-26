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
            <a href="{{ route('courses.show', $course) }}" class="card text-decoration-none border-0 shadow-sm h-100 overflow-hidden">
                <div class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center">
                    @if ($course->thumbnail)
                        <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->title }}" class="object-fit-cover">
                    @else
                        <svg class="text-secondary" width="48" height="48" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v12H4V6zm2 2v8l6-4 6 4V8H6z"/></svg>
                    @endif
                </div>
                <div class="card-body">
                    <span class="badge bg-light text-dark mb-2">{{ $course->level }}</span>
                    <h5 class="card-title text-dark">{{ $course->title }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($course->description, 80) }}</p>
                    <p class="small text-muted mb-2">{{ $course->instructor->name ?? '—' }}</p>
                    @if ($enrolledIds->contains($course->id))
                        <span class="badge rounded-pill" style="background: rgba(15,23,42,0.15); color: #0f172a;">Enrolled</span>
                    @endif
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

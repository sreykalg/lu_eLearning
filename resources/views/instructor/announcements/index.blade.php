@extends('layouts.instructor-inner')

@push('styles')
<style>
    .ann-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
        border-radius: 1rem;
        padding: 1.4rem 1.5rem;
        color: #fff;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .ann-hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .ann-hero-icon {
        width: 46px;
        height: 46px;
        border-radius: 0.75rem;
        background: rgba(255,255,255,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex-shrink: 0;
    }
    .ann-hero-title { margin: 0; font-weight: 700; }
    .ann-hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.78); font-size: 0.9rem; }
    .ann-hero-btn { border-color: rgba(255,255,255,0.35); color: #fff; }
    .ann-hero-btn:hover { background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.5); }
    .ann-card { border: 1px solid #e2e8f0; border-radius: 0.75rem; background: #fff; max-width: 1000px; }
    .ann-card + .ann-card { margin-top: 0.75rem; }
    .ann-header-title { font-weight: 700; color: #0f172a; margin-bottom: 0.25rem; }
    .ann-course { color: #64748b; font-size: 0.85rem; }
    .ann-body { color: #334155; white-space: pre-wrap; margin: 0.5rem 0 0; font-size: 0.92rem; }
    .ann-meta { color: #64748b; font-size: 0.82rem; display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap; }
    .ann-meta-dot { width: 4px; height: 4px; border-radius: 999px; background: #cbd5e1; display: inline-block; }
    .ann-action-wrap { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.65rem; padding: 0.55rem; margin-top: 0.7rem; }
    .ann-action-title { font-size: 0.78rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .ann-expiry-input { max-width: 210px; }
</style>
@endpush

@section('content')
<div class="ann-hero">
    <div class="ann-hero-left">
        <div class="ann-hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        </div>
        <div>
            <h1 class="h3 ann-hero-title">Announcements</h1>
            <p class="ann-hero-subtitle">Create and manage course announcements for your students</p>
        </div>
    </div>
    <a href="{{ route('instructor.announcements.create') }}" class="btn ann-hero-btn">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        New Announcement
    </a>
</div>

<div class="card border-0 shadow-sm p-2 p-md-3">
    @forelse($announcements as $a)
        <div class="ann-card p-3">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                <div class="min-w-0 flex-grow-1">
                    <h5 class="ann-header-title">{{ $a->title }}</h5>
                    <div class="ann-course">{{ $a->course->title }}</div>
                    <p class="ann-body">{{ $a->body }}</p>
                    <div class="ann-meta mt-2">
                        <span>Posted {{ $a->created_at->format('M j, Y \a\t g:i A') }}</span>
                        <span class="ann-meta-dot"></span>
                        @if($a->expires_at)
                            <span>Expires {{ $a->expires_at->format('M j, Y \a\t g:i A') }}</span>
                            @if($a->expires_at->isPast())
                                <span class="badge bg-secondary">Expired</span>
                            @else
                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">Active</span>
                            @endif
                        @else
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">No Expiry</span>
                        @endif
                    </div>
                </div>
                <div class="ann-action-wrap w-100">
                    <div class="ann-action-title">Actions</div>
                    <div class="d-flex gap-2 align-items-end flex-wrap">
                        <form action="{{ route('instructor.announcements.update', $a) }}" method="POST" class="d-flex gap-2 align-items-end flex-wrap">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="form-label small mb-1">Update Expiry</label>
                                <input
                                    type="datetime-local"
                                    name="expires_at"
                                    class="form-control form-control-sm ann-expiry-input"
                                    value="{{ $a->expires_at ? $a->expires_at->format('Y-m-d\TH:i') : '' }}"
                                >
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </form>
                        <form action="{{ route('instructor.announcements.destroy', $a) }}" method="POST" onsubmit="return confirm('Remove this announcement?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card-body text-center py-5 text-muted">
            <p class="mb-3">No announcements yet.</p>
            <a href="{{ route('instructor.announcements.create') }}" class="btn btn-outline-danger">Post your first announcement</a>
        </div>
    @endforelse
</div>

@if($announcements->hasPages())
    <div class="mt-3">{{ $announcements->links() }}</div>
@endif
@endsection

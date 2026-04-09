@extends('layouts.instructor-inner')

@push('styles')
<style>
    .annx-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%);
        border-radius: 1rem;
        padding: 1.35rem 1.5rem;
        color: #fff;
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2);
    }
    .annx-hero-left { display: flex; align-items: center; gap: 0.95rem; }
    .annx-hero-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.85rem;
        background: rgba(255,255,255,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex-shrink: 0;
    }
    .annx-hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .annx-hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.84); font-size: 0.9rem; }
    .annx-hero-btn { border-color: rgba(255,255,255,0.35); color: #fff; border-radius: 0.65rem; font-weight: 700; }
    .annx-hero-btn:hover { background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.5); }
    .annx-list {
        border: 0;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
        padding: 0;
        width: 100%;
    }
    .annx-item {
        border: 1px solid #e5e7eb;
        border-left: 4px solid #0f172a;
        border-radius: 0.8rem;
        background: #fff;
        padding: 0.95rem 1rem;
    }
    .annx-item + .annx-item { margin-top: 0.75rem; }
    .annx-item-grid {
        display: flex;
        flex-direction: column;
        gap: 0.7rem;
        align-items: start;
    }
    .annx-content { min-width: 0; }
    .annx-title { font-weight: 800; color: #0f172a; margin-bottom: 0.2rem; letter-spacing: -0.01em; }
    .annx-course { color: #64748b; font-size: 0.84rem; margin-bottom: 0.5rem; }
    .annx-body { color: #334155; white-space: pre-wrap; margin: 0; font-size: 0.9rem; }
    .annx-meta { color: #64748b; font-size: 0.8rem; display: flex; align-items: center; gap: 0.45rem; flex-wrap: wrap; margin-top: 0.6rem; }
    .annx-meta-dot { width: 4px; height: 4px; border-radius: 999px; background: #cbd5e1; display: inline-block; }
    .annx-actions {
        width: 100%;
        padding-top: 0.6rem;
        border-top: 1px dashed #e2e8f0;
    }
    .annx-actions-title {
        font-size: 0.74rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.45rem;
    }
    .annx-expiry-input { max-width: 210px; }
    .annx-action-stack { display: flex; gap: 0.7rem; align-items: end; justify-content: space-between; flex-wrap: wrap; }
    .annx-action-row { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: end; }
    .annx-update-form { display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: end; }
    .annx-field-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .annx-empty {
        text-align: center;
        padding: 2.75rem 1.5rem;
        color: #64748b;
    }
    .annx-empty svg { opacity: 0.45; margin-bottom: 0.75rem; }
    @media (max-width: 991.98px) {
        .annx-item-grid { grid-template-columns: minmax(0, 1fr); }
    }
</style>
@endpush

@section('content')
<div class="annx-hero">
    <div class="annx-hero-left">
        <div class="annx-hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        </div>
        <div>
            <h1 class="h3 annx-hero-title">Announcements</h1>
            <p class="annx-hero-subtitle">Create and manage course announcements for your students</p>
        </div>
    </div>
    <a href="{{ route('instructor.announcements.create') }}" class="btn annx-hero-btn">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        New Announcement
    </a>
</div>

<div class="annx-list">
    @forelse($announcements as $a)
        <div class="annx-item">
            <div class="annx-item-grid">
                <div class="annx-content">
                    <h5 class="annx-title">{{ $a->title }}</h5>
                    <div class="annx-course">{{ $a->course->title }}</div>
                    <p class="annx-body">{{ $a->body }}</p>
                    <div class="annx-meta">
                        <span>Posted {{ $a->created_at->format('M j, Y \a\t g:i A') }}</span>
                        <span class="annx-meta-dot"></span>
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
                <div class="annx-actions">
                    <div class="annx-action-stack">
                        <form action="{{ route('instructor.announcements.update', $a) }}" method="POST" class="annx-update-form">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="annx-field-label">Expiry</label>
                                <input
                                    type="datetime-local"
                                    name="expires_at"
                                    class="form-control form-control-sm annx-expiry-input"
                                    value="{{ $a->expires_at ? $a->expires_at->format('Y-m-d\TH:i') : '' }}"
                                >
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </form>
                        <div class="annx-action-row">
                            <form action="{{ route('instructor.announcements.destroy', $a) }}" method="POST" onsubmit="return confirm('Remove this announcement?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="annx-empty">
            <svg width="42" height="42" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            <p class="fw-semibold text-secondary mb-2">No announcements yet</p>
            <a href="{{ route('instructor.announcements.create') }}" class="btn btn-outline-danger btn-sm">Post your first announcement</a>
        </div>
    @endforelse
 </div>

@if($announcements->hasPages())
    <div class="mt-3">{{ $announcements->links() }}</div>
@endif
@endsection

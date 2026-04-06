@extends('layouts.hod-inner')

@push('styles')
<style>
    .hod-apv-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 1.35rem 1.5rem;
        color: #fff;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2);
    }
    .hod-apv-hero .hero-left { display: flex; align-items: center; gap: 1rem; }
    .hod-apv-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .hod-apv-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .hod-apv-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; max-width: 36rem; line-height: 1.45; }
    .hod-apv-tabs-wrap {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .hod-apv-tabs {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 0;
        padding: 0 0.5rem;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }
    .hod-apv-tabs::-webkit-scrollbar { height: 4px; }
    .hod-apv-tabs::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    .hod-apv-tab {
        flex: 0 0 auto;
        padding: 0.9rem 1.1rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -1px;
        transition: color 0.15s, border-color 0.15s;
        white-space: nowrap;
    }
    .hod-apv-tab:hover { color: #0f172a; }
    .hod-apv-tab.active {
        color: #0f172a;
        border-bottom-color: #0f172a;
    }
    .hod-apv-tab .count {
        font-weight: 700;
        opacity: 0.85;
    }
    .hod-apv-card {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        padding: 1.25rem 1.35rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        transition: box-shadow 0.2s, transform 0.15s;
    }
    .hod-apv-card:hover {
        box-shadow: 0 8px 32px rgba(15, 23, 42, 0.1);
        transform: translateY(-1px);
    }
    .hod-apv-card__title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 0.35rem;
        line-height: 1.35;
    }
    .hod-apv-card__instructor {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }
    .hod-apv-card__meta {
        font-size: 0.8125rem;
        color: #64748b;
        margin: 0;
        line-height: 1.45;
    }
    .hod-apv-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        font-size: 0.72rem;
        font-weight: 700;
        border-radius: 9999px;
        letter-spacing: 0.02em;
    }
    .hod-apv-badge--pending { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .hod-apv-badge--approved { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .hod-apv-badge--needs-revision { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
    .hod-apv-badge--draft { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
    .hod-apv-btn-preview {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.9rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 0.65rem;
        border: 1px solid #0f172a;
        color: #0f172a;
        background: #fff;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
    }
    .hod-apv-btn-preview:hover {
        background: #0f172a;
        color: #fff;
    }
    .hod-apv-empty {
        text-align: center;
        padding: 3rem 1.5rem;
        border-radius: 1rem;
        border: 1px dashed #cbd5e1;
        background: #fafbfc;
        color: #64748b;
    }
    .hod-apv-empty svg { opacity: 0.35; margin-bottom: 0.75rem; color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="hod-apv-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
            <h1 class="hero-title">Course approval</h1>
            <p class="hero-subtitle">Review and approve courses submitted by instructors.</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 rounded-3 shadow-sm alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="hod-apv-tabs-wrap">
    <nav class="hod-apv-tabs border-bottom" aria-label="Approval status">
        <a href="{{ route('hod.approval', request()->except('status', 'page')) }}" class="hod-apv-tab {{ !request('status') ? 'active' : '' }}">
            All <span class="count">({{ $counts['all'] }})</span>
        </a>
        <a href="{{ route('hod.approval', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" class="hod-apv-tab {{ request('status') === 'pending' ? 'active' : '' }}">
            Pending <span class="count">({{ $counts['pending'] }})</span>
        </a>
        <a href="{{ route('hod.approval', array_merge(request()->except('status', 'page'), ['status' => 'approved'])) }}" class="hod-apv-tab {{ request('status') === 'approved' ? 'active' : '' }}">
            Approved <span class="count">({{ $counts['approved'] }})</span>
        </a>
        <a href="{{ route('hod.approval', array_merge(request()->except('status', 'page'), ['status' => 'needs_revision'])) }}" class="hod-apv-tab {{ request('status') === 'needs_revision' ? 'active' : '' }}">
            Needs revision <span class="count">({{ $counts['needs_revision'] }})</span>
        </a>
    </nav>
</div>

<div class="d-flex flex-column">
    @forelse($courses as $c)
        <div class="hod-apv-card">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div class="flex-grow-1 min-w-0">
                    <h2 class="hod-apv-card__title">{{ $c->title }}</h2>
                    <p class="hod-apv-card__instructor mb-0">{{ $c->instructor->name ?? '—' }}</p>
                    <p class="hod-apv-card__meta mt-2 mb-0">
                        @if($c->approval_status === 'approved' && $c->approved_at)
                            Approval date: {{ $c->approved_at->format('M j, Y') }}
                        @else
                            Submission date: {{ ($c->submitted_at ?? $c->updated_at)->format('M j, Y') }}
                        @endif
                        · {{ $c->modules_count ?? 0 }} {{ Str::plural('module', $c->modules_count ?? 0) }} · {{ $c->lessons_count }} {{ Str::plural('lesson', $c->lessons_count) }}
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                    @php
                        $statusLabel = match($c->approval_status ?? 'draft') {
                            'pending' => 'Pending review',
                            'approved' => 'Approved',
                            'needs_revision' => 'Needs revision',
                            default => 'Draft',
                        };
                        $statusMod = match($c->approval_status ?? 'draft') {
                            'pending' => 'pending',
                            'approved' => 'approved',
                            'needs_revision' => 'needs-revision',
                            default => 'draft',
                        };
                    @endphp
                    <span class="hod-apv-badge hod-apv-badge--{{ $statusMod }}">{{ $statusLabel }}</span>
                    <a href="{{ route('courses.show', $c) }}" target="_blank" rel="noopener noreferrer" class="hod-apv-btn-preview">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Preview
                    </a>
                    @if($c->approval_status === 'pending')
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#requestChangesModal{{ $c->id }}">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Request changes
                        </button>
                        <form action="{{ route('hod.approval.approve', $c) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-dark btn-sm rounded-3 px-3 fw-semibold">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Approve
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        @if($c->approval_status === 'pending')
        <div class="modal fade" id="requestChangesModal{{ $c->id }}" tabindex="-1" aria-labelledby="requestChangesModalLabel{{ $c->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <form action="{{ route('hod.approval.request-changes', $c) }}" method="POST">
                        @csrf
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold" id="requestChangesModalLabel{{ $c->id }}">Request changes</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body pt-2">
                            <p class="text-muted small mb-3">Feedback for <strong>{{ $c->title }}</strong>. The instructor will see this and can resubmit after revisions.</p>
                            <label class="form-label fw-semibold">Revision notes</label>
                            <textarea name="revision_notes" class="form-control rounded-3" rows="4" required placeholder="Describe what should change…"></textarea>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning rounded-3 fw-semibold px-4">Send request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @empty
        <div class="hod-apv-empty">
            <svg width="52" height="52" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="fw-semibold text-secondary mb-1">Nothing to show</p>
            <p class="small mb-0">No courses match this filter, or nothing has been submitted for approval yet.</p>
        </div>
    @endforelse
</div>
@endsection

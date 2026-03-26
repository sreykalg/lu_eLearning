@extends('layouts.hod-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .approval-tab { padding: 0.5rem 1rem; font-size: 0.9375rem; font-weight: 500; color: #64748b; text-decoration: none; border-bottom: 2px solid transparent; transition: color 0.15s; }
    .approval-tab:hover { color: #0f172a; }
    .approval-tab.active { color: #0f172a; border-bottom-color: #0f172a; }
    .approval-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1.25rem; margin-bottom: 1rem; transition: box-shadow 0.2s; }
    .approval-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .approval-status { font-size: 0.75rem; font-weight: 600; padding: 0.25rem 0.6rem; border-radius: 0.25rem; }
    .approval-status-pending { background: #fff7ed; color: #c2410c; }
    .approval-status-approved { background: #dcfce7; color: #166534; }
    .approval-status-needs-revision { background: #fee2e2; color: #b91c1c; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
        </div>
        <div>
            <h1 class="h3 hero-title">Course Approval</h1>
            <p class="hero-subtitle">Review and approve courses submitted by instructors</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Status tabs --}}
<div class="d-flex gap-1 mb-4 border-bottom">
    <a href="{{ route('hod.approval', request()->except('status', 'page')) }}" class="approval-tab {{ !request('status') ? 'active' : '' }}">All ({{ $counts['all'] }})</a>
    <a href="{{ route('hod.approval', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" class="approval-tab {{ request('status') === 'pending' ? 'active' : '' }}">Pending ({{ $counts['pending'] }})</a>
    <a href="{{ route('hod.approval', array_merge(request()->except('status', 'page'), ['status' => 'approved'])) }}" class="approval-tab {{ request('status') === 'approved' ? 'active' : '' }}">Approved ({{ $counts['approved'] }})</a>
    <a href="{{ route('hod.approval', array_merge(request()->except('status', 'page'), ['status' => 'needs_revision'])) }}" class="approval-tab {{ request('status') === 'needs_revision' ? 'active' : '' }}">Needs Revision ({{ $counts['needs_revision'] }})</a>
</div>

<div class="d-flex flex-column gap-3">
    @forelse($courses as $c)
        <div class="approval-card">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                <div>
                    <h6 class="fw-semibold mb-1">{{ $c->title }}</h6>
                    <p class="text-muted small mb-1">{{ $c->instructor->name ?? '—' }}</p>
                    <p class="text-muted small mb-0">
                        @if($c->approval_status === 'approved' && $c->approved_at)
                            Approval date: {{ $c->approved_at->format('M j, Y') }}
                        @else
                            Submission date: {{ ($c->submitted_at ?? $c->updated_at)->format('M j, Y') }}
                        @endif
                        · {{ $c->modules_count ?? 0 }} chapters - {{ $c->lessons_count }} lessons
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @php
                        $statusLabel = match($c->approval_status ?? 'draft') {
                            'pending' => 'Pending Review',
                            'approved' => 'Approved',
                            'needs_revision' => 'Needs Revision',
                            default => 'Draft',
                        };
                        $statusClass = match($c->approval_status ?? 'draft') {
                            'pending' => 'approval-status-pending',
                            'approved' => 'approval-status-approved',
                            'needs_revision' => 'approval-status-needs-revision',
                            default => 'bg-secondary text-white',
                        };
                    @endphp
                    <span class="approval-status {{ $statusClass }}">{{ $statusLabel }}</span>
                    <a href="{{ route('courses.show', $c) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Preview
                    </a>
                    @if($c->approval_status === 'pending')
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#requestChangesModal{{ $c->id }}">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Request Changes
                        </button>
                        <form action="{{ route('hod.approval.approve', $c) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Approve
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Request Changes Modal --}}
        @if($c->approval_status === 'pending')
        <div class="modal fade" id="requestChangesModal{{ $c->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('hod.approval.request-changes', $c) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Request Changes</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted small mb-2">Provide feedback for <strong>{{ $c->title }}</strong>. The instructor will see this and can resubmit after making revisions.</p>
                            <label class="form-label">Revision notes</label>
                            <textarea name="revision_notes" class="form-control" rows="4" required placeholder="Describe what changes are needed..."></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning">Request Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @empty
        <div class="rounded-3 bg-white border p-5 text-center text-muted">
            No courses to review.
        </div>
    @endforelse
</div>
@endsection

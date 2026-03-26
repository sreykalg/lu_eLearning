@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h1 class="h3 fw-bold mb-1">Announcements</h1>
        <p class="text-muted mb-0">All announcements you've posted</p>
    </div>
    <a href="{{ route('instructor.announcements.create') }}" class="btn btn-outline-danger">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        New Announcement
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    @forelse($announcements as $a)
        <div class="border-bottom border-light p-4">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <div class="min-w-0">
                    <h5 class="mb-1 fw-semibold">{{ $a->title }}</h5>
                    <p class="text-muted small mb-2">{{ $a->course->title }}</p>
                    <p class="mb-0 text-secondary" style="white-space: pre-wrap;">{{ $a->body }}</p>
                    <div class="mt-2 d-flex flex-wrap align-items-center gap-2">
                        <small class="text-muted">{{ $a->created_at->format('M j, Y \a\t g:i A') }}</small>
                        @if($a->expires_at)
                            <small class="text-muted">· Expires {{ $a->expires_at->format('M j, Y \a\t g:i A') }}</small>
                            @if($a->expires_at->isPast())
                                <span class="badge bg-secondary">Expired</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        @else
                            <span class="badge bg-info text-dark">No Expiry</span>
                        @endif
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

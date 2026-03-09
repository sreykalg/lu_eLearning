@extends('layouts.hod-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Course Approval</h1>
    <p class="text-muted mb-0">Review and approve courses submitted by instructors</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="rounded-3 bg-white shadow-sm border overflow-hidden">
    @forelse($courses as $c)
        <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="fw-semibold mb-0">{{ $c->title }}</h6>
                <p class="text-muted small mb-1">{{ $c->instructor->name ?? '—' }} · {{ $c->updated_at->format('M j, Y') }}</p>
                <span class="badge {{ $c->is_published ? 'bg-success' : 'bg-warning text-dark' }}">{{ $c->is_published ? 'Approved' : 'Pending Review' }}</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('courses.show', $c) }}" class="btn btn-outline-primary btn-sm">Preview</a>
                @if(!$c->is_published)
                    <form action="{{ route('hod.approval.approve', $c) }}" method="post" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">Approve</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="p-5 text-center text-muted">No courses to review.</div>
    @endforelse
</div>
@endsection

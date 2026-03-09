@extends('layouts.student-inner')

@push('styles')
<style>
    .assignment-card { transition: box-shadow 0.2s; }
    .assignment-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .filter-tab { padding: 0.4rem 1rem; font-size: 0.9rem; border-radius: 0.375rem; text-decoration: none; color: #64748b; border: 1px solid #e5e7eb; background: #fff; transition: all 0.15s; }
    .filter-tab:hover { background: #f9fafb; color: #0f172a; }
    .filter-tab.active { background: #0f172a; color: #fff; border-color: #0f172a; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Assignments</h1>
    @php
        $pendingCount = $assignments->filter(fn($a) => !$submissions->has($a->id))->count();
        $submittedCount = $assignments->filter(fn($a) => $submissions->has($a->id) && !($submissions->get($a->id)?->isGraded()))->count();
        $gradedCount = $assignments->filter(fn($a) => ($submissions->get($a->id)?->isGraded() ?? false))->count();
        $filter = request('filter', 'all');
        $filtered = match($filter) {
            'pending' => $assignments->filter(fn($a) => !$submissions->has($a->id)),
            'submitted' => $assignments->filter(fn($a) => $submissions->has($a->id) && !($submissions->get($a->id)?->isGraded())),
            'graded' => $assignments->filter(fn($a) => ($submissions->get($a->id)?->isGraded() ?? false)),
            default => $assignments,
        };
    @endphp
    <p class="text-muted mb-3">{{ $pendingCount }} pending · {{ $gradedCount }} graded</p>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('student.assignments', array_merge(request()->except('filter'), ['filter' => 'all'])) }}" class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">All ({{ $assignments->count() }})</a>
        <a href="{{ route('student.assignments', array_merge(request()->except('filter'), ['filter' => 'pending'])) }}" class="filter-tab {{ $filter === 'pending' ? 'active' : '' }}">Pending ({{ $pendingCount }})</a>
        <a href="{{ route('student.assignments', array_merge(request()->except('filter'), ['filter' => 'submitted'])) }}" class="filter-tab {{ $filter === 'submitted' ? 'active' : '' }}">Submitted ({{ $submittedCount }})</a>
        <a href="{{ route('student.assignments', array_merge(request()->except('filter'), ['filter' => 'graded'])) }}" class="filter-tab {{ $filter === 'graded' ? 'active' : '' }}">Graded ({{ $gradedCount }})</a>
    </div>
</div>

<div class="d-flex flex-column gap-3">
    @forelse($filtered as $a)
        @php $sub = $submissions->get($a->id); $isGraded = $sub?->isGraded() ?? false; $isSubmitted = $sub !== null; $canSubmit = $a->canSubmit(); @endphp
        <a href="{{ route('student.assignments.show', [$a->course, $a]) }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm assignment-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                @if($isGraded)
                                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: #dcfce7;">
                                        <svg width="14" height="14" fill="#16a34a" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                    </span>
                                @elseif(!$canSubmit && !$isSubmitted)
                                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: #f1f5f9;">
                                        <svg width="14" height="14" fill="none" stroke="#64748b" viewBox="0 0 24 24"><path stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    </span>
                                @else
                                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: #fef3c7;">
                                        <svg width="14" height="14" fill="none" stroke="#b45309" viewBox="0 0 24 24"><path stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                @endif
                                <span class="badge {{ $isGraded ? 'bg-success' : ($isSubmitted ? 'bg-info' : (!$canSubmit ? 'bg-secondary' : 'bg-warning text-dark')) }}">
                                    {{ $isGraded ? 'Graded' : ($isSubmitted ? 'Submitted' : (!$canSubmit ? 'Closed' : 'Pending')) }}
                                </span>
                                <h6 class="mb-0 fw-semibold">{{ $a->title }}</h6>
                            </div>
                            <p class="text-muted small mb-1">{{ $a->course->title }}</p>
                            @if($a->instructions)
                                <p class="small text-muted mb-2">{{ Str::limit($a->instructions, 120) }}</p>
                            @endif
                            <div class="d-flex align-items-center gap-3 small text-muted flex-wrap">
                                <span><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="d-inline-block align-text-bottom me-1"><path stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Due: {{ $a->due_at?->format('M j, g:i A') ?? '—' }}</span>
                                <span><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="d-inline-block align-text-bottom me-1"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>Max: {{ $a->max_score }} pts</span>
                                @if($isGraded && $sub)
                                    <span class="fw-semibold text-dark">Score: {{ $sub->score }}/{{ $a->max_score }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="btn btn-sm {{ ($isGraded || !$canSubmit) ? 'btn-outline-secondary' : 'btn-primary' }}">
                                {{ $isGraded ? 'View' : (!$canSubmit ? 'View' : 'Submit') }}
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" class="ms-1"><path d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <svg class="mb-3 text-muted" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <p class="text-muted mb-0">No assignments {{ $filter !== 'all' ? 'in this filter.' : 'yet.' }}</p>
            </div>
        </div>
    @endforelse
</div>
@endsection

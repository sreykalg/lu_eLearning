@extends('layouts.student-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .assignment-card { transition: box-shadow 0.2s; }
    .assignment-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .filter-tab { padding: 0.4rem 1rem; font-size: 0.9rem; border-radius: 0.375rem; text-decoration: none; color: #64748b; border: 1px solid #e5e7eb; background: #fff; transition: all 0.15s; }
    .filter-tab:hover { background: #f9fafb; color: #0f172a; }
    .filter-tab.active { background: #0f172a; color: #fff; border-color: #0f172a; }
    .assignment-filter-row { display: flex; flex-wrap: wrap; column-gap: 0.65rem; row-gap: 0.6rem; margin-top: 0.85rem; }
    .assignment-list-card { border: 1px solid #e2e8f0; border-radius: 0.85rem; background: #fff; overflow: hidden; }
    .assignment-list-card .card-body { padding: 0.9rem 1rem; }
    .assignment-status-pill { font-size: 0.72rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 9999px; }
    .assignment-title { font-size: 1.02rem; font-weight: 700; color: #0f172a; margin-bottom: 0.18rem; }
    .assignment-course { color: #64748b; font-size: 0.84rem; margin-bottom: 0.45rem; }
    .assignment-preview { color: #475569; font-size: 0.88rem; margin-bottom: 0.55rem; }
    .assignment-meta-row { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
    .assignment-meta-chip { font-size: 0.78rem; color: #475569; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 9999px; padding: 0.2rem 0.5rem; display: inline-flex; align-items: center; gap: 0.25rem; }
    .assignment-action-btn { border-radius: 0.55rem; font-weight: 600; padding: 0.35rem 0.7rem; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
        </div>
        <div>
            <h1 class="h3 hero-title">Assignments</h1>
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
            <p class="hero-subtitle">{{ $pendingCount }} pending · {{ $gradedCount }} graded</p>
        </div>
    </div>
    
    <div class="assignment-filter-row">
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
            <div class="card border-0 shadow-sm assignment-card assignment-list-card">
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
                                <span class="badge assignment-status-pill {{ $isGraded ? 'bg-success' : ($isSubmitted ? 'bg-info' : (!$canSubmit ? 'bg-secondary' : 'bg-warning text-dark')) }}">
                                    {{ $isGraded ? 'Graded' : ($isSubmitted ? 'Submitted' : (!$canSubmit ? 'Closed' : 'Pending')) }}
                                </span>
                                <h6 class="assignment-title mb-0">{{ $a->title }}</h6>
                            </div>
                            <p class="assignment-course">{{ $a->course->title }}</p>
                            @if($a->instructions)
                                <p class="assignment-preview">{{ Str::limit($a->instructions, 120) }}</p>
                            @endif
                            <div class="assignment-meta-row">
                                <span class="assignment-meta-chip"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Due: {{ $a->due_at?->format('M j, g:i A') ?? '—' }}</span>
                                <span class="assignment-meta-chip"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>Max: {{ $a->max_score }} pts</span>
                                @if($isGraded && $sub)
                                    <span class="assignment-meta-chip fw-semibold text-dark">Score: {{ $sub->score }}/{{ $a->max_score }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="btn btn-sm assignment-action-btn {{ ($isGraded || !$canSubmit) ? 'btn-outline-secondary' : 'btn-primary' }}">
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

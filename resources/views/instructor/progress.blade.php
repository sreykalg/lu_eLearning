@extends('layouts.instructor-inner')

@php
    $rowCount = count($rows);
@endphp

@push('styles')
<style>
    .ins-prog-hero {
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
    .ins-prog-hero .hero-left { display: flex; align-items: center; gap: 1rem; }
    .ins-prog-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center;
    }
    .ins-prog-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .ins-prog-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; max-width: 36rem; line-height: 1.45; }
    .ins-prog-toolbar {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .ins-prog-toolbar__head {
        padding: 0.85rem 1.15rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
    }
    .ins-prog-toolbar__head h2 {
        margin: 0;
        font-size: 0.8125rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
    }
    .ins-prog-toolbar__body {
        padding: 1rem 1.15rem 1.15rem;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        gap: 1rem 1.25rem;
    }
    .ins-prog-search-wrap {
        position: relative;
        flex: 0 1 280px;
        min-width: 200px;
    }
    .ins-prog-search-wrap svg {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }
    .ins-prog-search {
        width: 100%;
        padding: 0.55rem 0.85rem 0.55rem 2.5rem;
        font-size: 0.9rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.65rem;
        background: #f8fafc;
        transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
    }
    .ins-prog-search:focus {
        outline: none;
        border-color: #0f172a;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
    }
    .ins-prog-chips-wrap {
        flex: 1;
        min-width: 0;
    }
    .ins-prog-chips-scroll {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.45rem;
        overflow-x: auto;
        padding-bottom: 0.25rem;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .ins-prog-chips-scroll::-webkit-scrollbar { height: 6px; }
    .ins-prog-chips-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    .ins-prog-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.85rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 9999px;
        text-decoration: none;
        white-space: nowrap;
        flex-shrink: 0;
        transition: background 0.15s, color 0.15s, border-color 0.15s, box-shadow 0.15s;
    }
    .ins-prog-chip--active {
        background: #0f172a;
        color: #fff;
        border: 1px solid #0f172a;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.2);
    }
    .ins-prog-chip--inactive {
        background: #fff;
        color: #334155;
        border: 1px solid #e2e8f0;
    }
    .ins-prog-chip--inactive:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .ins-prog-panel {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        min-height: 280px;
        display: flex;
        flex-direction: column;
    }
    .ins-prog-panel__head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .ins-prog-panel__head h2 { margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a; }
    .ins-prog-panel__head .meta { font-size: 0.8rem; color: #64748b; }
    .ins-prog-table-wrap { flex: 1; overflow-x: auto; }
    .ins-prog-table { margin: 0; font-size: 0.9rem; }
    .ins-prog-table thead th {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 0.85rem 1.25rem !important;
        white-space: nowrap;
    }
    .ins-prog-table tbody td {
        padding: 1rem 1.25rem !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .ins-prog-table tbody tr:nth-child(even) { background: #fafbfc; }
    .ins-prog-table tbody tr:hover { background: #f1f5f9; }
    .ins-prog-avatar {
        width: 38px; height: 38px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 700;
        color: #fff;
        background: linear-gradient(145deg, #0f172a 0%, #334155 100%);
        flex-shrink: 0;
    }
    .ins-prog-course-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 240px;
        line-height: 1.35;
        font-weight: 600;
        color: #0f172a;
    }
    .ins-prog-track-wrap { display: flex; align-items: center; gap: 0.65rem; min-width: 140px; }
    .ins-prog-track {
        flex: 1;
        min-width: 72px;
        max-width: 140px;
        height: 9px;
        border-radius: 9999px;
        background: #e2e8f0;
        overflow: hidden;
    }
    .ins-prog-track .fill {
        height: 100%;
        border-radius: 9999px;
        background: linear-gradient(90deg, #0f172a 0%, #475569 100%);
        transition: width 0.35s ease;
    }
    .ins-prog-track .fill.fill--low { background: linear-gradient(90deg, #94a3b8 0%, #cbd5e1 100%); }
    .ins-prog-pct { font-size: 0.8125rem; font-weight: 700; color: #475569; min-width: 2.5rem; }
    .ins-prog-status {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 9999px;
        letter-spacing: 0.02em;
    }
    .ins-prog-status--on-track { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
    .ins-prog-status--excellent { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .ins-prog-status--at-risk { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
    .ins-prog-empty {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
        text-align: center;
        color: #64748b;
    }
    .ins-prog-empty svg { opacity: 0.35; margin-bottom: 1rem; color: #94a3b8; }
    .ins-prog-empty p { margin: 0; max-width: 22rem; line-height: 1.5; }
</style>
@endpush

@section('content')
<div class="ins-prog-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div>
            <h1 class="hero-title">Student progress</h1>
            <p class="hero-subtitle">
                @if($rowCount === 0)
                    Enrollments will appear here when students join your courses.
                @else
                    {{ $rowCount }} {{ Str::plural('enrollment', $rowCount) }} · lessons, assignments, and quiz performance per learner.
                @endif
            </p>
        </div>
    </div>
</div>

<div class="ins-prog-toolbar">
    <div class="ins-prog-toolbar__head">
        <h2>Filter &amp; search</h2>
    </div>
    <div class="ins-prog-toolbar__body">
        <div class="ins-prog-search-wrap">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <form action="{{ route('instructor.progress') }}" method="GET" class="m-0">
                @if(request('course_id'))
                    <input type="hidden" name="course_id" value="{{ request('course_id') }}">
                @endif
                <label class="visually-hidden" for="ins-prog-search-input">Search students</label>
                <input id="ins-prog-search-input" type="search" name="search" value="{{ request('search') }}" class="ins-prog-search" placeholder="Search by student name…" autocomplete="off">
            </form>
        </div>
        <div class="ins-prog-chips-wrap">
            <div class="ins-prog-chips-scroll">
                <a href="{{ route('instructor.progress', request()->except('course_id', 'page')) }}"
                   class="ins-prog-chip {{ !request('course_id') ? 'ins-prog-chip--active' : 'ins-prog-chip--inactive' }}"
                   title="All courses">All courses</a>
                @foreach($courses as $c)
                    <a href="{{ route('instructor.progress', array_merge(request()->except('course_id', 'page'), ['course_id' => $c->id])) }}"
                       class="ins-prog-chip {{ (string)request('course_id') === (string)$c->id ? 'ins-prog-chip--active' : 'ins-prog-chip--inactive' }}"
                       title="{{ $c->title }}">{{ Str::limit($c->title, 42) }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="ins-prog-panel">
    <div class="ins-prog-panel__head">
        <h2>Learners</h2>
        <span class="meta">{{ $rowCount }} {{ Str::plural('row', $rowCount) }}</span>
    </div>
    @if($rowCount === 0)
        <div class="ins-prog-empty">
            <svg width="56" height="56" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            @if(request('search') || request('course_id'))
                <p class="fw-semibold text-secondary mb-2">No matching learners</p>
                <p class="small">Try clearing the search or choosing &ldquo;All courses&rdquo; to see more results.</p>
            @else
                <p class="fw-semibold text-secondary mb-2">No enrollments yet</p>
                <p class="small">When students enroll in your courses, their progress will appear here. You can filter by course or search by name.</p>
            @endif
        </div>
    @else
        <div class="ins-prog-table-wrap">
            <table class="table ins-prog-table mb-0">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Lesson progress</th>
                        <th>Assignments</th>
                        <th>Quiz avg</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $r)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @php
                                        $name = $r->user->name ?? 'U';
                                        $parts = array_filter(explode(' ', $name));
                                        $initials = count($parts) >= 2 ? Str::upper(mb_substr($parts[0],0,1).mb_substr($parts[count($parts)-1],0,1)) : Str::upper(mb_substr($name,0,2));
                                    @endphp
                                    <div class="ins-prog-avatar">{{ $initials }}</div>
                                    <span class="fw-semibold text-dark">{{ $r->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="ins-prog-course-text" title="{{ $r->course->title }}">{{ $r->course->title }}</span>
                            </td>
                            <td>
                                <div class="ins-prog-track-wrap">
                                    <div class="ins-prog-track">
                                        <div class="fill {{ $r->lesson_pct < 40 ? 'fill--low' : '' }}" style="width: {{ min(100, $r->lesson_pct) }}%;"></div>
                                    </div>
                                    <span class="ins-prog-pct">{{ $r->lesson_pct }}%</span>
                                </div>
                            </td>
                            <td class="fw-semibold text-secondary">{{ $r->assignments_done }}/{{ $r->assignments_total }}</td>
                            <td class="text-secondary">{{ $r->quiz_avg !== null ? $r->quiz_avg.'%' : '—' }}</td>
                            <td>
                                @php
                                    $statusKey = str_replace('_', '-', $r->status);
                                    $statusLabel = ucfirst(str_replace('_', ' ', $r->status));
                                @endphp
                                <span class="ins-prog-status ins-prog-status--{{ $statusKey }}">{{ $statusLabel }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

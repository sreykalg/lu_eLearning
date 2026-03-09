@extends('layouts.instructor-inner')

@push('styles')
<style>
    .progress-chip { padding: 0.35rem 0.75rem; font-size: 0.875rem; border-radius: 9999px; text-decoration: none; transition: all 0.15s; }
    .progress-chip.active { background: #0f172a; color: #fff; border: none; }
    .progress-chip.inactive { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
    .progress-chip.inactive:hover { background: #f9fafb; border-color: #d1d5db; }
    .progress-search-input { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; }
    .status-chip { padding: 0.25rem 0.6rem; font-size: 0.75rem; font-weight: 500; border-radius: 9999px; }
    .status-chip.status-on-track { background: #EDF2F7; color: #2C5282; border: 1px solid #cbd5e0; }
    .status-chip.status-excellent { background: #E6FFFA; color: #2F855A; border: 1px solid #9ae6b4; }
    .status-chip.status-at-risk { background: #FFF5F5; color: #C53030; border: 1px solid #feb2b2; }
    .progress-bar-custom { height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden; }
    .progress-bar-custom .fill { height: 100%; background: #0f172a; border-radius: 4px; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Student Progress</h1>
    <p class="text-muted mb-0">{{ count($rows) }} students across your courses</p>
</div>

{{-- Search and course filter --}}
<div class="d-flex flex-wrap align-items-center gap-3 mb-4">
    <div class="position-relative flex-grow-1" style="max-width: 280px;">
        <svg class="position-absolute top-50 start-3 translate-middle-y text-muted" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <form action="{{ route('instructor.progress') }}" method="GET" class="d-flex">
            @if(request('course_id'))
                <input type="hidden" name="course_id" value="{{ request('course_id') }}">
            @endif
            <input type="search" name="search" value="{{ request('search') }}" class="form-control py-2 ps-4 progress-search-input" placeholder="Search students..." style="font-size: 0.9375rem;">
        </form>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('instructor.progress', request()->except('course_id', 'page')) }}"
           class="progress-chip {{ !request('course_id') ? 'active' : 'inactive' }}">All</a>
        @foreach($courses as $c)
            <a href="{{ route('instructor.progress', array_merge(request()->except('course_id', 'page'), ['course_id' => $c->id])) }}"
               class="progress-chip {{ (string)request('course_id') === (string)$c->id ? 'active' : 'inactive' }}">
                {{ Str::limit($c->title, 30) }}
            </a>
        @endforeach
    </div>
</div>

<div class="rounded-3 bg-white shadow-sm border overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background: #f1f5f9;">
                <tr>
                    <th class="border-0 py-3 fw-semibold">Student</th>
                    <th class="border-0 py-3 fw-semibold">Course</th>
                    <th class="border-0 py-3 fw-semibold">Lesson Progress</th>
                    <th class="border-0 py-3 fw-semibold">Assignments</th>
                    <th class="border-0 py-3 fw-semibold">Quiz Avg</th>
                    <th class="border-0 py-3 fw-semibold">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $name = $r->user->name ?? 'U';
                                    $parts = array_filter(explode(' ', $name));
                                    $initials = count($parts) >= 2 ? Str::upper(mb_substr($parts[0],0,1).mb_substr($parts[count($parts)-1],0,1)) : Str::upper(mb_substr($name,0,2));
                                @endphp
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold flex-shrink-0" style="width:36px;height:36px;background:#0f172a;font-size:0.75rem;">{{ $initials }}</div>
                                <span>{{ $r->user->name }}</span>
                            </div>
                        </td>
                        <td class="py-3">{{ Str::limit($r->course->title, 25) }}</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress-bar-custom flex-grow-1" style="min-width:80px;max-width:120px;">
                                    <div class="fill" style="width:{{ $r->lesson_pct }}%;"></div>
                                </div>
                                <span class="text-nowrap">{{ $r->lesson_pct }}%</span>
                            </div>
                        </td>
                        <td class="py-3">{{ $r->assignments_done }}/{{ $r->assignments_total }}</td>
                        <td class="py-3">{{ $r->quiz_avg !== null ? $r->quiz_avg.'%' : '—' }}</td>
                        <td class="py-3">
                            <span class="status-chip status-{{ str_replace('_', '-', $r->status) }}">{{ ucfirst(str_replace('_', ' ', $r->status)) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted text-center py-5">No enrolled students yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

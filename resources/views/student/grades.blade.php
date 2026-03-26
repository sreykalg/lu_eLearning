@extends('layouts.student-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
    .grades-summary-card {
        background: #fff;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        padding: 1.25rem 1.5rem;
        height: 100%;
        transition: box-shadow 0.2s;
    }
    .grades-summary-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .grades-summary-card .icon-wrap {
        width: 40px;
        height: 40px;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
        background: #f1f5f9;
        color: #0f172a;
    }
    .grades-summary-card .icon-wrap.gold { background: #fef3c7; color: #b45309; }
    .grades-summary-card .value { font-size: 1.5rem; font-weight: 700; color: #0f172a; }
    .grades-summary-card .label { font-size: 0.8125rem; color: #64748b; margin-top: 0.25rem; }
    .grades-course-section {
        background: #fff;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .grades-course-header {
        padding: 1rem 1.25rem;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .grades-course-title { font-weight: 600; font-size: 1rem; color: #0f172a; }
    .grades-course-current { font-size: 0.875rem; color: #64748b; }
    .grades-letter-pill {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8125rem;
        font-weight: 600;
    }
    .grades-letter-a { background: #dcfce7; color: #166534; }
    .grades-letter-b { background: #dbeafe; color: #1e40af; }
    .grades-letter-c { background: #fef3c7; color: #b45309; }
    .grades-letter-d { background: #ffe4e6; color: #be123c; }
    .grades-letter-f { background: #fee2e2; color: #b91c1c; }
    .grades-table { margin: 0; }
    .grades-table th {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        background: #fafafa;
    }
    .grades-table td {
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .grades-table tr:last-child td { border-bottom: none; }
    .grades-table .type-badge {
        font-size: 0.75rem;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-weight: 500;
        background: #f1f5f9;
        color: #475569;
    }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/></svg>
        </div>
        <div>
            <h1 class="h3 hero-title">Grades</h1>
            <p class="hero-subtitle">Your performance across courses</p>
        </div>
    </div>
</div>

{{-- Summary cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="grades-summary-card">
            <div class="icon-wrap">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div class="value">{{ $overallAvg }}%</div>
            <div class="label">Overall Average</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="grades-summary-card">
            <div class="icon-wrap gold">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            </div>
            <div class="value">{{ $highestLetter ?? '—' }}{{ $highestPct > 0 ? ' (' . $highestPct . '%)' : '' }}</div>
            <div class="label">Highest Grade</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="grades-summary-card">
            <div class="icon-wrap">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div class="value">{{ $coursesCount }}</div>
            <div class="label">Courses</div>
        </div>
    </div>
</div>

{{-- Course breakdowns --}}
@forelse($coursesData as $cd)
    <div class="grades-course-section">
        <div class="grades-course-header">
            <div>
                <div class="grades-course-title">{{ $cd['course']->title }}</div>
                @if($cd['current_pct'] !== null)
                    <span class="grades-course-current">Current: {{ $cd['current_pct'] }}%</span>
                @endif
            </div>
            @if($cd['letter'])
                @php
                    $letterClass = 'grades-letter-f';
                    $first = substr($cd['letter'], 0, 1);
                    if ($first === 'A') $letterClass = 'grades-letter-a';
                    elseif ($first === 'B') $letterClass = 'grades-letter-b';
                    elseif ($first === 'C') $letterClass = 'grades-letter-c';
                    elseif ($first === 'D') $letterClass = 'grades-letter-d';
                @endphp
                <span class="grades-letter-pill {{ $letterClass }}">{{ $cd['letter'] }}</span>
            @endif
        </div>
        @if($cd['items']->isNotEmpty())
            <table class="table grades-table mb-0">
                <thead>
                    <tr>
                        <th>Assessment</th>
                        <th>Type</th>
                        <th>Earned</th>
                        <th>Max</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cd['items'] as $item)
                        <tr>
                            <td class="fw-medium">{{ $item['title'] }}</td>
                            <td><span class="type-badge">{{ $item['type'] }}</span></td>
                            <td>{{ (int) $item['earned'] }}</td>
                            <td>{{ (int) $item['max'] }}</td>
                            <td class="fw-semibold">{{ $item['pct'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-4 text-center text-muted small">No graded assessments yet.</div>
        @endif
    </div>
@empty
    <div class="rounded-3 bg-white shadow-sm border p-5 text-center">
        <p class="text-muted mb-0">No courses or grades yet. Enroll in a course to see your grades here.</p>
    </div>
@endforelse
@endsection

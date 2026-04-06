@extends('layouts.hod-inner')

@push('styles')
<style>
    .hod-dash-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 1.35rem 1.5rem;
        color: #fff;
        margin-bottom: 1.25rem;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2);
    }
    .hod-dash-hero .hero-left { display: flex; align-items: center; gap: 1rem; }
    .hod-dash-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center;
    }
    .hod-dash-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .hod-dash-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.82); font-size: 0.9rem; }
    .hod-stat-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.9rem;
        background: #fff;
        padding: 1.15rem 1.2rem;
        height: 100%;
        transition: box-shadow 0.2s, transform 0.15s;
    }
    .hod-stat-card:hover {
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        transform: translateY(-2px);
    }
    .hod-stat-card .stat-icon {
        width: 44px; height: 44px; border-radius: 0.75rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .hod-stat-card .stat-icon--courses { background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%); color: #1e40af; }
    .hod-stat-card .stat-icon--students { background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%); color: #166534; }
    .hod-stat-card .stat-icon--instructors { background: linear-gradient(135deg, #e0e7ff 0%, #eef2ff 100%); color: #3730a3; }
    .hod-stat-card .stat-icon--pending { background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%); color: #b45309; }
    .hod-stat-card .stat-value { font-size: 1.65rem; font-weight: 800; letter-spacing: -0.03em; color: #0f172a; line-height: 1.1; }
    .hod-stat-card .stat-label { font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: #64748b; margin-top: 0.15rem; }
    .hod-panel {
        border: 1px solid #e2e8f0;
        border-radius: 0.9rem;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .hod-panel-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
    }
    .hod-panel-head h2 { margin: 0; font-size: 1rem; font-weight: 700; color: #0f172a; }
    .hod-panel-head p { margin: 0.2rem 0 0; font-size: 0.8rem; color: #64748b; }
    .hod-panel-body { padding: 0.25rem 0; }
    .hod-instructor-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }
    .hod-instructor-row:last-child { border-bottom: none; }
    .hod-instructor-row:hover { background: #f8fafc; }
    .hod-instructor-row .person { display: flex; align-items: center; gap: 0.75rem; min-width: 0; }
    .hod-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
        color: #fff; font-size: 0.8rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .hod-instructor-row .name { font-weight: 600; color: #0f172a; font-size: 0.9375rem; }
    .hod-instructor-row .email { font-size: 0.8rem; color: #64748b; }
    .hod-badge {
        font-size: 0.72rem; font-weight: 700;
        padding: 0.35rem 0.65rem; border-radius: 9999px;
        white-space: nowrap;
    }
    .hod-badge--ok { background: #dcfce7; color: #166534; }
    .hod-badge--idle { background: #f1f5f9; color: #64748b; }
    .hod-progress-item { padding: 0.85rem 1.25rem; border-bottom: 1px solid #f1f5f9; }
    .hod-progress-item:last-child { border-bottom: none; }
    .hod-progress-item .row-label { display: flex; justify-content: space-between; align-items: baseline; gap: 0.5rem; margin-bottom: 0.45rem; }
    .hod-progress-item .course-name { font-size: 0.875rem; font-weight: 600; color: #334155; }
    .hod-progress-item .pct { font-size: 0.8rem; font-weight: 700; color: #0f172a; }
    .hod-progress-track {
        height: 9px;
        border-radius: 9999px;
        background: #f1f5f9;
        overflow: hidden;
    }
    .hod-progress-track .fill {
        height: 100%;
        border-radius: 9999px;
        background: linear-gradient(90deg, #0f172a 0%, #334155 100%);
        transition: width 0.4s ease;
    }
    .hod-progress-track .fill.fill--low { background: linear-gradient(90deg, #94a3b8 0%, #cbd5e1 100%); }
</style>
@endpush

@section('content')
<div class="hod-dash-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z"/></svg>
        </div>
        <div>
            <h1 class="hero-title">Head of Department</h1>
            <p class="hero-subtitle">Welcome, {{ auth()->user()->name }} · department overview and platform health</p>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="hod-stat-card">
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon--courses">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['courses'] }}</div>
                    <div class="stat-label">Total courses</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="hod-stat-card">
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon--students">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['students'] }}</div>
                    <div class="stat-label">Students</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="hod-stat-card">
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon--instructors">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['instructors'] }}</div>
                    <div class="stat-label">Instructors</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="hod-stat-card">
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon--pending">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <div class="stat-value">{{ $stats['pending'] }}</div>
                    <div class="stat-label">Pending approvals</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="hod-panel h-100">
            <div class="hod-panel-head">
                <h2>Instructors &amp; courses</h2>
                <p>Teaching staff and how many courses each owns</p>
            </div>
            <div class="hod-panel-body">
                @forelse($instructors as $i)
                    @php
                        $parts = array_filter(explode(' ', $i->name ?? ''));
                        $initials = count($parts) >= 2
                            ? strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1))
                            : strtoupper(mb_substr($i->name ?? '?', 0, 2));
                    @endphp
                    <div class="hod-instructor-row">
                        <div class="person">
                            <span class="hod-avatar">{{ $initials }}</span>
                            <div class="min-w-0">
                                <div class="name text-truncate">{{ $i->name }}</div>
                                <div class="email text-truncate">{{ $i->email }}</div>
                            </div>
                        </div>
                        <span class="hod-badge {{ $i->courses_count > 0 ? 'hod-badge--ok' : 'hod-badge--idle' }}">
                            {{ $i->courses_count }} {{ Str::plural('course', $i->courses_count) }} · {{ $i->courses_count > 0 ? 'Active' : 'None' }}
                        </span>
                    </div>
                @empty
                    <div class="p-4 text-muted text-center small">No instructors yet.</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="hod-panel h-100">
            <div class="hod-panel-head">
                <h2>Course completion</h2>
                <p>Approximate share of lesson progress across enrollments (published courses)</p>
            </div>
            <div class="hod-panel-body">
                @forelse($completion as $c)
                    <div class="hod-progress-item">
                        <div class="row-label">
                            <span class="course-name">{{ Str::limit($c->title, 42) }}</span>
                            <span class="pct">{{ $c->completion_pct }}%</span>
                        </div>
                        <div class="hod-progress-track">
                            <div class="fill{{ $c->completion_pct < 20 ? ' fill--low' : '' }}" style="width: {{ min(100, max(0, $c->completion_pct)) }}%;"></div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-muted text-center small">No data yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

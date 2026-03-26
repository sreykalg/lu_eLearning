@extends('layouts.hod-inner')

@push('styles')
<style>
    .page-hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #334155 100%); border-radius: 1rem; padding: 1.25rem 1.4rem; color: #fff; margin-bottom: 1rem; }
    .page-hero .hero-left { display: flex; align-items: center; gap: 0.9rem; }
    .page-hero .hero-icon { width: 44px; height: 44px; border-radius: 0.75rem; background: rgba(255,255,255,0.12); display: flex; align-items: center; justify-content: center; }
    .page-hero .hero-title { margin: 0; font-weight: 700; }
    .page-hero .hero-subtitle { margin: 0.2rem 0 0; color: rgba(255,255,255,0.8); font-size: 0.9rem; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 13h8V3H3v10zm10 8h8V3h-8v18zM3 21h8v-6H3v6z"/></svg>
        </div>
        <div>
            <h1 class="h3 hero-title">Head of Department Dashboard</h1>
            <p class="hero-subtitle">Welcome, {{ auth()->user()->name }} · Department Overview</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2" style="background: rgba(37,99,235,0.1);">
                    <svg width="28" height="28" fill="none" stroke="#0f172a" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold">{{ $stats['courses'] }}</h3>
                    <p class="text-muted small mb-0">Total Courses</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2" style="background: rgba(37,99,235,0.1);">
                    <svg width="28" height="28" fill="none" stroke="#0f172a" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold">{{ $stats['students'] }}</h3>
                    <p class="text-muted small mb-0">Students</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2" style="background: rgba(37,99,235,0.1);">
                    <svg width="28" height="28" fill="none" stroke="#0f172a" viewBox="0 0 24 24"><path stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold">{{ $stats['instructors'] }}</h3>
                    <p class="text-muted small mb-0">Instructors</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="rounded-3 p-4 bg-white shadow-sm border">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2" style="background: rgba(37,99,235,0.1);">
                    <svg width="28" height="28" fill="none" stroke="#0f172a" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold">{{ $stats['pending'] }}</h3>
                    <p class="text-muted small mb-0">Pending Approvals</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="rounded-3 bg-white shadow-sm border p-4">
            <h5 class="fw-semibold mb-3">Instructors & Active Courses</h5>
            @forelse($instructors as $i)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-light">
                    <div>
                        <div class="fw-medium">{{ $i->name }}</div>
                        <small class="text-muted">{{ $i->email }}</small>
                    </div>
                    <span class="badge {{ $i->courses_count > 0 ? 'bg-success' : 'bg-secondary' }}">{{ $i->courses_count }} courses · {{ $i->courses_count > 0 ? 'Active' : 'Inactive' }}</span>
                </div>
            @empty
                <p class="text-muted mb-0">No instructors yet.</p>
            @endforelse
        </div>
    </div>
    <div class="col-lg-6">
        <div class="rounded-3 bg-white shadow-sm border p-4">
            <h5 class="fw-semibold mb-3">Platform Completion Rates</h5>
            @forelse($completion as $c)
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span>{{ $c->title }}</span>
                        <span>{{ $c->completion_pct }}%</span>
                    </div>
                    <div class="progress" style="height:8px;">
                        <div class="progress-bar bg-primary" style="width:{{ $c->completion_pct }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">No data yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

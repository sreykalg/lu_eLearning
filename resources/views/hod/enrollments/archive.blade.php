@extends('layouts.hod-inner')

@push('styles')
<style>
    .hod-arch-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 1.35rem 1.5rem;
        color: #fff;
        margin-bottom: 1.25rem;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2);
    }
    .hod-arch-hero .hero-left { display: flex; align-items: flex-start; gap: 1rem; }
    .hod-arch-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .hod-arch-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .hod-arch-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; max-width: 40rem; }
    .hod-arch-panel {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        padding: 1.35rem 1.5rem;
        margin-bottom: 1.25rem;
    }
    .hod-arch-panel h2 { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem; }
    .hod-arch-estimate {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        padding: 0.85rem 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
    }
    .hod-arch-course-list { max-height: 220px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 0.65rem 0.85rem; }
    .hod-arch-course-list label { display: flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0; font-size: 0.9rem; cursor: pointer; margin: 0; }
    .hod-arch-muted { font-size: 0.875rem; color: #64748b; }
</style>
@endpush

@section('content')
<div class="hod-arch-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <div>
            <h1 class="hero-title">Archive enrollments</h1>
            <p class="hero-subtitle">Hide past student–course links so learners start fresh for a new intake. Archived enrollments no longer appear on dashboards or class lists; students can enroll again. Grades stay visible on the Grades page as past enrollments.</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-3 border-0 shadow-sm mb-3">{{ session('success') }}</div>
@endif

<div class="hod-arch-panel">
    <h2>Estimate</h2>
    <p class="hod-arch-muted mb-3">We archive <strong>active</strong> enrollments whose <strong>enrolled date</strong> is on or before the end of the selected day. Leave all courses unchecked to include every published course.</p>
    <form method="get" action="{{ route('hod.enrollments.archive') }}" class="mb-0">
        <div class="row g-3 align-items-end mb-3">
            <div class="col-auto">
                <label class="form-label small fw-semibold text-secondary mb-1">Cutoff date</label>
                <input type="date" name="cutoff" class="form-control" value="{{ $cutoffInput }}" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-secondary rounded-3">Update estimate</button>
            </div>
        </div>
        <p class="hod-arch-estimate mb-0">Estimated matches: {{ number_format($previewCount) }} enrollment(s)</p>
        @if($courses->isNotEmpty())
            <p class="small fw-semibold text-secondary mb-2">Limit to specific courses (optional)</p>
            <div class="hod-arch-course-list mb-0">
                @foreach($courses as $c)
                    <label>
                        <input type="checkbox" name="course_ids[]" value="{{ $c->id }}" @checked(in_array($c->id, $courseIds, true))>
                        <span>{{ $c->title }}</span>
                    </label>
                @endforeach
            </div>
        @endif
    </form>
</div>

<div class="hod-arch-panel border-warning" style="border-width: 2px;">
    <h2>Apply archive</h2>
    <p class="hod-arch-muted mb-3">Confirm the same cutoff and courses below, then submit. This sets <code>archived_at</code> on matching rows. It does not delete courses or student accounts.</p>
    <form method="post" action="{{ route('hod.enrollments.archive.store') }}" id="hodArchiveForm">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-semibold text-secondary">Cutoff date</label>
            <input type="date" name="cutoff_date" class="form-control" style="max-width: 12rem;" value="{{ $cutoffInput }}" required>
        </div>
        @if($courses->isNotEmpty())
            <p class="small fw-semibold text-secondary mb-2">Courses (optional — none checked = all published)</p>
            <div class="hod-arch-course-list mb-3">
                @foreach($courses as $c)
                    <label>
                        <input type="checkbox" name="course_ids[]" value="{{ $c->id }}" @checked(in_array($c->id, $courseIds, true))>
                        <span>{{ $c->title }}</span>
                    </label>
                @endforeach
            </div>
        @endif
        <button type="submit" class="btn btn-dark rounded-3">Archive matching enrollments</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('hodArchiveForm');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        var n = {{ (int) $previewCount }};
        if (n === 0) {
            e.preventDefault();
            alert('No enrollments match. Use "Update estimate" after choosing a cutoff, or pick a different date.');
            return;
        }
        if (!confirm('Archive ' + n + ' enrollment(s) for this cutoff (see estimate above)? Students will need to enroll again for those courses.')) {
            e.preventDefault();
        }
    });
})();
</script>
@endpush

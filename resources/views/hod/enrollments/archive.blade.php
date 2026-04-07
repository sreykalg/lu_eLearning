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
    .hod-arch-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; max-width: 42rem; line-height: 1.45; }
    .hod-arch-narrow {
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
        width: 100%;
    }
    .hod-arch-card {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .hod-arch-card__section { padding: 1.35rem 1.5rem; }
    .hod-arch-card__section + .hod-arch-card__section { border-top: 1px solid #f1f5f9; }
    .hod-arch-card__section--muted { background: #fafbfc; }
    .hod-arch-steps {
        display: flex; flex-wrap: wrap; gap: 0.5rem 1.25rem;
        font-size: 0.75rem; font-weight: 700; letter-spacing: 0.04em;
        text-transform: uppercase; color: #94a3b8; margin-bottom: 1rem;
    }
    .hod-arch-steps span { color: #0f172a; }
    .hod-arch-steps svg { vertical-align: -2px; opacity: 0.45; }
    .hod-arch-label { font-size: 0.8125rem; font-weight: 600; color: #475569; margin-bottom: 0.35rem; }
    .hod-arch-date-row { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 0.75rem; }
    .hod-arch-date-row .form-control { max-width: 11rem; border-radius: 0.65rem; }
    .hod-arch-stat {
        display: flex; align-items: baseline; gap: 0.5rem; flex-wrap: wrap;
        padding: 1rem 1.15rem;
        border-radius: 0.85rem;
        border: 1px solid #e2e8f0;
        background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        margin-top: 1rem;
    }
    .hod-arch-stat__n { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.03em; color: #0f172a; line-height: 1; }
    .hod-arch-stat--zero .hod-arch-stat__n { color: #b45309; }
    .hod-arch-stat__hint { font-size: 0.8125rem; color: #64748b; margin: 0; flex: 1; min-width: 12rem; }
    .hod-arch-course-list {
        max-height: 200px; overflow-y: auto;
        border: 1px solid #e2e8f0; border-radius: 0.75rem;
        padding: 0.5rem 0.75rem;
        background: #fff;
    }
    .hod-arch-course-list label {
        display: flex; align-items: flex-start; gap: 0.6rem;
        padding: 0.45rem 0.15rem; font-size: 0.9rem; cursor: pointer; margin: 0;
        border-radius: 0.35rem;
    }
    .hod-arch-course-list label:hover { background: #f8fafc; }
    .hod-arch-course-list input { margin-top: 0.2rem; flex-shrink: 0; }
    .hod-arch-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem; }
    .hod-arch-danger {
        border-left: 4px solid #fbbf24;
        background: #fffbeb;
    }
    .hod-arch-danger .hod-arch-muted { color: #92400e; }
    .hod-arch-muted { font-size: 0.875rem; color: #64748b; line-height: 1.5; }
    .hod-arch-muted code { font-size: 0.8em; color: #475569; background: #f1f5f9; padding: 0.1rem 0.35rem; border-radius: 0.25rem; }
</style>
@endpush

@section('content')
<div class="hod-arch-narrow">
<div class="hod-arch-hero">
    <div class="hero-left">
        <div class="hero-icon" aria-hidden="true">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <div>
            <h1 class="hero-title">Archive enrollments</h1>
            <p class="hero-subtitle">End-of-year cleanup: hide old student–course links so dashboards stay current. Nothing is deleted—<strong class="text-white">grades remain</strong> on the Grades page as past enrollments.</p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success rounded-3 border-0 shadow-sm mb-3" role="status">{{ session('success') }}</div>
@endif

<form method="post" action="{{ route('hod.enrollments.archive.store') }}" id="hodArchiveForm">
    @csrf
    <div class="hod-arch-card">
        <div class="hod-arch-card__section hod-arch-card__section--muted">
            <div class="hod-arch-steps" aria-hidden="true">
                <span>① Filters</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>② Estimate</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>③ Archive</span>
            </div>
            <p class="hod-arch-muted mb-3 mb-lg-4">Pick a <strong>cutoff</strong>. We match <strong>active</strong> enrollments whose first enrollment time is <strong>on or before</strong> the end of that day. Leave every course unchecked to include <strong>all</strong> published courses.</p>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="hod-arch-label" for="hod-cutoff">Cutoff date</div>
                    <div class="hod-arch-date-row">
                        <input type="date" name="cutoff_date" id="hod-cutoff" class="form-control" value="{{ $cutoffInput }}" required aria-describedby="hod-cutoff-help">
                        <button type="button" id="hod-btn-estimate" class="btn btn-outline-secondary rounded-3 px-3">Refresh estimate</button>
                    </div>
                    <p id="hod-cutoff-help" class="small text-muted mt-2 mb-0">Tip: if you see 0 matches, try a <em>later</em> date—enrollments may be newer than the cutoff.</p>
                </div>
                <div class="col-lg-6">
                    @if($courses->isNotEmpty())
                        <div class="hod-arch-label">Limit to courses <span class="fw-normal text-muted">(optional)</span></div>
                        <div class="hod-arch-course-list" role="group" aria-label="Optional course filter">
                            @foreach($courses as $c)
                                <label>
                                    <input type="checkbox" class="hod-course-cb" name="course_ids[]" value="{{ $c->id }}" @checked(in_array($c->id, $courseIds, true))>
                                    <span>{{ $c->title }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="small text-muted mb-0">No published courses yet.</p>
                    @endif
                </div>
            </div>

            <div class="hod-arch-stat {{ $previewCount === 0 ? 'hod-arch-stat--zero' : '' }}" role="status" aria-live="polite">
                <span class="hod-arch-stat__n">{{ number_format($previewCount) }}</span>
                <p class="hod-arch-stat__hint mb-0">
                    @if($previewCount === 0)
                        No active enrollments match right now. Adjust the date or courses, then click <strong>Refresh estimate</strong>.
                    @else
                        Active enrollment(s) will be archived when you confirm below.
                    @endif
                </p>
            </div>
        </div>

        <div class="hod-arch-card__section hod-arch-danger">
            <h2 class="h6 fw-bold text-dark mb-2">Apply</h2>
            <p class="hod-arch-muted small mb-3">Sets <code>archived_at</code> on matching rows. Courses and user accounts are unchanged. Students can enroll again later.</p>
            <div class="hod-arch-actions">
                <button type="submit" class="btn btn-dark rounded-3 px-4" id="hod-btn-archive">
                    @if($previewCount > 0)
                        Archive {{ number_format($previewCount) }} enrollment(s)
                    @else
                        Archive matching enrollments
                    @endif
                </button>
            </div>
        </div>
    </div>
</form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var estimateUrl = @json(route('hod.enrollments.archive'));
    var cutoff = document.getElementById('hod-cutoff');
    var btnEst = document.getElementById('hod-btn-estimate');
    var form = document.getElementById('hodArchiveForm');

    function selectedCourseIds() {
        return Array.from(document.querySelectorAll('.hod-course-cb:checked')).map(function (cb) { return cb.value; });
    }

    if (btnEst) {
        btnEst.addEventListener('click', function () {
            if (!cutoff.value) return;
            var params = new URLSearchParams();
            params.set('cutoff_date', cutoff.value);
            selectedCourseIds().forEach(function (id) { params.append('course_ids[]', id); });
            window.location = estimateUrl + '?' + params.toString();
        });
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            if (!cutoff.value) {
                e.preventDefault();
                return;
            }
        });
    }
})();
</script>
@endpush

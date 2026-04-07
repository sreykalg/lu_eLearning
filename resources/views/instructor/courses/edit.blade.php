@extends('layouts.instructor-inner')

@include('instructor.course-builder.sidebar-styles')

@push('styles')
<style>
    .cb-edit-breadcrumb {
        font-size: 0.8125rem;
        margin-bottom: 1rem;
    }
    .cb-edit-back {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: #64748b;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .cb-edit-back:hover { color: #0f172a; }
    .cb-edit-breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }
    .cb-edit-breadcrumb a:hover { color: #0f172a; }
    .cb-form-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .cb-form-card__head {
        padding: 1.35rem 1.5rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fff 0%, #fafbfc 100%);
    }
    .cb-form-card__head h1 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #0f172a;
    }
    .cb-form-card__head p {
        margin: 0.35rem 0 0;
        font-size: 0.9rem;
        color: #64748b;
        max-width: 42rem;
    }
    .cb-form-card__body {
        padding: 1.5rem 1.5rem 1.75rem;
    }
    .cb-form-card .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: #334155;
    }
    .cb-form-card .form-control,
    .cb-form-card .form-select {
        border-radius: 0.65rem;
        border-color: #cbd5e1;
    }
    .cb-form-card .form-control:focus,
    .cb-form-card .form-select:focus {
        border-color: #0f172a;
        box-shadow: 0 0 0 0.2rem rgba(15, 23, 42, 0.08);
    }
    .cb-thumb-preview {
        border-radius: 0.65rem;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        max-width: 280px;
        background: #f8fafc;
    }
    .cb-thumb-preview img {
        width: 100%;
        height: auto;
        display: block;
        vertical-align: middle;
    }
    .cb-grading-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem;
        padding: 1.15rem 1.25rem 1.25rem;
    }
    .cb-grading-card .cb-grading-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    .cb-grading-card h2 {
        margin: 0;
        font-size: 0.9375rem;
        font-weight: 700;
        color: #0f172a;
    }
    .cb-grading-card .cb-grading-hint {
        margin: 0.25rem 0 0;
        font-size: 0.8rem;
        color: #64748b;
    }
    .cb-grading-total-bar {
        height: 8px;
        border-radius: 9999px;
        background: #e2e8f0;
        overflow: hidden;
        display: flex;
        margin-top: 0.75rem;
    }
    .cb-grading-total-bar span {
        display: block;
        height: 100%;
        transition: flex 0.2s ease;
    }
    .cb-seg-quiz { background: #3b82f6; }
    .cb-seg-assign { background: #8b5cf6; }
    .cb-seg-mid { background: #f59e0b; }
    .cb-seg-final { background: #ef4444; }
    .cb-seg-att { background: #10b981; }
    .cb-status-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.15rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
    }
    .cb-status-actions {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .cb-status-toggle {
        border-radius: 9999px;
        padding: 0.38rem 0.85rem;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .cb-form-footer {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.65rem;
        padding-top: 0.25rem;
    }
    .cb-form-footer-secondary {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.65rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }
    /* Match course-structure column height to this card (flex row stretch) */
    .cb-wrap .cb-main > .cb-form-card {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
    }
    .cb-wrap .cb-main > .cb-form-card .cb-form-card__body {
        flex: 1 1 auto;
    }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success border-0 rounded-3 shadow-sm alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 rounded-3 shadow-sm alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<a href="{{ route('instructor.courses.index') }}" class="cb-edit-back">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" d="M15 18l-6-6 6-6"/></svg>
        Back
</a>
<div class="cb-edit-breadcrumb">
    <a href="{{ route('instructor.courses.index') }}">Course Builder</a>
    <span class="text-muted"> / </span>
    <span class="text-dark fw-semibold">Edit course</span>
</div>

<div class="cb-wrap">
    <div class="cb-sidebar">
        @include('instructor.course-builder.sidebar', ['course' => $course, 'courses' => auth()->user()->courses()->orderBy('title')->get()])
    </div>
    <div class="cb-main">
        <div class="cb-form-card">
            <div class="cb-form-card__head">
                <h1>Course details</h1>
                <p>Update the course shell, thumbnail, and grading weights. Use the left panel to add modules, lessons, quizzes, and assignments.</p>
            </div>
            <div class="cb-form-card__body">
                <form action="{{ route('instructor.courses.update', $course) }}" method="post" enctype="multipart/form-data" id="courseUpdateForm">
                    @csrf
                    @method('PUT')
                    @error('grading')
                        <div class="alert alert-danger rounded-3 border-0 mb-3">{{ $message }}</div>
                    @enderror
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label class="form-label" for="course_title">Title</label>
                                <input id="course_title" type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $course->title) }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label" for="course_description">Description</label>
                                <textarea id="course_description" name="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description', $course->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label" for="course_level">Level</label>
                                <select id="course_level" name="level" class="form-select @error('level') is-invalid @enderror" required>
                                    @foreach(['beginner','intermediate','advanced'] as $l)
                                        <option value="{{ $l }}" {{ old('level', $course->level) === $l ? 'selected' : '' }}>{{ ucfirst($l) }}</option>
                                    @endforeach
                                </select>
                                @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label" for="course_thumbnail">Thumbnail (optional)</label>
                                <input id="course_thumbnail" type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                                @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @if($course->thumbnail)
                                    <div class="cb-thumb-preview mt-3">
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Current course thumbnail">
                                    </div>
                                    <p class="small text-muted mt-2 mb-0">Upload a new image to replace the current thumbnail.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="cb-grading-card mt-4">
                        <div class="cb-grading-head">
                            <div>
                                <h2>Grading setup</h2>
                                <p class="cb-grading-hint mb-0">Weights must add up to exactly 100. Attendance is fixed at 10.</p>
                            </div>
                        </div>
                        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-5 g-3">
                            <div class="col">
                                <label class="form-label small text-muted mb-1">Quiz</label>
                                <select name="quiz_weight" class="form-select form-select-sm">
                                    <option value="10" {{ old('quiz_weight', $course->quiz_weight ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ old('quiz_weight', $course->quiz_weight ?? 10) == 20 ? 'selected' : '' }}>20</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label small text-muted mb-1">Assignment</label>
                                <select name="assignment_weight" class="form-select form-select-sm">
                                    <option value="10" {{ old('assignment_weight', $course->assignment_weight ?? 10) == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ old('assignment_weight', $course->assignment_weight ?? 10) == 20 ? 'selected' : '' }}>20</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label small text-muted mb-1">Midterm</label>
                                <select name="midterm_weight" class="form-select form-select-sm">
                                    <option value="30" {{ old('midterm_weight', $course->midterm_weight ?? 30) == 30 ? 'selected' : '' }}>30</option>
                                    <option value="40" {{ old('midterm_weight', $course->midterm_weight ?? 30) == 40 ? 'selected' : '' }}>40</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label small text-muted mb-1">Final</label>
                                <select name="final_weight" class="form-select form-select-sm">
                                    <option value="30" {{ old('final_weight', $course->final_weight ?? 40) == 30 ? 'selected' : '' }}>30</option>
                                    <option value="40" {{ old('final_weight', $course->final_weight ?? 40) == 40 ? 'selected' : '' }}>40</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label small text-muted mb-1">Attendance</label>
                                <select class="form-select form-select-sm" disabled>
                                    <option value="10" selected>10</option>
                                </select>
                                <input type="hidden" name="attendance_weight" value="10">
                            </div>
                        </div>
                        <div id="gradingTotalMessage" class="small mt-3 mb-0"></div>
                        <div class="cb-grading-total-bar" id="gradingBar" aria-hidden="true">
                            <span class="cb-seg-quiz" id="barQuiz" style="flex: 1"></span>
                            <span class="cb-seg-assign" id="barAssign" style="flex: 1"></span>
                            <span class="cb-seg-mid" id="barMid" style="flex: 1"></span>
                            <span class="cb-seg-final" id="barFinal" style="flex: 1"></span>
                            <span class="cb-seg-att" id="barAtt" style="flex: 1"></span>
                        </div>
                    </div>

                    @php
                        $statusLabel = match($course->approval_status ?? 'draft') {
                            'pending' => 'Pending Review',
                            'approved' => 'Approved',
                            'needs_revision' => 'Needs Revision',
                            default => 'Draft',
                        };
                    @endphp
                    <div class="cb-status-row mt-4">
                        <span class="fw-semibold text-secondary small text-uppercase" style="letter-spacing: 0.06em;">Status</span>
                        <span class="badge rounded-pill px-3 py-2 {{ $course->approval_status === 'approved' ? 'bg-success' : ($course->approval_status === 'pending' ? 'bg-warning text-dark' : ($course->approval_status === 'needs_revision' ? 'bg-danger' : 'bg-secondary')) }}">{{ $statusLabel }}</span>
                        @if($course->approval_status === 'approved')
                            <span class="badge rounded-pill px-3 py-2 {{ $course->is_published ? 'bg-success-subtle text-success-emphasis border border-success-subtle' : 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle' }}">
                                {{ $course->is_published ? 'Active for students' : 'Inactive for students' }}
                            </span>
                            <div class="cb-status-actions">
                                <form action="{{ route('instructor.courses.toggle-publish', $course) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($course->is_published)
                                        <button type="submit" class="btn btn-outline-danger cb-status-toggle">Set inactive</button>
                                    @else
                                        <button type="submit" class="btn btn-outline-success cb-status-toggle">Set active</button>
                                    @endif
                                </form>
                            </div>
                        @endif
                    </div>
                    @if($course->revision_notes)
                        <div class="mt-3 p-3 rounded-3 bg-light border">
                            <small class="text-muted d-block mb-1 fw-semibold">Feedback from HoD</small>
                            <p class="mb-0 small">{{ $course->revision_notes }}</p>
                        </div>
                    @endif

                    <div class="cb-form-footer mt-4">
                        <button type="submit" class="btn btn-dark rounded-3 px-4 fw-semibold">Save course</button>
                    </div>
                </form>

                <div class="cb-form-footer-secondary d-flex flex-wrap align-items-center gap-2">
                    @if(in_array($course->approval_status ?? 'draft', ['draft', 'needs_revision']))
                        <form action="{{ route('instructor.courses.submit-approval', $course) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success rounded-3 px-4 fw-semibold">Submit for approval</button>
                        </form>
                    @endif
                    <form action="{{ route('instructor.courses.destroy', $course) }}" method="POST" class="d-inline ms-auto" onsubmit="return confirm('Delete this course? All lessons, quizzes, and assignments will be removed. This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger rounded-3 fw-semibold">Delete course</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    (function () {
        const form = document.getElementById('courseUpdateForm');
        if (!form) return;
        const fields = ['quiz_weight', 'assignment_weight', 'midterm_weight', 'final_weight', 'attendance_weight']
            .map((name) => form.querySelector(`[name="${name}"]`));
        const msg = document.getElementById('gradingTotalMessage');
        const barQuiz = document.getElementById('barQuiz');
        const barAssign = document.getElementById('barAssign');
        const barMid = document.getElementById('barMid');
        const barFinal = document.getElementById('barFinal');
        const barAtt = document.getElementById('barAtt');
        function updateTotal() {
            const total = fields.reduce((sum, el) => sum + Number(el?.value || 0), 0);
            const q = Number(fields[0]?.value || 0);
            const a = Number(fields[1]?.value || 0);
            const m = Number(fields[2]?.value || 0);
            const f = Number(fields[3]?.value || 0);
            const att = Number(fields[4]?.value || 0);
            if (barQuiz) barQuiz.style.flex = String(Math.max(q, 0.001));
            if (barAssign) barAssign.style.flex = String(Math.max(a, 0.001));
            if (barMid) barMid.style.flex = String(Math.max(m, 0.001));
            if (barFinal) barFinal.style.flex = String(Math.max(f, 0.001));
            if (barAtt) barAtt.style.flex = String(Math.max(att, 0.001));
            if (total > 100) {
                msg.className = 'small mt-3 mb-0 text-danger fw-semibold';
                msg.textContent = 'This combination is over 100. Adjust the values.';
            } else if (total < 100) {
                msg.className = 'small mt-3 mb-0 text-warning fw-semibold';
                msg.textContent = 'Total is ' + total + '. It must equal 100.';
            } else {
                msg.className = 'small mt-3 mb-0 text-success fw-semibold';
                msg.textContent = 'Perfect. Total is 100.';
            }
        }
        fields.forEach((el) => el?.addEventListener('change', updateTotal));
        form.addEventListener('submit', function (e) {
            const total = fields.reduce((sum, el) => sum + Number(el?.value || 0), 0);
            if (total !== 100) {
                e.preventDefault();
                msg.className = 'small mt-3 mb-0 text-danger fw-semibold';
                msg.textContent = total > 100
                    ? 'Cannot save: total is over 100.'
                    : 'Cannot save: total is below 100.';
            }
        });
        updateTotal();
    })();
</script>
@endpush
@endsection

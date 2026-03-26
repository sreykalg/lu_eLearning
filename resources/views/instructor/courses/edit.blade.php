@extends('layouts.instructor-inner')

@push('styles')
<style>
    .cb-wrap { display: flex; min-height: 560px; overflow-x: hidden; background: #fff; border-radius: 0.5rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    .cb-sidebar { width: 380px; min-width: 380px; max-width: 380px; flex-shrink: 0; padding: 1rem 1.5rem 1rem 1rem; max-height: 85vh; overflow-y: auto; overflow-x: hidden; border-right: 1px solid #e5e7eb; border-radius: 0.5rem 0 0 0.5rem; }
    .cb-main { flex: 1; min-width: 0; padding: 1.5rem 2.5rem 1.5rem 2rem; overflow: auto; }
    .cb-dropdown { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #374151; }
    .cb-add-module { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #374151; text-align: left; }
    .cb-add-module:hover { background: #f9fafb; }
    .cb-module-header { padding: 0.35rem 0; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem; }
    .cb-module-title { color: #374151; }
    .cb-drag { color: #9ca3af; font-size: 0.75rem; cursor: default; pointer-events: none; }
    .cb-item { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; margin: 0.15rem 0; border-radius: 0.375rem; text-decoration: none; color: #374151; font-size: 0.875rem; border-left: 3px solid transparent; }
    .cb-item:hover { background: #f3f4f6; }
    .cb-item.active { background: #0f172a; color: #fff; border-left-color: #0f172a; }
    .cb-label { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="cb-wrap">
    <div class="cb-sidebar">
        @include('instructor.course-builder.sidebar', ['course' => $course, 'courses' => auth()->user()->courses()->orderBy('title')->get()])
    </div>
    <div class="cb-main">
        <h4 class="mb-4 fw-bold">Course details</h4>
        <form action="{{ route('instructor.courses.update', $course) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @error('grading')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $course->title) }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $course->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Level</label>
                <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                    @foreach(['beginner','intermediate','advanced'] as $l)
                        <option value="{{ $l }}" {{ old('level', $course->level) === $l ? 'selected' : '' }}>{{ ucfirst($l) }}</option>
                    @endforeach
                </select>
                @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Thumbnail (optional)</label>
                <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="border rounded p-3 mb-3">
                <h6 class="fw-bold mb-3">Grading Setup (must total 100)</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Quiz</label>
                        <select name="quiz_weight" class="form-select">
                            <option value="10" {{ old('quiz_weight', $course->quiz_weight ?? 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ old('quiz_weight', $course->quiz_weight ?? 10) == 20 ? 'selected' : '' }}>20</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Assignment</label>
                        <select name="assignment_weight" class="form-select">
                            <option value="10" {{ old('assignment_weight', $course->assignment_weight ?? 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ old('assignment_weight', $course->assignment_weight ?? 10) == 20 ? 'selected' : '' }}>20</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Midterm</label>
                        <select name="midterm_weight" class="form-select">
                            <option value="30" {{ old('midterm_weight', $course->midterm_weight ?? 30) == 30 ? 'selected' : '' }}>30</option>
                            <option value="40" {{ old('midterm_weight', $course->midterm_weight ?? 30) == 40 ? 'selected' : '' }}>40</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Final</label>
                        <select name="final_weight" class="form-select">
                            <option value="30" {{ old('final_weight', $course->final_weight ?? 40) == 30 ? 'selected' : '' }}>30</option>
                            <option value="40" {{ old('final_weight', $course->final_weight ?? 40) == 40 ? 'selected' : '' }}>40</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Attendance</label>
                        <select class="form-select" disabled>
                            <option value="10" selected>10</option>
                        </select>
                        <input type="hidden" name="attendance_weight" value="10">
                    </div>
                </div>
                <div id="gradingTotalMessage" class="small mt-3"></div>
            </div>
            @php
                $statusLabel = match($course->approval_status ?? 'draft') {
                    'pending' => 'Pending Review',
                    'approved' => 'Approved',
                    'needs_revision' => 'Needs Revision',
                    default => 'Draft',
                };
            @endphp
            <div class="mb-3">
                <span class="badge {{ $course->approval_status === 'approved' ? 'bg-success' : ($course->approval_status === 'pending' ? 'bg-warning text-dark' : ($course->approval_status === 'needs_revision' ? 'bg-danger' : 'bg-secondary')) }}">{{ $statusLabel }}</span>
                @if($course->revision_notes)
                    <div class="mt-2 p-3 rounded bg-light border">
                        <small class="text-muted d-block mb-1">Feedback from HoD:</small>
                        <p class="mb-0 small">{{ $course->revision_notes }}</p>
                    </div>
                @endif
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <button type="submit" class="btn btn-primary">Save Course</button>
                @if(in_array($course->approval_status ?? 'draft', ['draft', 'needs_revision']))
                    <form action="{{ route('instructor.courses.submit-approval', $course) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Submit for Approval</button>
                    </form>
                @endif
                <form action="{{ route('instructor.courses.destroy', $course) }}" method="POST" class="d-inline ms-auto" onsubmit="return confirm('Delete this course? All lessons, quizzes, and assignments will be removed. This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">Delete Course</button>
                </form>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    (function () {
        const form = document.querySelector('form[action="{{ route('instructor.courses.update', $course) }}"]');
        if (!form) return;
        const fields = ['quiz_weight', 'assignment_weight', 'midterm_weight', 'final_weight', 'attendance_weight']
            .map((name) => form.querySelector(`[name="${name}"]`));
        const msg = document.getElementById('gradingTotalMessage');
        function updateTotal() {
            const total = fields.reduce((sum, el) => sum + Number(el?.value || 0), 0);
            if (total > 100) {
                msg.className = 'small mt-3 text-danger';
                msg.textContent = 'Cannot choose this combination, it is over 100 in total.';
            } else if (total < 100) {
                msg.className = 'small mt-3 text-warning';
                msg.textContent = 'Total is below 100. Please adjust to exactly 100.';
            } else {
                msg.className = 'small mt-3 text-success';
                msg.textContent = 'Perfect. Total is 100.';
            }
        }
        fields.forEach((el) => el?.addEventListener('change', updateTotal));
        form.addEventListener('submit', function (e) {
            const total = fields.reduce((sum, el) => sum + Number(el?.value || 0), 0);
            if (total !== 100) {
                e.preventDefault();
                msg.className = 'small mt-3 text-danger';
                msg.textContent = total > 100
                    ? 'Cannot choose this combination, it is over 100 in total.'
                    : 'Total is below 100. Please adjust to exactly 100.';
            }
        });
        updateTotal();
    })();
</script>
@endpush
@endsection

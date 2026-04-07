@extends('layouts.instructor-inner')

@include('instructor.course-builder.sidebar-styles')

@push('styles')
<style>
    .asg-edit-shell {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .asg-edit-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.9rem;
        padding: 1.15rem 1.25rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fff 0%, #fafbfc 100%);
    }
    .asg-edit-head h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #0f172a;
    }
    .asg-edit-subtitle {
        margin: 0.3rem 0 0;
        font-size: 0.86rem;
        color: #64748b;
    }
    .asg-edit-actions { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .asg-edit-actions .btn { border-radius: 0.6rem; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em; padding: 0.4rem 0.7rem; }
    .asg-edit-body { padding: 1.25rem; }
    .asg-block {
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem;
        background: #fff;
        padding: 1rem 1rem 0.4rem;
        margin-bottom: 1rem;
    }
    .asg-section-title {
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.85rem;
    }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="cb-wrap">
    <div class="cb-sidebar">
        @php $course->load(['modules', 'lessons', 'quizzes', 'assignments']); @endphp
        @include('instructor.course-builder.sidebar', ['course' => $course, 'assignment' => $assignment])
    </div>
    <div class="cb-main">
        <div class="asg-edit-shell">
            <div class="asg-edit-head">
                <div>
                    <h4>Edit Assignment</h4>
                    <p class="asg-edit-subtitle">Update instructions, due date, grading settings, and required rules.</p>
                </div>
                <div class="asg-edit-actions">
                    <a href="{{ route('instructor.assignments.submissions', [$course, $assignment]) }}" class="btn btn-dark">
                        View submissions ({{ $assignment->submissions()->count() }})
                    </a>
                    <form action="{{ route('instructor.assignments.destroy', [$course, $assignment]) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this assignment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                    </form>
                </div>
            </div>

            <div class="asg-edit-body">
                <form action="{{ route('instructor.assignments.update', [$course, $assignment]) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="asg-block">
                        <div class="asg-section-title">Assignment Details</div>
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $assignment->title) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Instructions</label>
                            <textarea name="instructions" class="form-control" rows="4">{{ old('instructions', $assignment->instructions) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link to lesson (optional)</label>
                            <select name="lesson_id" class="form-select">
                                <option value="">— None —</option>
                                @foreach($course->lessons as $l)
                                    <option value="{{ $l->id }}" {{ old('lesson_id', $assignment->lesson_id) == $l->id ? 'selected' : '' }}>{{ $l->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Grading type</label>
                            <select name="grading_type" class="form-select">
                                <option value="auto" {{ old('grading_type', $assignment->grading_type ?? 'manual') === 'auto' ? 'selected' : '' }}>Auto-grading</option>
                                <option value="manual" {{ old('grading_type', $assignment->grading_type ?? 'manual') === 'manual' ? 'selected' : '' }}>Manual grading by instructor</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Max score</label>
                                <input type="number" name="max_score" class="form-control" min="0" value="{{ old('max_score', $assignment->max_score) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Due date (optional)</label>
                                <input type="datetime-local" name="due_at" class="form-control" value="{{ old('due_at', $assignment->due_at?->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="allow_late_submission" value="1" class="form-check-input" id="allow_late_submission" {{ old('allow_late_submission', $assignment->allow_late_submission ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_late_submission">Allow late submission</label>
                            <div class="form-text">If unchecked, students cannot submit after the due date.</div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_required" value="1" class="form-check-input" id="is_required" {{ old('is_required', $assignment->is_required) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_required">Required</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Assignment</button>
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

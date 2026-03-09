@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Edit Assignment: {{ $assignment->title }}</h1>
</div>

<div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('instructor.assignments.update', [$course, $assignment]) }}" method="post">
                    @csrf
                    @method('PUT')
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
                    <button type="submit" class="btn btn-lu-primary">Update Assignment</button>
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                    <form action="{{ route('instructor.assignments.destroy', [$course, $assignment]) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this assignment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                    </form>
                </form>
            </div>
        </div>
</div>
@endsection

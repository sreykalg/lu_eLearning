@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Add Assignment to {{ $course->title }}</h1>
</div>

<div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('instructor.assignments.store', $course) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instructions</label>
                        <textarea name="instructions" class="form-control @error('instructions') is-invalid @enderror" rows="4">{{ old('instructions') }}</textarea>
                        @error('instructions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link to lesson (optional)</label>
                        <select name="lesson_id" class="form-select">
                            <option value="">— None —</option>
                            @foreach($course->lessons as $l)
                                <option value="{{ $l->id }}" {{ old('lesson_id') == $l->id ? 'selected' : '' }}>{{ $l->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Max score</label>
                            <input type="number" name="max_score" class="form-control" min="0" value="{{ old('max_score', 100) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Due date (optional)</label>
                            <input type="datetime-local" name="due_at" class="form-control" value="{{ old('due_at') }}">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_required" value="1" class="form-check-input" id="is_required" {{ old('is_required') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_required">Required to complete course</label>
                    </div>
                    <button type="submit" class="btn btn-lu-primary">Add Assignment</button>
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
</div>
@endsection

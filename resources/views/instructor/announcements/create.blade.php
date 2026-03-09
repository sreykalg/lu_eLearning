@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Post Announcement</h1>
    <p class="text-muted mb-0">Notify students enrolled in a specific course</p>
</div>

@if($courses->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <p class="mb-3">You need at least one course before posting announcements.</p>
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">Create course</a>
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="{{ route('instructor.announcements.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="course_id" class="form-label fw-medium">Course</label>
                    <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                        <option value="">Select a course</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Only students enrolled in this course will see the announcement.</small>
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label fw-medium">Title</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" maxlength="255" required placeholder="e.g. Important: Quiz moved to Friday">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="body" class="form-label fw-medium">Message</label>
                    <textarea name="body" id="body" class="form-control @error('body') is-invalid @enderror" rows="5" maxlength="5000" required placeholder="Write your announcement...">{{ old('body') }}</textarea>
                    @error('body')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-danger">Post Announcement</button>
                    <a href="{{ route('instructor.announcements.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection

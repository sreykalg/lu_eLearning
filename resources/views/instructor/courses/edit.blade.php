@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h1 class="h3 fw-bold mb-0">Edit Course: {{ $course->title }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('instructor.lessons.create', $course) }}" class="btn btn-primary btn-sm">Add Lesson</a>
        <a href="{{ route('instructor.quizzes.create', $course) }}" class="btn btn-outline-primary btn-sm">Add Quiz</a>
        <a href="{{ route('instructor.assignments.create', $course) }}" class="btn btn-outline-secondary btn-sm">Add Assignment</a>
    </div>
</div>

<div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                {{-- Course details form --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Course details</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('instructor.courses.update', $course) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
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
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_published" value="1" class="form-check-input" id="is_published" {{ old('is_published', $course->is_published) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">Published</label>
                            </div>
                            <button type="submit" class="btn btn-lu-primary">Update Course</button>
                        </form>
                    </div>
                </div>

                {{-- Lessons --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Lessons</h5>
                        <a href="{{ route('instructor.lessons.create', $course) }}" class="btn btn-sm btn-lu-primary">+ Add</a>
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse($course->lessons as $lesson)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $lesson->title }}</strong>
                                    @if($lesson->video_url)
                                        <span class="badge bg-info ms-2">Video</span>
                                    @endif
                                    @if($lesson->videoQuizzes->count() > 0)
                                        <span class="badge bg-secondary ms-1">{{ $lesson->videoQuizzes->count() }} quizzes</span>
                                    @endif
                                </div>
                                <a href="{{ route('instructor.lessons.edit', [$course, $lesson]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No lessons yet.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Quizzes (practice, midterm, final) --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Quizzes</h5>
                        <a href="{{ route('instructor.quizzes.create', $course) }}" class="btn btn-sm btn-lu-primary">+ Add</a>
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse($course->quizzes as $quiz)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $quiz->title }}</strong>
                                    <span class="badge bg-{{ $quiz->type === 'final' ? 'danger' : ($quiz->type === 'midterm' ? 'warning' : 'primary') }} ms-2">{{ ucfirst($quiz->type) }}</span>
                                    <small class="text-muted">({{ $quiz->questions->count() }} questions)</small>
                                </div>
                                <a href="{{ route('instructor.quizzes.edit', [$course, $quiz]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No quizzes yet.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Assignments --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Assignments</h5>
                        <a href="{{ route('instructor.assignments.create', $course) }}" class="btn btn-sm btn-lu-primary">+ Add</a>
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse($course->assignments as $a)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $a->title }}</strong>
                                    <small class="text-muted">Max: {{ $a->max_score }} pts</small>
                                    @if($a->due_at)
                                        <small class="text-muted"> · Due {{ $a->due_at->format('M j, Y') }}</small>
                                    @endif
                                </div>
                                <a href="{{ route('instructor.assignments.edit', [$course, $a]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No assignments yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <a href="{{ route('instructor.dashboard') }}" class="btn btn-outline-secondary w-100 mb-2">← Back to Dashboard</a>
                        <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary w-100">All Courses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

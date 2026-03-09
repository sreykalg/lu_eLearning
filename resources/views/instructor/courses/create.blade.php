@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Create Course</h1>
</div>

<div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('instructor.courses.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Level</label>
                        <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                            <option value="beginner" {{ old('level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        @error('level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thumbnail (optional)</label>
                        <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                        @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-lu-primary">Create Course</button>
                    <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
</div>
@endsection

@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Add Lesson to {{ $course->title }}</h1>
</div>

<div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('instructor.lessons.store', $course) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content (optional)</label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="4">{{ old('content') }}</textarea>
                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Video (upload or URL)</label>
                        <input type="file" name="video" class="form-control mb-2 @error('video') is-invalid @enderror" accept="video/mp4,video/webm,video/quicktime">
                        <small class="text-muted">Or paste external video URL below (e.g. YouTube embed URL)</small>
                        <input type="text" name="video_url" class="form-control mt-2 @error('video_url') is-invalid @enderror" placeholder="https://..." value="{{ old('video_url') }}">
                        @error('video')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @error('video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Video duration (seconds, optional)</label>
                        <input type="number" name="video_duration" class="form-control" min="0" value="{{ old('video_duration') }}" placeholder="e.g. 600 for 10 min">
                        @error('video_duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_free" value="1" class="form-check-input" id="is_free" {{ old('is_free') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_free">Free preview</label>
                    </div>
                    <button type="submit" class="btn btn-lu-primary">Add Lesson</button>
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
</div>
@endsection

<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0 fw-bold" style="color: var(--lu-deep-purple);">Edit Lesson: {{ $lesson->title }}</h2>
    </x-slot>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Lesson details</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('instructor.lessons.update', [$course, $lesson]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $lesson->title) }}" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Content (optional)</label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="4">{{ old('content', $lesson->content) }}</textarea>
                                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Video</label>
                                @if($lesson->video_url)
                                    <div class="mb-2 small text-muted">Current: {{ Str::limit($lesson->video_url, 60) }}</div>
                                @endif
                                <input type="file" name="video" class="form-control mb-2" accept="video/mp4,video/webm,video/quicktime">
                                <input type="text" name="video_url" class="form-control" placeholder="Or paste external URL" value="{{ old('video_url', $lesson->video_url) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Video duration (seconds)</label>
                                <input type="number" name="video_duration" class="form-control" min="0" value="{{ old('video_duration', $lesson->video_duration) }}">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_free" value="1" class="form-check-input" id="is_free" {{ old('is_free', $lesson->is_free) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_free">Free preview</label>
                            </div>
                            <button type="submit" class="btn btn-lu-primary">Update Lesson</button>
                        </form>
                    </div>
                </div>

                {{-- In-video quizzes (at X minutes) --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">In-video quizzes</h5>
                        <button type="button" class="btn btn-sm btn-lu-primary" data-bs-toggle="modal" data-bs-target="#addVideoQuizModal">+ Add quiz at minute</button>
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse($lesson->videoQuizzes as $vq)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ gmdate('i:s', $vq->timestamp_seconds) }}</strong> — {{ Str::limit($vq->question, 50) }}
                                </div>
                                <form action="{{ route('instructor.video-quizzes.destroy', $vq) }}" method="post" class="d-inline" onsubmit="return confirm('Remove this in-video quiz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No in-video quizzes. Add quizzes that appear at specific timestamps in the video.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary w-100">← Back to Course</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add in-video quiz modal --}}
    <div class="modal fade" id="addVideoQuizModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('instructor.video-quizzes.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Add in-video quiz</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted">The quiz will appear when the video reaches this timestamp.</p>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Minutes</label>
                                <input type="number" name="timestamp_minutes" class="form-control" min="0" value="0" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Seconds</label>
                                <input type="number" name="timestamp_seconds" class="form-control" min="0" max="59" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Question</label>
                            <input type="text" name="question" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Options (select the correct one)</label>
                            @foreach(range(0,3) as $i)
                                <div class="input-group mb-2">
                                    <span class="input-group-text">
                                        <input type="radio" name="correct_index" value="{{ $i }}" {{ $i === 0 ? 'checked' : '' }}>
                                    </span>
                                    <input type="text" name="options[{{ $i }}][text]" class="form-control" placeholder="Option {{ $i+1 }}" required>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-lu-primary">Add quiz</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

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
    .cb-video-dropzone { border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 2rem; text-align: center; background: #f9fafb; cursor: pointer; transition: all 0.2s; }
    .cb-video-dropzone:hover, .cb-video-dropzone.dragover { border-color: #0f172a; background: #f1f5f9; }
    .cb-attach-dropzone { border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 1rem; text-align: center; background: #f9fafb; font-size: 0.875rem; color: #6b7280; cursor: pointer; transition: all 0.2s; }
    .cb-attach-dropzone:hover, .cb-attach-dropzone.dragover { border-color: #0f172a; background: #f1f5f9; }
    .cb-duration-chip { background: #e5e7eb; color: #374151; padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.875rem; }
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
        @php $course->load(['modules', 'lessons', 'quizzes']); @endphp
        @include('instructor.course-builder.sidebar', ['course' => $course, 'lesson' => $lesson])
    </div>
    <div class="cb-main">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold">Edit Lesson</h4>
            <div class="d-flex gap-2">
                <button type="submit" form="lessonForm" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Draft
                </button>
                <form action="{{ route('instructor.lessons.destroy', [$course, $lesson]) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this lesson?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 text-danger">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </form>
                <button type="submit" form="lessonForm" class="btn btn-sm d-flex align-items-center gap-1" style="background:#0f172a;color:#fff;border:none;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Publish
                </button>
            </div>
        </div>

        <form id="lessonForm" action="{{ route('instructor.lessons.update', [$course, $lesson]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="form-label fw-medium">Lesson Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $lesson->title) }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Video Upload</label>
                <div class="cb-video-dropzone" onclick="document.getElementById('videoInput').click()" id="videoDropzone">
                    <input type="file" name="video" id="videoInput" class="d-none" accept="video/mp4,video/quicktime,video/x-msvideo">
                    <svg width="40" height="40" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" class="mb-2 d-block mx-auto"><path stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <p class="mb-0 text-muted">Drag & drop a video file or click to browse</p>
                    <p class="small text-muted mb-0 mt-1">MP4, MOV up to 500MB</p>
                    @if($lesson->video_url)
                        <p class="small text-success mt-2">Current: {{ Str::limit($lesson->video_url, 50) }}</p>
                    @endif
                </div>
                <input type="text" name="video_url" class="form-control form-control-sm mt-2" placeholder="Or paste video URL" value="{{ old('video_url', $lesson->video_url) }}">
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Description</label>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="4" placeholder="This lesson covers the fundamentals...">{{ old('content', $lesson->content) }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Attachments</label>
                <div class="cb-attach-dropzone" id="attachDropzone" onclick="document.getElementById('attachInput').click()">
                    <input type="file" name="attachments[]" id="attachInput" class="d-none" multiple accept=".pdf,.doc,.docx,.ppt,.pptx">
                    <p class="mb-0 text-muted">Drag & drop files (PDF, DOCX, PPTX) or click to browse</p>
                </div>
                @if($lesson->attachments->isNotEmpty())
                    <p class="small text-muted mt-2 mb-1">Existing: @foreach($lesson->attachments as $a) <a href="{{ $a->path }}" target="_blank">{{ $a->original_name }}</a>@if(!$loop->last), @endif @endforeach</p>
                @endif
                <div id="attachList" class="small text-muted mt-1"></div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Lesson Duration</label>
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <p class="text-muted small mb-0">Auto-detected from video, or set manually.</p>
                    <div class="d-flex align-items-center gap-2">
                        <input type="number" name="video_duration" class="form-control form-control-sm" style="max-width:80px" min="0" value="{{ old('video_duration', $lesson->video_duration) }}" placeholder="sec">
                        <span class="cb-duration-chip">{{ $lesson->video_duration ? floor($lesson->video_duration/60) . ' min' : '—' }}</span>
                    </div>
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_free" value="1" class="form-check-input" id="is_free" {{ old('is_free', $lesson->is_free) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_free">Free preview</label>
            </div>

            <button type="submit" class="btn btn-primary">Save Lesson</button>
        </form>

        <hr class="my-4">
        <h6 class="fw-medium mb-2">In-video quizzes</h6>
        <ul class="list-group list-group-flush mb-3">
            @forelse($lesson->videoQuizzes as $vq)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <span><strong>{{ gmdate('i:s', $vq->timestamp_seconds) }}</strong> — {{ Str::limit($vq->question, 40) }}</span>
                    <form action="{{ route('instructor.video-quizzes.destroy', $vq) }}" method="post" class="d-inline" onsubmit="return confirm('Remove?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                    </form>
                </li>
            @empty
                <li class="list-group-item text-muted small px-0">No in-video quizzes.</li>
            @endforelse
        </ul>
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVideoQuizModal">+ Add quiz at minute</button>
    </div>
</div>

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
                        <label class="form-label">Options (select correct)</label>
                        @foreach(range(0,3) as $i)
                            <div class="input-group mb-2">
                                <span class="input-group-text"><input type="radio" name="correct_index" value="{{ $i }}" {{ $i === 0 ? 'checked' : '' }}></span>
                                <input type="text" name="options[{{ $i }}][text]" class="form-control" placeholder="Option {{ $i+1 }}" required>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('videoDropzone')?.addEventListener('dragover', function(e) { e.preventDefault(); this.classList.add('dragover'); });
    document.getElementById('videoDropzone')?.addEventListener('dragleave', function() { this.classList.remove('dragover'); });
    document.getElementById('videoDropzone')?.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        if (e.dataTransfer.files.length) document.getElementById('videoInput').files = e.dataTransfer.files;
    });
    var adz = document.getElementById('attachDropzone'), ain = document.getElementById('attachInput'), alist = document.getElementById('attachList');
    if (adz) {
        adz.addEventListener('dragover', function(e) { e.preventDefault(); e.stopPropagation(); this.classList.add('dragover'); });
        adz.addEventListener('dragleave', function(e) { e.preventDefault(); this.classList.remove('dragover'); });
        adz.addEventListener('drop', function(e) {
            e.preventDefault(); e.stopPropagation(); this.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                var dt = new DataTransfer();
                for (var i = 0; i < e.dataTransfer.files.length; i++) dt.items.add(e.dataTransfer.files[i]);
                ain.files = dt.files;
                updateAttachList();
            }
        });
    }
    ain?.addEventListener('change', function() { updateAttachList(); });
    function updateAttachList() {
        if (!ain || !alist) return;
        var names = []; for (var i = 0; i < ain.files.length; i++) names.push(ain.files[i].name);
        alist.textContent = names.length ? names.join(', ') : '';
    }
</script>
@endpush
@endsection

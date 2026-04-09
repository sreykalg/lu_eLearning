@extends('layouts.instructor-inner')

@include('instructor.course-builder.sidebar-styles')

@push('styles')
<style>
    .ls-edit-shell {
        background: transparent;
        border: 0;
        border-radius: 0;
        box-shadow: none;
        overflow: visible;
    }
    .ls-edit-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.9rem;
        padding: 1rem 1.1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.9rem;
        background: #fff;
    }
    .ls-edit-head h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #0f172a;
    }
    .ls-edit-subtitle {
        margin: 0.3rem 0 0;
        font-size: 0.86rem;
        color: #64748b;
    }
    .ls-edit-actions { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .ls-edit-actions .btn { border-radius: 0.6rem; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em; padding: 0.4rem 0.7rem; }
    .ls-edit-body {
        margin-top: 0.9rem;
        padding: 0;
    }
    .ls-edit-back {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        margin-bottom: 0.7rem;
        font-size: 0.84rem;
        font-weight: 700;
        color: #475569;
        text-decoration: none;
    }
    .ls-edit-back:hover { color: #0f172a; }
    .ls-block {
        border: 0;
        border-radius: 0;
        background: transparent;
        padding: 0;
        margin-bottom: 0;
    }
    .ls-section-title {
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 1rem;
    }
    .ls-form-flat {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.9rem;
        padding: 1rem 1.1rem;
    }
    .ls-field {
        padding-bottom: 1.05rem;
        margin-bottom: 1.05rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .ls-field:last-of-type {
        padding-bottom: 0;
        margin-bottom: 0.9rem;
        border-bottom: 0;
    }
    .ls-submit-row {
        display: flex;
        justify-content: flex-end;
    }
    .ls-submit-row .btn {
        border-radius: 0.65rem;
        font-weight: 700;
        padding-inline: 1rem;
    }
    .ls-quiz-section {
        margin-top: 0.9rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }
    .ls-quiz-add-btn {
        background: transparent;
        border-color: #0f172a;
        color: #0f172a;
        border-radius: 0.65rem;
        font-weight: 700;
    }
    .ls-quiz-add-btn:hover,
    .ls-quiz-add-btn:focus {
        background: rgba(15, 23, 42, 0.06);
        border-color: #0f172a;
        box-shadow: 0 0 0 0.2rem rgba(15, 23, 42, 0.12);
        color: #0f172a;
    }
    .cb-video-dropzone { border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 2rem; text-align: center; background: #f9fafb; cursor: pointer; transition: all 0.2s; }
    .cb-video-dropzone:hover, .cb-video-dropzone.dragover { border-color: #0f172a; background: #f1f5f9; }
    .cb-video-placeholder { display: block; }
    .cb-video-preview-wrap {
        display: none;
        margin-bottom: 0.65rem;
    }
    .cb-video-preview {
        width: min(100%, 560px);
        max-height: 260px;
        border-radius: 0.55rem;
        background: #0f172a;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.12);
    }
    .cb-attach-dropzone { border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 1rem; text-align: center; background: #f9fafb; font-size: 0.875rem; color: #6b7280; cursor: pointer; transition: all 0.2s; }
    .cb-attach-dropzone:hover, .cb-attach-dropzone.dragover { border-color: #0f172a; background: #f1f5f9; }
    .cb-drop-current {
        margin-top: 0.7rem;
        font-size: 0.82rem;
        color: #0f766e;
        font-weight: 600;
    }
    .cb-drop-current-list {
        margin-top: 0.65rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        justify-content: center;
    }
    .cb-drop-current-chip {
        display: inline-flex;
        align-items: center;
        max-width: 100%;
        padding: 0.25rem 0.55rem;
        border-radius: 9999px;
        background: #eef2ff;
        border: 1px solid #c7d2fe;
        color: #3730a3;
        font-size: 0.76rem;
        font-weight: 600;
        text-decoration: none;
    }
    .cb-drop-current-chip:hover { color: #312e81; background: #e0e7ff; }
    .ls-duration-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.85rem;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        padding: 0.8rem 0.9rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.85rem;
        flex-wrap: wrap;
    }
    .ls-duration-meta {
        min-width: 200px;
    }
    .ls-duration-title {
        margin: 0;
        font-size: 0.84rem;
        font-weight: 700;
        color: #0f172a;
    }
    .ls-duration-help {
        margin: 0.2rem 0 0;
        font-size: 0.78rem;
        color: #64748b;
    }
    .ls-duration-input-wrap {
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .ls-duration-input-wrap .form-control {
        width: 98px;
        text-align: right;
        font-weight: 700;
    }
    .ls-duration-unit {
        font-size: 0.74rem;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .cb-duration-chip {
        background: #e0e7ff;
        border: 1px solid #c7d2fe;
        color: #3730a3;
        padding: 0.36rem 0.62rem;
        border-radius: 9999px;
        font-size: 0.78rem;
        font-weight: 700;
        min-width: 68px;
        text-align: center;
    }
    .cb-file-row { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid #e5e7eb; }
    .cb-file-row:last-child { border-bottom: none; }
    .cb-file-icon { flex-shrink: 0; width: 32px; height: 40px; background: #e5e7eb; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center; color: #6b7280; }
    .cb-file-icon.pdf { background: #fef2f2; color: #dc2626; }
    .cb-file-info { flex: 1; min-width: 0; }
    .cb-file-name { font-size: 0.875rem; font-weight: 500; color: #374151; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cb-file-size { font-size: 0.75rem; color: #6b7280; display: flex; align-items: center; gap: 0.35rem; }
    .cb-file-progress { height: 4px; background: #e5e7eb; border-radius: 2px; overflow: hidden; margin-top: 0.25rem; }
    .cb-file-progress-bar { height: 100%; background: #0f172a; border-radius: 2px; transition: width 0.2s; }
    .cb-file-actions { flex-shrink: 0; }
    .cb-file-actions .btn-link { padding: 0.25rem; color: #6b7280; }
    .cb-file-actions .btn-link:hover { color: #dc2626; }
    .cb-attach-pair { display: none; }
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
        <a href="{{ route('instructor.courses.edit', $course) }}" class="ls-edit-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Course details
        </a>
        <div class="ls-edit-shell">
        <div class="ls-edit-head">
            <div>
                <h4>Edit Lesson</h4>
                <p class="ls-edit-subtitle">Update lesson media, subtitles, notes, and in-video checks in one place.</p>
            </div>
            <div class="ls-edit-actions">
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
        <div class="ls-edit-body">
        <form id="lessonForm" action="{{ route('instructor.lessons.update', [$course, $lesson]) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="ls-form-flat">
                <div class="ls-section-title">Lesson Details</div>
            <div class="ls-field">
                <label class="form-label fw-medium">Lesson Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $lesson->title) }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="ls-field">
                <label class="form-label fw-medium">Video Upload</label>
                <input type="hidden" name="uploaded_video_path" id="uploadedVideoPath" value="">
                <div class="cb-video-dropzone" id="videoDropzone">
                    <input type="file" id="videoInput" class="d-none" accept="video/mp4,video/quicktime,video/webm,.mov">
                    <div class="cb-video-preview-wrap" id="videoPreviewWrap">
                        <video id="videoPreview" class="cb-video-preview" controls preload="metadata"></video>
                    </div>
                    <div id="videoPlaceholder" class="cb-video-placeholder">
                        <svg width="40" height="40" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" class="mb-2 d-block mx-auto"><path stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <p class="mb-0 text-muted">Drag & drop a video file or click to browse</p>
                        <p class="small text-muted mb-0 mt-1">MP4, MOV, WebM up to 500MB.</p>
                        <p class="small text-muted mb-0 mt-1">For best compatibility in production, convert your video to MP4 (H.264/AAC) first using <a href="https://cloudconvert.com/" target="_blank" rel="noopener noreferrer">CloudConvert.com</a>.</p>
                    </div>
                    <p class="cb-drop-current d-none" id="currentVideoLabel"></p>
                </div>
                <div id="videoFileList" class="mt-2"></div>
                <input type="text" name="video_url" class="form-control form-control-sm mt-2" placeholder="Or paste video URL" value="{{ old('video_url', $lesson->video_url) }}">
            </div>

            <div class="ls-field">
                <label class="form-label fw-medium">Subtitles (CC)</label>
                <input type="hidden" name="uploaded_subtitle_path" id="uploadedSubtitlePath" value="{{ old('uploaded_subtitle_path') }}">
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <input type="file" id="subtitleInput" class="form-control form-control-sm" accept=".vtt" style="max-width:200px">
                    <span class="text-muted small">or</span>
                    <input type="text" name="subtitle_url" class="form-control form-control-sm" placeholder="Paste subtitle URL (.vtt)" value="{{ old('subtitle_url', $lesson->subtitle_url) }}" style="max-width:280px">
                </div>
                <p class="small text-muted mt-1 mb-0">WebVTT (.vtt) format. Enables CC button in the video player.</p>
                <div id="subtitleFileInfo" class="small text-success mt-1 {{ $lesson->subtitle_url ? '' : 'd-none' }}">{{ $lesson->subtitle_url ? 'Current: ' . Str::limit($lesson->subtitle_url, 40) : '' }}</div>
            </div>

            <div class="ls-field">
                <label class="form-label fw-medium">Description</label>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="4" placeholder="This lesson covers the fundamentals...">{{ old('content', $lesson->content) }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="ls-field">
                <label class="form-label fw-medium">Attachments</label>
                <div id="uploadedAttachmentsContainer"></div>
                <div class="cb-attach-dropzone" id="attachDropzone" onclick="document.getElementById('attachInput').click()">
                    <input type="file" id="attachInput" class="d-none" multiple accept=".pdf,.doc,.docx,.ppt,.pptx">
                    <p class="mb-0 text-muted">Drag & drop files (PDF, DOCX, PPTX) or click to browse</p>
                    @if($lesson->attachments->isNotEmpty())
                        <div class="cb-drop-current-list">
                            @foreach($lesson->attachments as $a)
                                <a href="{{ $a->path }}" target="_blank" class="cb-drop-current-chip" onclick="event.stopPropagation()">{{ Str::limit($a->original_name, 34) }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div id="attachList" class="mt-2"></div>
            </div>

            <div class="ls-field">
                <label class="form-label fw-medium">Lesson Duration</label>
                <div class="ls-duration-card">
                    <div class="ls-duration-meta">
                        <p class="ls-duration-title">Duration in seconds</p>
                        <p class="ls-duration-help">Auto-detected from video, or adjust manually for better timeline syncing.</p>
                    </div>
                    <div class="ls-duration-input-wrap">
                        <input id="lessonDurationSeconds" type="number" name="video_duration" class="form-control form-control-sm" min="0" value="{{ old('video_duration', $lesson->video_duration) }}" placeholder="0">
                        <span class="ls-duration-unit">sec</span>
                        <span class="cb-duration-chip" id="lessonDurationMinutesChip">{{ $lesson->video_duration ? floor($lesson->video_duration/60) . ' min' : '—' }}</span>
                    </div>
                </div>
            </div>

            <div class="mb-0 form-check">
                <input type="checkbox" name="is_free" value="1" class="form-check-input" id="is_free" {{ old('is_free', $lesson->is_free) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_free">Free preview</label>
            </div>
            </div>    

            <div class="ls-submit-row mt-3">
                <button type="submit" class="btn btn-primary">Save Lesson</button>
            </div>
        </form>

        <div class="ls-quiz-section">
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
        <button type="button" class="btn btn-sm ls-quiz-add-btn" data-bs-toggle="modal" data-bs-target="#addVideoQuizModal">+ Add quiz at minute</button>
        </div>
    </div>
    </div>
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
(function() {
    var uploadVideoUrl = @json(route('instructor.lessons.upload-video', $course));
    var uploadAttachmentUrl = @json(route('instructor.lessons.upload-attachment', $course));
    var uploadSubtitleUrl = @json(route('instructor.lessons.upload-subtitle', $course));
    var csrf = document.querySelector('input[name="_token"]')?.value;
    var existingVideoUrl = @json($lesson->video_url);
    var objectVideoPreviewUrl = null;

    function fmt(size) { return (size / 1024).toFixed(0) + ' KB'; }
    function fileIcon(name) {
        var ext = (name.split('.').pop() || '').toLowerCase();
        return ext === 'pdf' ? '<div class="cb-file-icon pdf">PDF</div>' : '<div class="cb-file-icon">DOC</div>';
    }

    function updateVideoPreview(src, label, isObjectUrl) {
        var preview = document.getElementById('videoPreview');
        var wrap = document.getElementById('videoPreviewWrap');
        var placeholder = document.getElementById('videoPlaceholder');
        var currentLabel = document.getElementById('currentVideoLabel');
        if (!preview || !wrap || !placeholder || !currentLabel) return;
        if (objectVideoPreviewUrl && !isObjectUrl) {
            URL.revokeObjectURL(objectVideoPreviewUrl);
            objectVideoPreviewUrl = null;
        }
        if (!src) {
            preview.removeAttribute('src');
            preview.load();
            wrap.style.display = 'none';
            placeholder.style.display = 'block';
            currentLabel.classList.add('d-none');
            currentLabel.textContent = '';
            return;
        }
        if (isObjectUrl) objectVideoPreviewUrl = src;
        preview.src = src;
        preview.load();
        wrap.style.display = 'block';
        placeholder.style.display = 'none';
        currentLabel.classList.remove('d-none');
        currentLabel.textContent = label || 'Current video';
    }

    function uploadVideo(file) {
        var list = document.getElementById('videoFileList');
        var row = document.createElement('div');
        row.className = 'cb-file-row';
        var total = file.size;
        row.innerHTML = '<div class="cb-file-icon" style="background:#dbeafe;color:#2563eb;"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg></div><div class="cb-file-info"><div class="cb-file-name">' + file.name.replace(/</g, '&lt;') + '</div><div class="cb-file-size"><span class="progress-text">0 KB of ' + fmt(total) + '</span> <span class="spinner-border spinner-border-sm" role="status"></span> Uploading...</div><div class="cb-file-progress"><div class="cb-file-progress-bar" style="width:0%"></div></div></div><div class="cb-file-actions"><button type="button" class="btn btn-link btn-sm p-0 cb-cancel" title="Cancel">×</button></div>';
        list.innerHTML = '';
        list.appendChild(row);
        document.getElementById('uploadedVideoPath').value = '';
        updateVideoPreview(URL.createObjectURL(file), 'Selected: ' + file.name, true);

        var xhr = new XMLHttpRequest();
        var fd = new FormData();
        fd.append('video', file);
        fd.append('_token', csrf);

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                var pct = Math.round((e.loaded / e.total) * 100);
                row.querySelector('.progress-text').textContent = fmt(e.loaded) + ' of ' + fmt(e.total);
                row.querySelector('.cb-file-progress-bar').style.width = pct + '%';
            }
        };
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                var res = JSON.parse(xhr.responseText);
                document.getElementById('uploadedVideoPath').value = res.path;
                row.querySelector('.cb-file-size').innerHTML = '<span>' + fmt(total) + ' of ' + fmt(total) + '</span> <span class="text-success">✓ Completed</span>';
                row.querySelector('.cb-file-progress')?.remove();
                row.querySelector('.cb-cancel')?.remove();
                var del = document.createElement('button');
                del.type = 'button';
                del.className = 'btn btn-link btn-sm p-0 text-muted';
                del.title = 'Remove';
                del.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                del.onclick = function() {
                    list.innerHTML = '';
                    document.getElementById('uploadedVideoPath').value = '';
                    updateVideoPreview(existingVideoUrl || '', existingVideoUrl ? ('Current video: ' + (existingVideoUrl.split('/').pop() || 'video')) : '', false);
                };
                row.querySelector('.cb-file-actions').appendChild(del);
            } else {
                row.querySelector('.cb-file-size').innerHTML = '<span class="text-danger">Upload failed</span>';
                row.querySelector('.cb-cancel')?.remove();
            }
        };
        xhr.onerror = function() {
            row.querySelector('.cb-file-size').innerHTML = '<span class="text-danger">Upload failed</span>';
            row.querySelector('.cb-cancel')?.remove();
        };
        row.querySelector('.cb-cancel').onclick = function() {
            xhr.abort();
            list.innerHTML = '';
            document.getElementById('uploadedVideoPath').value = '';
            row.remove();
            updateVideoPreview(existingVideoUrl || '', existingVideoUrl ? ('Current video: ' + (existingVideoUrl.split('/').pop() || 'video')) : '', false);
        };
        xhr.open('POST', uploadVideoUrl);
        xhr.send(fd);
    }

    function uploadAttachment(file, index) {
        var list = document.getElementById('attachList');
        var container = document.getElementById('uploadedAttachmentsContainer');
        var row = document.createElement('div');
        row.className = 'cb-file-row';
        row.dataset.index = index;
        var total = file.size;
        row.innerHTML = fileIcon(file.name) + '<div class="cb-file-info"><div class="cb-file-name">' + file.name.replace(/</g, '&lt;') + '</div><div class="cb-file-size"><span class="progress-text">0 KB of ' + fmt(total) + '</span> <span class="spinner-border spinner-border-sm" role="status"></span> Uploading...</div><div class="cb-file-progress"><div class="cb-file-progress-bar" style="width:0%"></div></div></div><div class="cb-file-actions"><button type="button" class="btn btn-link btn-sm p-0 cb-cancel" title="Cancel">×</button></div>';
        list.appendChild(row);

        var xhr = new XMLHttpRequest();
        var fd = new FormData();
        fd.append('attachment', file);
        fd.append('_token', csrf);

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                row.querySelector('.progress-text').textContent = fmt(e.loaded) + ' of ' + fmt(total);
                row.querySelector('.cb-file-progress-bar').style.width = Math.round((e.loaded / e.total) * 100) + '%';
            }
        };
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                var res = JSON.parse(xhr.responseText);
                var idx = container.querySelectorAll('.cb-attach-pair').length;
                var pair = document.createElement('div');
                pair.className = 'cb-attach-pair';
                pair.innerHTML = '<input type="hidden" name="uploaded_attachments[' + idx + '][path]" value="' + (res.path || '').replace(/"/g, '&quot;') + '"><input type="hidden" name="uploaded_attachments[' + idx + '][original_name]" value="' + (res.original_name || '').replace(/"/g, '&quot;').replace(/</g, '&lt;') + '">';
                container.appendChild(pair);
                row.querySelector('.cb-file-size').innerHTML = '<span>' + fmt(total) + ' of ' + fmt(total) + '</span> <span class="text-success">✓ Completed</span>';
                row.querySelector('.cb-file-progress')?.remove();
                row.querySelector('.cb-cancel')?.remove();
                var del = document.createElement('button');
                del.type = 'button';
                del.className = 'btn btn-link btn-sm p-0 text-muted';
                del.title = 'Remove';
                del.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                del.onclick = function() {
                    row.remove();
                    pair.remove();
                    reindexAttachments();
                };
                row.querySelector('.cb-file-actions').appendChild(del);
            } else {
                row.querySelector('.cb-file-size').innerHTML = '<span class="text-danger">Upload failed</span>';
                row.querySelector('.cb-cancel')?.remove();
            }
        };
        xhr.onerror = function() {
            row.querySelector('.cb-file-size').innerHTML = '<span class="text-danger">Upload failed</span>';
            row.querySelector('.cb-cancel')?.remove();
        };
        row.querySelector('.cb-cancel').onclick = function() { xhr.abort(); row.remove(); };
        xhr.open('POST', uploadAttachmentUrl);
        xhr.send(fd);
    }
    function reindexAttachments() {
        var container = document.getElementById('uploadedAttachmentsContainer');
        var pairs = container.querySelectorAll('.cb-attach-pair');
        pairs.forEach(function(pair, i) {
            var pathInp = pair.querySelector('input[name$="[path]"]');
            var nameInp = pair.querySelector('input[name$="[original_name]"]');
            if (pathInp) pathInp.name = 'uploaded_attachments[' + i + '][path]';
            if (nameInp) nameInp.name = 'uploaded_attachments[' + i + '][original_name]';
        });
    }

    document.getElementById('subtitleInput')?.addEventListener('change', function() {
        var f = this.files?.[0];
        if (!f || !f.name.toLowerCase().endsWith('.vtt')) return;
        var fd = new FormData();
        fd.append('subtitle', f);
        fd.append('_token', csrf);
        var info = document.getElementById('subtitleFileInfo');
        info.textContent = 'Uploading...';
        info.classList.remove('d-none');
        fetch(uploadSubtitleUrl, { method: 'POST', body: fd }).then(function(r) { return r.json(); }).then(function(res) {
            document.getElementById('uploadedSubtitlePath').value = res.path || '';
            document.querySelector('input[name="subtitle_url"]').value = '';
            info.textContent = 'Uploaded: ' + (res.path || '').split('/').pop();
        }).catch(function() { info.textContent = 'Upload failed'; });
    });

    if (existingVideoUrl) {
        var existingName = existingVideoUrl.split('/').pop() || 'video';
        updateVideoPreview(existingVideoUrl, 'Current video: ' + existingName, false);
    }

    document.getElementById('videoDropzone')?.addEventListener('click', function(e) {
        var clickedVideo = e.target.closest('video');
        var clickedLink = e.target.closest('a');
        if (clickedVideo || clickedLink) return;
        document.getElementById('videoInput')?.click();
    });

    document.getElementById('videoDropzone')?.addEventListener('dragover', function(e) { e.preventDefault(); this.classList.add('dragover'); });
    document.getElementById('videoDropzone')?.addEventListener('dragleave', function() { this.classList.remove('dragover'); });
    document.getElementById('videoDropzone')?.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        if (e.dataTransfer.files.length) { var f = e.dataTransfer.files[0]; if (f.type.startsWith('video/')) uploadVideo(f); }
    });
    document.getElementById('videoInput')?.addEventListener('change', function() {
        if (this.files.length) { uploadVideo(this.files[0]); this.value = ''; }
    });

    var adz = document.getElementById('attachDropzone'), ain = document.getElementById('attachInput');
    if (adz) {
        adz.addEventListener('dragover', function(e) { e.preventDefault(); e.stopPropagation(); this.classList.add('dragover'); });
        adz.addEventListener('dragleave', function(e) { e.preventDefault(); this.classList.remove('dragover'); });
        adz.addEventListener('drop', function(e) {
            e.preventDefault(); e.stopPropagation(); this.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                for (var i = 0; i < e.dataTransfer.files.length; i++) uploadAttachment(e.dataTransfer.files[i], i);
            }
        });
    }
    ain?.addEventListener('change', function() {
        for (var i = 0; i < this.files.length; i++) uploadAttachment(this.files[i], i);
        this.value = '';
    });

    var durationInput = document.getElementById('lessonDurationSeconds');
    var durationChip = document.getElementById('lessonDurationMinutesChip');
    if (durationInput && durationChip) {
        var updateDurationChip = function() {
            var seconds = Number(durationInput.value || 0);
            if (!Number.isFinite(seconds) || seconds <= 0) {
                durationChip.textContent = '—';
                return;
            }
            var mins = Math.round((seconds / 60) * 10) / 10;
            durationChip.textContent = mins + ' min';
        };
        durationInput.addEventListener('input', updateDurationChip);
        updateDurationChip();
    }
})();
</script>
@endpush
@endsection

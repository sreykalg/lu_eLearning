@extends('layouts.instructor-inner')

@include('instructor.course-builder.sidebar-styles')

@push('styles')
<style>
    .cb-video-dropzone { border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 2rem; text-align: center; background: #f9fafb; cursor: pointer; }
    .cb-video-dropzone:hover, .cb-video-dropzone.dragover { border-color: #0f172a; background: #f1f5f9; }
    .cb-attach-dropzone { border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 1rem; text-align: center; background: #f9fafb; font-size: 0.875rem; color: #6b7280; cursor: pointer; transition: all 0.2s; }
    .cb-attach-dropzone:hover, .cb-attach-dropzone.dragover { border-color: #0f172a; background: #f1f5f9; }
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
<div class="cb-wrap">
    <div class="cb-sidebar">
        @php $course->load(['modules', 'lessons', 'quizzes']); @endphp
        @include('instructor.course-builder.sidebar', ['course' => $course])
    </div>
    <div class="cb-main">
        <h4 class="mb-4 fw-bold">New Lesson</h4>
        <form action="{{ route('instructor.lessons.store', $course) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Module (optional)</label>
                <select name="module_id" class="form-select">
                    <option value="">— No module —</option>
                    @foreach($course->modules as $m)
                        <option value="{{ $m->id }}" {{ old('module_id', request('module_id')) == $m->id ? 'selected' : '' }}>{{ $m->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium">Lesson Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium">Video Upload</label>
                <input type="hidden" name="uploaded_video_path" id="uploadedVideoPath" value="">
                <div class="cb-video-dropzone" onclick="document.getElementById('videoInput').click()" id="videoDropzone">
                    <input type="file" id="videoInput" class="d-none" accept="video/mp4,video/quicktime,video/x-msvideo">
                    <svg width="40" height="40" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" class="mb-2 d-block mx-auto"><path stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <p class="mb-0 text-muted">Drag & drop a video file or click to browse</p>
                    <p class="small text-muted mb-0 mt-1">MP4, MOV up to 500MB</p>
                </div>
                <div id="videoFileList" class="mt-2"></div>
                <input type="text" name="video_url" class="form-control form-control-sm mt-2" placeholder="Or paste video URL" value="{{ old('video_url') }}">
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium">Subtitles (CC)</label>
                <input type="hidden" name="uploaded_subtitle_path" id="uploadedSubtitlePath" value="">
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <input type="file" id="subtitleInput" class="form-control form-control-sm" accept=".vtt" style="max-width:200px">
                    <span class="text-muted small">or</span>
                    <input type="text" name="subtitle_url" class="form-control form-control-sm" placeholder="Paste subtitle URL (.vtt)" value="{{ old('subtitle_url') }}" style="max-width:280px">
                </div>
                <p class="small text-muted mt-1 mb-0">WebVTT (.vtt) format. Enables CC button in the video player.</p>
                <div id="subtitleFileInfo" class="small text-success mt-1 d-none"></div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium">Description</label>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="4">{{ old('content') }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium">Attachments</label>
                <div id="uploadedAttachmentsContainer"></div>
                <div class="cb-attach-dropzone" id="attachDropzone" onclick="document.getElementById('attachInput').click()">
                    <input type="file" id="attachInput" class="d-none" multiple accept=".pdf,.doc,.docx,.ppt,.pptx">
                    <p class="mb-0 text-muted">Drag & drop files (PDF, DOCX, PPTX) or click to browse</p>
                </div>
                <div id="attachList" class="mt-2"></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-medium">Video duration (seconds)</label>
                <input type="number" name="video_duration" class="form-control" style="max-width:100px" min="0" value="{{ old('video_duration') }}" placeholder="e.g. 720">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_free" value="1" class="form-check-input" id="is_free" {{ old('is_free') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_free">Free preview</label>
            </div>
            <button type="submit" class="btn btn-primary">Add Lesson</button>
            <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var uploadVideoUrl = @json(route('instructor.lessons.upload-video', $course));
    var uploadAttachmentUrl = @json(route('instructor.lessons.upload-attachment', $course));
    var uploadSubtitleUrl = @json(route('instructor.lessons.upload-subtitle', $course));
    var csrf = document.querySelector('input[name="_token"]')?.value;

    function fmt(size) { return (size / 1024).toFixed(0) + ' KB'; }
    function fileIcon(name) {
        var ext = (name.split('.').pop() || '').toLowerCase();
        return ext === 'pdf' ? '<div class="cb-file-icon pdf">PDF</div>' : '<div class="cb-file-icon">DOC</div>';
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
                del.onclick = function() { list.innerHTML = ''; document.getElementById('uploadedVideoPath').value = ''; };
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
        row.querySelector('.cb-cancel').onclick = function() { xhr.abort(); list.innerHTML = ''; document.getElementById('uploadedVideoPath').value = ''; row.remove(); };
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
        var ext = (file.name.split('.').pop() || '').toLowerCase();
        row.innerHTML = fileIcon(file.name) + '<div class="cb-file-info"><div class="cb-file-name">' + file.name + '</div><div class="cb-file-size"><span class="progress-text">0 KB of ' + fmt(total) + '</span> <span class="spinner-border spinner-border-sm" role="status"></span> Uploading...</div><div class="cb-file-progress"><div class="cb-file-progress-bar" style="width:0%"></div></div></div><div class="cb-file-actions"><button type="button" class="btn btn-link btn-sm p-0 cb-cancel" title="Cancel">×</button></div>';
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
})();
</script>
@endpush
@endsection

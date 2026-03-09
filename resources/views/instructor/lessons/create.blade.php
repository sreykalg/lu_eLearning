@extends('layouts.instructor-inner')

@push('styles')
<style>
    .cb-wrap { display: flex; gap: 1.5rem; min-height: 560px; overflow-x: hidden; }
    .cb-sidebar { width: 380px; min-width: 380px; max-width: 380px; flex-shrink: 0; background: #fff; border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 1rem; max-height: 85vh; overflow-y: auto; overflow-x: hidden; }
    .cb-dropdown { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; color: #374151; }
    .cb-main { flex: 1; min-width: 0; background: #fff; border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 1.5rem; }
    .cb-dropdown, .cb-add-module, .cb-module-header, .cb-module-title, .cb-drag, .cb-item, .cb-label { }
    .cb-add-module { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.5rem; font-size: 0.875rem; color: #374151; text-align: left; width: 100%; }
    .cb-add-module:hover { background: #f9fafb; }
    .cb-module-header { padding: 0.35rem 0; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem; }
    .cb-module-title { color: #374151; }
    .cb-drag { color: #9ca3af; font-size: 0.75rem; cursor: default; pointer-events: none; }
    .cb-item { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; margin: 0.15rem 0; border-radius: 0.375rem; text-decoration: none; color: #374151; font-size: 0.875rem; border-left: 3px solid transparent; }
    .cb-item:hover { background: #f3f4f6; }
    .cb-item.active { background: #0f172a; color: #fff; border-left-color: #0f172a; }
    .cb-label { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cb-video-dropzone { border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 2rem; text-align: center; background: #f9fafb; cursor: pointer; }
    .cb-video-dropzone:hover, .cb-video-dropzone.dragover { border-color: #2563eb; background: #eff6ff; }
    .cb-attach-dropzone { border: 2px dashed #d1d5db; border-radius: 0.5rem; padding: 1rem; text-align: center; background: #f9fafb; font-size: 0.875rem; color: #6b7280; cursor: pointer; transition: all 0.2s; }
    .cb-attach-dropzone:hover, .cb-attach-dropzone.dragover { border-color: #2563eb; background: #eff6ff; }
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
                <div class="cb-video-dropzone" onclick="document.getElementById('videoInput').click()" id="videoDropzone">
                    <input type="file" name="video" id="videoInput" class="d-none" accept="video/mp4,video/quicktime,video/x-msvideo">
                    <svg width="40" height="40" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" class="mb-2 d-block mx-auto"><path stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <p class="mb-0 text-muted">Drag & drop a video file or click to browse</p>
                    <p class="small text-muted mb-0 mt-1">MP4, MOV up to 500MB</p>
                </div>
                <input type="text" name="video_url" class="form-control form-control-sm mt-2" placeholder="Or paste video URL" value="{{ old('video_url') }}">
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium">Description</label>
                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="4">{{ old('content') }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium">Attachments</label>
                <div class="cb-attach-dropzone" id="attachDropzone" onclick="document.getElementById('attachInput').click()">
                    <input type="file" name="attachments[]" id="attachInput" class="d-none" multiple accept=".pdf,.doc,.docx,.ppt,.pptx">
                    <p class="mb-0 text-muted">Drag & drop files (PDF, DOCX, PPTX) or click to browse</p>
                </div>
                <div id="attachList" class="small text-muted mt-2"></div>
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

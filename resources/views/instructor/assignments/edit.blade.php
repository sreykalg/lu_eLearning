@extends('layouts.instructor-inner')

@include('instructor.course-builder.sidebar-styles')

@push('styles')
<style>
    .asg-edit-shell {
        background: transparent;
        border: 0;
        border-radius: 0;
        box-shadow: none;
        overflow: visible;
    }
    .asg-edit-head {
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
    .asg-edit-head h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #0f172a;
    }
    .asg-edit-subtitle {
        margin: 0.3rem 0 0;
        font-size: 0.86rem;
        color: #64748b;
    }
    .asg-edit-actions { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .asg-edit-actions .btn { border-radius: 0.6rem; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em; padding: 0.4rem 0.7rem; }
    .asg-edit-body {
        margin-top: 0.85rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.9rem;
        padding: 1rem 1.1rem;
    }
    .asg-block {
        border: 0;
        border-radius: 0;
        background: transparent;
        padding: 0;
        margin-bottom: 0;
    }
    .asg-section-title {
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.85rem;
    }
    .asg-field {
        padding-bottom: 0.9rem;
        margin-bottom: 0.9rem;
        border-bottom: 1px solid #eef2f7;
    }
    .asg-field:last-of-type {
        border-bottom: 0;
        margin-bottom: 0.2rem;
        padding-bottom: 0;
    }
    .asg-manual-chip {
        display: inline-flex;
        align-items: center;
        border: 1px solid #dbeafe;
        background: #eff6ff;
        color: #1e3a8a;
        font-size: 0.8rem;
        font-weight: 700;
        border-radius: 9999px;
        padding: 0.34rem 0.65rem;
    }
    .asg-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.55rem;
        flex-wrap: wrap;
        padding-top: 0.9rem;
        margin-top: 0.9rem;
        border-top: 1px solid #eef2f7;
    }
    .asg-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: 0.8rem;
        background: #f8fafc;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .asg-dropzone:hover {
        border-color: #334155;
        background: #f1f5f9;
    }
    .asg-dropzone.is-dragover {
        border-color: #0f172a;
        background: #eef2ff;
        box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08);
    }
    .asg-dropzone-main {
        color: #334155;
        font-size: 0.85rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }
    .asg-dropzone-sub {
        color: #64748b;
        font-size: 0.78rem;
        margin: 0;
    }
    .asg-file-list {
        margin-top: 0.65rem;
        display: grid;
        gap: 0.5rem;
    }
    .asg-file-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.6rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        border-radius: 0.75rem;
        padding: 0.5rem 0.65rem;
    }
    .asg-file-card-link {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        min-width: 0;
        text-decoration: none;
        color: #0f172a;
    }
    .asg-file-card-link:hover { color: #0f172a; }
    .asg-file-card-icon {
        width: 36px;
        height: 36px;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        background: #f8fafc;
        flex-shrink: 0;
    }
    .asg-file-card-name {
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: underline;
        text-underline-offset: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .asg-file-card-type {
        font-size: 0.72rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-top: 0.08rem;
    }
    .asg-chip-remove {
        width: 20px;
        height: 20px;
        border-radius: 9999px;
        border: 1px solid #fecaca;
        background: #fff1f2;
        color: #b91c1c;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        padding: 0;
    }
    .asg-chip-remove:hover {
        background: #ffe4e6;
        border-color: #fca5a5;
        color: #991b1b;
    }
    .asg-existing-files {
        margin-top: 0.75rem;
        display: grid;
        gap: 0.45rem;
    }
    .asg-existing-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.6rem;
        flex-wrap: wrap;
        border: 1px solid #e2e8f0;
        border-radius: 0.65rem;
        padding: 0.45rem 0.55rem;
        background: #fff;
    }
    .asg-existing-link {
        font-size: 0.82rem;
        color: #0f172a;
        text-decoration: none;
    }
    .asg-existing-link:hover {
        text-decoration: underline;
        color: #1e293b;
    }
    .asg-existing-remove {
        border: 1px solid #fecaca;
        color: #b91c1c;
        background: #fff1f2;
        border-radius: 9999px;
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        font-weight: 700;
        line-height: 1;
    }
    .asg-existing-remove:hover {
        background: #ffe4e6;
        border-color: #fca5a5;
        color: #991b1b;
    }
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
        @php $course->load(['modules', 'lessons', 'quizzes', 'assignments']); @endphp
        @include('instructor.course-builder.sidebar', ['course' => $course, 'assignment' => $assignment])
    </div>
    <div class="cb-main">
        <div class="asg-edit-shell">
            <div class="asg-edit-head">
                <div>
                    <h4>Edit Assignment</h4>
                    <p class="asg-edit-subtitle">Update instructions, due date, grading settings, and required rules.</p>
                </div>
                <div class="asg-edit-actions">
                    <a href="{{ route('instructor.assignments.submissions', [$course, $assignment]) }}" class="btn btn-dark">
                        View submissions ({{ $assignment->submissions()->count() }})
                    </a>
                    <form action="{{ route('instructor.assignments.destroy', [$course, $assignment]) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this assignment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                    </form>
                </div>
            </div>

            <div class="asg-edit-body">
                <form action="{{ route('instructor.assignments.update', [$course, $assignment]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="asg-block">
                        <div class="asg-section-title">Assignment Details</div>
                        <div class="asg-field">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $assignment->title) }}" required>
                        </div>
                        <div class="asg-field">
                            <label class="form-label">Instructions</label>
                            <textarea name="instructions" class="form-control" rows="4">{{ old('instructions', $assignment->instructions) }}</textarea>
                        </div>
                        <div class="asg-field">
                            <label class="form-label">Link to lesson (optional)</label>
                            <select name="lesson_id" class="form-select">
                                <option value="">— None —</option>
                                @foreach($course->lessons as $l)
                                    <option value="{{ $l->id }}" {{ old('lesson_id', $assignment->lesson_id) == $l->id ? 'selected' : '' }}>{{ $l->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="asg-field">
                            <label class="form-label">Grading type</label>
                            <input type="hidden" name="grading_type" value="manual">
                            <div class="asg-manual-chip">Manual grading by instructor</div>
                        </div>
                        <div class="asg-field">
                            <label class="form-label">Assignment files (optional)</label>
                            <div id="asgAttachmentDropzone" class="asg-dropzone" role="button" tabindex="0" aria-label="Upload assignment files by dragging or clicking">
                                <div class="asg-dropzone-main">Drag and drop files here, or click to browse</div>
                                <p class="asg-dropzone-sub">PDF, DOCX, PPTX, ZIP, images, and other course files up to 50MB each.</p>
                            </div>
                            <div id="asgSelectedFiles" class="asg-file-list d-none"></div>
                            <input id="asgAttachmentInput" type="file" class="d-none @error('attachments.*') is-invalid @enderror" name="attachments[]" multiple>
                            @error('attachments')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            @error('attachments.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror

                            @if($assignment->attachments->isNotEmpty())
                                <div class="asg-existing-files">
                                    @foreach($assignment->attachments as $file)
                                        <div class="asg-existing-row">
                                            <a href="{{ asset('storage/' . $file->path) }}" target="_blank" class="asg-existing-link">{{ $file->original_name }}</a>
                                            <button type="button" class="asg-existing-remove" data-existing-remove="{{ $file->id }}" title="Remove file">X</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="row asg-field">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Max score</label>
                                <input type="number" name="max_score" class="form-control" min="0" value="{{ old('max_score', $assignment->max_score) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Due date (optional)</label>
                                <input type="datetime-local" name="due_at" class="form-control" value="{{ old('due_at', $assignment->due_at?->format('Y-m-d\TH:i')) }}">
                            </div>
                        </div>
                        <div class="asg-field">
                            <div class="mb-3 form-check">
                            <input type="checkbox" name="allow_late_submission" value="1" class="form-check-input" id="allow_late_submission" {{ old('allow_late_submission', $assignment->allow_late_submission ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_late_submission">Allow late submission</label>
                            <div class="form-text">If unchecked, students cannot submit after the due date.</div>
                            </div>
                            <div class="mb-0 form-check">
                            <input type="checkbox" name="is_required" value="1" class="form-check-input" id="is_required" {{ old('is_required', $assignment->is_required) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_required">Required</label>
                            </div>
                        </div>
                    </div>
                    <div class="asg-form-actions">
                        <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var dropzone = document.getElementById('asgAttachmentDropzone');
        var input = document.getElementById('asgAttachmentInput');
        var selectedList = document.getElementById('asgSelectedFiles');
        var form = input.closest('form');
        var selectedFiles = [];
        var getFileExt = function (filename) {
            var parts = String(filename || '').split('.');
            return parts.length > 1 ? parts.pop().toUpperCase() : 'FILE';
        };

        if (!dropzone || !input || !selectedList) return;

        var syncInputFiles = function () {
            var transfer = new DataTransfer();
            selectedFiles.forEach(function (file) { transfer.items.add(file); });
            input.files = transfer.files;
        };

        var mergeNewFiles = function (incoming) {
            if (!incoming || !incoming.length) return;
            Array.from(incoming).forEach(function (file) {
                selectedFiles.push(file);
            });
            syncInputFiles();
            renderSelectedFiles();
        };

        var renderSelectedFiles = function () {
            selectedList.innerHTML = '';
            if (!selectedFiles.length) {
                selectedList.classList.add('d-none');
                return;
            }
            selectedList.classList.remove('d-none');
            selectedFiles.forEach(function (file, index) {
                var row = document.createElement('div');
                row.className = 'asg-file-card';
                var fileUrl = URL.createObjectURL(file);
                var link = document.createElement('a');
                link.className = 'asg-file-card-link';
                link.href = fileUrl;
                link.target = '_blank';
                link.rel = 'noopener';
                link.title = file.name;
                link.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
                var icon = document.createElement('span');
                icon.className = 'asg-file-card-icon';
                icon.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M14 2H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V8z"/><path stroke-width="2" d="M14 2v6h6"/></svg>';
                var meta = document.createElement('div');
                meta.style.minWidth = '0';
                var name = document.createElement('div');
                name.className = 'asg-file-card-name';
                name.textContent = file.name;
                var type = document.createElement('div');
                type.className = 'asg-file-card-type';
                type.textContent = getFileExt(file.name);
                meta.appendChild(name);
                meta.appendChild(type);
                link.appendChild(icon);
                link.appendChild(meta);
                var remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'asg-chip-remove';
                remove.textContent = 'X';
                remove.title = 'Remove file';
                remove.addEventListener('click', function (e) {
                    e.stopPropagation();
                    selectedFiles.splice(index, 1);
                    syncInputFiles();
                    renderSelectedFiles();
                });
                row.appendChild(link);
                row.appendChild(remove);
                selectedList.appendChild(row);
            });
        };

        var preventDefaults = function (e) {
            e.preventDefault();
            e.stopPropagation();
        };

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, preventDefaults);
        });
        ['dragenter', 'dragover'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function () { dropzone.classList.add('is-dragover'); });
        });
        ['dragleave', 'drop'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function () { dropzone.classList.remove('is-dragover'); });
        });

        dropzone.addEventListener('click', function () { input.click(); });
        dropzone.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                input.click();
            }
        });
        dropzone.addEventListener('drop', function (e) {
            var files = e.dataTransfer && e.dataTransfer.files;
            if (!files || !files.length) return;
            mergeNewFiles(files);
        });

        input.addEventListener('change', function () {
            mergeNewFiles(input.files);
        });

        document.querySelectorAll('[data-existing-remove]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id = btn.getAttribute('data-existing-remove');
                if (form && id) {
                    var hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = 'remove_attachments[]';
                    hidden.value = id;
                    form.appendChild(hidden);
                }
                var row = btn.closest('.asg-existing-row');
                if (row) row.remove();
            });
        });
    });
</script>
@endpush

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
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="cb-wrap">
    <div class="cb-sidebar">
        @include('instructor.course-builder.sidebar', ['course' => $course, 'courses' => auth()->user()->courses()->orderBy('title')->get()])
    </div>
    <div class="cb-main">
        <h4 class="mb-4 fw-bold">Course details</h4>
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
            @php
                $statusLabel = match($course->approval_status ?? 'draft') {
                    'pending' => 'Pending Review',
                    'approved' => 'Approved',
                    'needs_revision' => 'Needs Revision',
                    default => 'Draft',
                };
            @endphp
            <div class="mb-3">
                <span class="badge {{ $course->approval_status === 'approved' ? 'bg-success' : ($course->approval_status === 'pending' ? 'bg-warning text-dark' : ($course->approval_status === 'needs_revision' ? 'bg-danger' : 'bg-secondary')) }}">{{ $statusLabel }}</span>
                @if($course->revision_notes)
                    <div class="mt-2 p-3 rounded bg-light border">
                        <small class="text-muted d-block mb-1">Feedback from HoD:</small>
                        <p class="mb-0 small">{{ $course->revision_notes }}</p>
                    </div>
                @endif
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <button type="submit" class="btn btn-primary">Save Course</button>
                @if(in_array($course->approval_status ?? 'draft', ['draft', 'needs_revision']))
                    <form action="{{ route('instructor.courses.submit-approval', $course) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Submit for Approval</button>
                    </form>
                @endif
                <form action="{{ route('instructor.courses.destroy', $course) }}" method="POST" class="d-inline ms-auto" onsubmit="return confirm('Delete this course? All lessons, quizzes, and assignments will be removed. This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">Delete Course</button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection

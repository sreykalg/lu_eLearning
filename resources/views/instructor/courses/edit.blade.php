@extends('layouts.instructor-inner')

@push('styles')
<style>
    .cb-wrap { display: flex; gap: 1.5rem; min-height: 560px; overflow-x: hidden; }
    .cb-sidebar { width: 380px; min-width: 380px; max-width: 380px; flex-shrink: 0; background: #fff; border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 1rem; max-height: 85vh; overflow-y: auto; overflow-x: hidden; }
    .cb-main { flex: 1; min-width: 0; background: #fff; border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 1.5rem; }
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
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_published" value="1" class="form-check-input" id="is_published" {{ old('is_published', $course->is_published) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">Published</label>
            </div>
            <button type="submit" class="btn btn-primary">Save Course</button>
        </form>
    </div>
</div>
@endsection

@extends('layouts.instructor-inner')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Post Announcement</h1>
    <p class="text-muted mb-0">Notify students enrolled in a specific course</p>
</div>

@if($courses->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <p class="mb-3">You need at least one course before posting announcements.</p>
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">Create course</a>
        </div>
    </div>
@else
    <div class="row g-4 align-items-start">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <form action="{{ route('instructor.announcements.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="course_id" class="form-label fw-medium">Course</label>
                            <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                <option value="">Select a course</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Only students enrolled in this course will see the announcement.</small>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label fw-medium">Title</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" maxlength="255" required placeholder="e.g. Important: Quiz moved to Friday">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="body" class="form-label fw-medium">Message</label>
                            <textarea name="body" id="body" class="form-control @error('body') is-invalid @enderror" rows="5" maxlength="5000" required placeholder="Write your announcement...">{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="expires_at" class="form-label fw-medium">Expiration (optional)</label>
                            <input type="datetime-local" name="expires_at" id="expires_at" class="form-control @error('expires_at') is-invalid @enderror" value="{{ old('expires_at') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">After this date/time, students will no longer see this announcement.</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger">Post Announcement</button>
                            <a href="{{ route('instructor.announcements.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Announcement History</h5>
                    @forelse($recentAnnouncements as $a)
                        <div class="border rounded p-3 mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                <span class="fw-semibold">{{ $a->title }}</span>
                                @if($a->expires_at && $a->expires_at->isPast())
                                    <span class="badge bg-secondary">Expired</span>
                                @elseif($a->expires_at)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-info text-dark">No Expiry</span>
                                @endif
                            </div>
                            <div class="small text-muted mb-1">{{ $a->course->title }} · Posted {{ $a->created_at->format('M j, Y g:i A') }}</div>
                            @if($a->expires_at)
                                <div class="small text-muted mb-2">Expires {{ $a->expires_at->format('M j, Y g:i A') }}</div>
                            @endif
                            <div class="small text-secondary" style="white-space: pre-wrap;">{{ \Illuminate\Support\Str::limit($a->body, 220) }}</div>
                            <form action="{{ route('instructor.announcements.update', $a) }}" method="POST" class="d-flex gap-2 align-items-end mt-3">
                                @csrf
                                @method('PATCH')
                                <div class="flex-grow-1">
                                    <label class="form-label small mb-1">Update expiry</label>
                                    <input
                                        type="datetime-local"
                                        name="expires_at"
                                        class="form-control form-control-sm"
                                        value="{{ $a->expires_at ? $a->expires_at->format('Y-m-d\TH:i') : '' }}"
                                    >
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Edit</button>
                            </form>
                            <form action="{{ route('instructor.announcements.destroy', $a) }}" method="POST" class="mt-2" onsubmit="return confirm('Remove this announcement?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No announcement history yet.</p>
                    @endforelse
                    <div class="mt-3">
                        <a href="{{ route('instructor.announcements.index') }}" class="btn btn-sm btn-outline-secondary">View full history</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

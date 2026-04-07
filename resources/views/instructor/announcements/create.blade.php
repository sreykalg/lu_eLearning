@extends('layouts.instructor-inner')

@push('styles')
<style>
    .annc-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 1.35rem 1.5rem;
        color: #fff;
        margin-bottom: 1.25rem;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2);
    }
    .annc-hero .hero-left { display: flex; align-items: center; gap: 1rem; }
    .annc-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .annc-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .annc-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; }

    .annc-panel {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }
    .annc-panel__body { padding: 1.15rem 1.2rem 1.25rem; }
    .annc-panel__title {
        margin: 0 0 0.9rem;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
    }
    .annc-panel .form-label { font-weight: 600; color: #334155; font-size: 0.875rem; }
    .annc-panel .form-control,
    .annc-panel .form-select {
        border-radius: 0.65rem;
        border-color: #cbd5e1;
    }
    .annc-panel .form-control:focus,
    .annc-panel .form-select:focus {
        border-color: #0f172a;
        box-shadow: 0 0 0 0.2rem rgba(15, 23, 42, 0.08);
    }
    .annc-history-item {
        border: 1px solid #e2e8f0;
        border-radius: 0.8rem;
        padding: 0.85rem 0.9rem;
        background: #fff;
    }
    .annc-history-item + .annc-history-item { margin-top: 0.65rem; }
    .annc-history-item .history-title { font-weight: 700; color: #0f172a; margin: 0; }
    .annc-history-item .history-meta { font-size: 0.8rem; color: #64748b; margin: 0.2rem 0 0; }
    .annc-history-item .history-body { margin: 0.55rem 0 0; color: #334155; font-size: 0.86rem; white-space: pre-wrap; }
    .annc-actions { display: flex; gap: 0.55rem; flex-wrap: wrap; margin-top: 0.9rem; }
</style>
@endpush

@section('content')
<div class="annc-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
        </div>
        <div>
            <h1 class="hero-title">Post Announcement</h1>
            <p class="hero-subtitle">Notify students in a specific course and manage expiry from one place.</p>
        </div>
    </div>
</div>

@if($courses->isEmpty())
    <div class="annc-panel">
        <div class="annc-panel__body text-center py-5 text-muted">
            <p class="mb-3">You need at least one course before posting announcements.</p>
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">Create course</a>
        </div>
    </div>
@else
    <div class="row g-4 align-items-start">
        <div class="col-lg-7">
            <div class="annc-panel h-100">
                <div class="annc-panel__body">
                    <h2 class="annc-panel__title">New Announcement</h2>
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
                            <button type="submit" class="btn btn-dark">Post Announcement</button>
                            <a href="{{ route('instructor.announcements.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="annc-panel">
                <div class="annc-panel__body">
                    <h2 class="annc-panel__title">Announcement History</h2>
                    @forelse($recentAnnouncements as $a)
                        <div class="annc-history-item">
                            <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                <p class="history-title">{{ $a->title }}</p>
                                @if($a->expires_at && $a->expires_at->isPast())
                                    <span class="badge bg-secondary">Expired</span>
                                @elseif($a->expires_at)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-info text-dark">No Expiry</span>
                                @endif
                            </div>
                            <p class="history-meta">{{ $a->course->title }} · Posted {{ $a->created_at->format('M j, Y g:i A') }}</p>
                            @if($a->expires_at)
                                <p class="history-meta mb-2">Expires {{ $a->expires_at->format('M j, Y g:i A') }}</p>
                            @endif
                            <p class="history-body">{{ \Illuminate\Support\Str::limit($a->body, 220) }}</p>
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
                    <div class="annc-actions">
                        <a href="{{ route('instructor.announcements.index') }}" class="btn btn-sm btn-outline-secondary">View full history</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

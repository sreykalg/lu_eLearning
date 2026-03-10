@php
$layout = auth()->check()
    ? (auth()->user()->isStudent() ? 'layouts.student-inner' : (auth()->user()->isInstructor() ? 'layouts.instructor-inner' : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.app-inner')))
    : 'layouts.public-inner';
@endphp
@extends($layout)

@push('styles')
<style>
    .discussion-chip { padding: 0.35rem 0.75rem; font-size: 0.875rem; border-radius: 9999px; text-decoration: none; transition: all 0.15s; }
    .discussion-chip.active { background: #0f172a; color: #fff; border: none; }
    .discussion-chip.inactive { background: #e5e7eb; color: #374151; border: none; }
    .discussion-chip.inactive:hover { background: #d1d5db; color: #111; }
    .discussion-instructor-badge { background: #0f172a; color: #fff; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 500; }
    .discussion-post-btn { background: #0f172a; color: #fff; border: none; padding: 0.35rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; }
    .discussion-post-btn:hover { background: #1e293b; color: #fff; }
</style>
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="mb-4">
    <h1 class="h3 fw-bold mb-1">Discussions</h1>
    <p class="text-muted mb-0">{{ $discussions->total() }} {{ Str::plural('thread', $discussions->total()) }} across all courses</p>
</div>

{{-- Course filter chips --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('discussions.index', array_merge(request()->except('course_id', 'page'), ['course_id' => null])) }}"
       class="discussion-chip {{ !request('course_id') ? 'active' : 'inactive' }}">
        All Courses
    </a>
    @foreach($courses as $c)
        <a href="{{ route('discussions.index', array_merge(request()->except('course_id', 'page'), ['course_id' => $c->id])) }}"
           class="discussion-chip {{ (string)request('course_id') === (string)$c->id ? 'active' : 'inactive' }}">
            {{ Str::limit($c->title, 28) }}
        </a>
    @endforeach
</div>

@auth
{{-- New discussion input --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('discussions.store') }}" class="d-flex flex-column" enctype="multipart/form-data">
            @csrf
            <textarea name="body" class="form-control border-0 p-3" rows="3" required
                      placeholder="Start a new discussion..." style="resize: none;"></textarea>
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top bg-light flex-wrap gap-2">
                <label class="mb-0 d-flex align-items-center gap-1 text-muted small cursor-pointer" style="cursor: pointer;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Attach image</span>
                    <input type="file" name="attachments[]" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" multiple class="d-none">
                </label>
                <span class="text-muted small" id="discussion-files-label">No file chosen</span>
                @if(request('course_id'))
                    <input type="hidden" name="course_id" value="{{ request('course_id') }}">
                @endif
                <button type="submit" class="discussion-post-btn d-flex align-items-center gap-2">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Post
                </button>
            </div>
        </form>
    </div>
</div>
@endauth

{{-- Thread list --}}
@forelse($discussions as $d)
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            {{-- Parent post --}}
            <div class="d-flex gap-3">
                @php
                    $name = $d->user->name ?? 'U';
                    $parts = array_filter(explode(' ', $name));
                    $initials = count($parts) >= 2 ? Str::upper(mb_substr($parts[0],0,1).mb_substr($parts[count($parts)-1],0,1)) : Str::upper(mb_substr($name,0,2));
                @endphp
                <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold" style="width:40px;height:40px;background:#0f172a;font-size:0.875rem;">{{ $initials }}</div>
                <div class="flex-grow-1 min-width-0">
                    <div class="text-muted small mb-1">
                        {{ $d->user->name }}
                        @if($d->course)<span> · {{ $d->course->title }}</span>@endif
                        <span> · {{ $d->created_at->diffForHumans() }}</span>
                    </div>
                    <a href="{{ route('discussions.show', $d) }}" class="text-decoration-none text-dark">
                        <p class="mb-2">{{ $d->body }}</p>
                    </a>
                    @if($d->attachments->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @foreach($d->attachments as $att)
                                <a href="{{ asset($att->path) }}" target="_blank" rel="noopener" class="d-inline-block">
                                    <img src="{{ asset($att->path) }}" alt="{{ $att->original_name }}" class="rounded" style="max-width:120px;max-height:80px;object-fit:cover;">
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <div class="d-flex align-items-center gap-3 text-muted small">
                        @auth
                        <form action="{{ route('discussions.like', $d) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 d-flex align-items-center gap-1 text-decoration-none {{ $d->hasLiked(auth()->user()) ? 'text-primary' : 'text-muted' }}" style="border:none;background:none;">
                                <svg width="16" height="16" fill="{{ $d->hasLiked(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"/></svg>
                                {{ $d->likes->count() }}
                            </button>
                        </form>
                        @else
                        <span class="d-flex align-items-center gap-1 text-muted">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"/></svg>
                            {{ $d->likes->count() }}
                        </span>
                        @endauth
                        <a href="{{ route('discussions.show', $d) }}" class="text-muted text-decoration-none d-flex align-items-center gap-1">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            {{ $d->replies->count() }} {{ Str::plural('reply', $d->replies->count()) }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Replies (top-level, indented) --}}
            @foreach($d->replies->take(3) as $reply)
                <div class="mt-3 ps-5 border-start ms-2" style="border-color:#e2e8f0 !important;">
                    <div class="d-flex gap-3">
                        @php
                            $rname = $reply->user->name ?? 'U';
                            $rparts = array_filter(explode(' ', $rname));
                            $rinitials = count($rparts) >= 2 ? Str::upper(mb_substr($rparts[0],0,1).mb_substr($rparts[count($rparts)-1],0,1)) : Str::upper(mb_substr($rname,0,2));
                        @endphp
                        <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold" style="width:36px;height:36px;background:#64748b;font-size:0.75rem;">{{ $rinitials }}</div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-medium small">{{ $reply->user->name }}</span>
                                @if($reply->is_instructor_answer)
                                    <span class="discussion-instructor-badge">Instructor</span>
                                @endif
                                <span class="text-muted small">{{ $reply->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mb-0 small text-body-secondary">{{ Str::limit($reply->body, 200) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
            @if($d->replies->count() > 3)
                <div class="mt-2 ps-5 ms-2">
                    <a href="{{ route('discussions.show', $d) }}" class="small text-primary text-decoration-none">View all {{ $d->replies->count() }} replies →</a>
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="text-center py-5 text-muted rounded-3 bg-white border">
        <p class="mb-0">No discussions yet.@auth Be the first to start one!@endauth</p>
    </div>
@endforelse

{{ $discussions->links() }}

@auth
@push('scripts')
<script>
document.querySelector('input[name="attachments[]"]')?.addEventListener('change', function() {
    var n = this.files.length;
    document.getElementById('discussion-files-label').textContent = n ? n + ' file(s) chosen' : 'No file chosen';
});
</script>
@endpush
@endauth
@endsection

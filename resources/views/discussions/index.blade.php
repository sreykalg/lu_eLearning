@php
$layout = auth()->check()
    ? (auth()->user()->isStudent() ? 'layouts.student-inner' : (auth()->user()->isInstructor() ? 'layouts.instructor-inner' : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.app-inner')))
    : 'layouts.app-simple';
@endphp
@extends($layout)

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

{{-- Course filter tabs --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('discussions.index', array_merge(request()->except('course_id', 'page'), ['course_id' => null])) }}"
       class="btn btn-sm {{ !request('course_id') ? 'btn-primary' : 'btn-outline-secondary' }}">
        All Courses
    </a>
    @foreach($courses as $c)
        <a href="{{ route('discussions.index', array_merge(request()->except('course_id', 'page'), ['course_id' => $c->id])) }}"
           class="btn btn-sm {{ (string)request('course_id') === (string)$c->id ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ Str::limit($c->title, 28) }}
        </a>
    @endforeach
</div>

@auth
{{-- New discussion input --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0">
        <form method="POST" action="{{ route('discussions.store') }}" class="d-flex flex-column">
            @csrf
            <textarea name="body" class="form-control border-0 p-3" rows="3" required
                      placeholder="Start a new discussion..." style="resize: none;"></textarea>
            <div class="d-flex justify-content-end p-2 border-top bg-light">
                @if(request('course_id'))
                    <input type="hidden" name="course_id" value="{{ request('course_id') }}">
                @endif
                <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
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
                <div class="flex-shrink-0 rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold" style="width:40px;height:40px;background:#2563eb;font-size:0.875rem;">{{ $initials }}</div>
                <div class="flex-grow-1 min-width-0">
                    <div class="text-muted small mb-1">
                        {{ $d->user->name }}
                        @if($d->course)<span> · {{ $d->course->title }}</span>@endif
                        <span> · {{ $d->created_at->diffForHumans() }}</span>
                    </div>
                    <a href="{{ route('discussions.show', $d) }}" class="text-decoration-none text-dark">
                        <p class="mb-2">{{ $d->body }}</p>
                    </a>
                    <div class="d-flex align-items-center gap-3 text-muted small">
                        <span class="d-flex align-items-center gap-1">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"/></svg>
                            0
                        </span>
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
                                    <span class="badge bg-primary" style="font-size:0.65rem;">Instructor</span>
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
@endsection

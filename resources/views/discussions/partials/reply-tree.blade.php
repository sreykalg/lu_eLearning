@php
    $rname = $reply->user->name ?? 'U';
    $rparts = array_filter(explode(' ', $rname));
    $rinitials = count($rparts) >= 2 ? Str::upper(mb_substr($rparts[0],0,1).mb_substr($rparts[count($rparts)-1],0,1)) : Str::upper(mb_substr($rname,0,2));
@endphp
<div class="discussion-card-reply" data-reply-id="{{ $reply->id }}">
    <div class="discussion-meta">
        <div class="avatar {{ $reply->is_instructor_answer ? 'instructor' : 'regular' }}">{{ $rinitials }}</div>
        <div class="flex-grow-1 min-width-0 d-flex align-items-center flex-wrap gap-2">
            <span class="name">{{ $reply->user->name }}</span>
            @if($reply->is_instructor_answer)
                <span class="discussion-instructor-badge">Instructor</span>
            @endif
            <span class="context">{{ $reply->created_at->diffForHumans() }}</span>
        </div>
    </div>
    <p class="mb-2 text-body-secondary small">{!! \App\Support\MentionHelper::highlight($reply->body) !!}</p>
    <div class="reply-actions d-flex align-items-center gap-3 text-muted small">
        @auth
        <form action="{{ route('discussions.reply-like', $reply) }}" method="POST" class="d-inline mb-0">
            @csrf
            <button type="submit" class="btn btn-link p-0 border-0 bg-transparent d-inline-flex align-items-center gap-1 text-decoration-none {{ $reply->hasLiked(auth()->user()) ? 'text-primary' : 'text-muted' }}" style="font-size:inherit;">
                <svg width="14" height="14" fill="{{ $reply->hasLiked(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"/></svg>
                Like {{ $reply->likes->count() > 0 ? $reply->likes->count() : '' }}
            </button>
        </form>
        @else
        <span class="d-inline-flex align-items-center gap-1">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"/></svg>
            Like {{ $reply->likes->count() > 0 ? $reply->likes->count() : '' }}
        </span>
        @endauth
        @auth
        <button type="button" class="btn-reply-toggle btn btn-link p-0 border-0 bg-transparent text-decoration-none" data-target="reply-form-{{ $reply->id }}" style="font-size:inherit;color:inherit;">Reply</button>
        @endauth
    </div>

    @auth
    <div class="discussion-reply-inline reply-form-toggle" id="reply-form-{{ $reply->id }}" style="display:none;">
        <form method="POST" action="{{ route('discussions.reply') }}" class="d-flex align-items-center gap-2" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
            <input type="hidden" name="parent_id" value="{{ $reply->id }}">
            <div class="reply-input-wrap flex-grow-1">
                <textarea name="body" class="form-control" rows="2" required placeholder="{{ auth()->user()->isInstructor() ? 'Reply as instructor...' : 'Write a reply...' }}" style="resize:none;"></textarea>
            </div>
            <button type="submit" class="btn btn-send" title="Send">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </button>
        </form>
    </div>
    @endauth

    @if($reply->replies->isNotEmpty())
        <div class="reply-children">
            @foreach($reply->replies as $child)
                @include('discussions.partials.reply-tree', ['reply' => $child, 'discussion' => $discussion])
            @endforeach
        </div>
    @endif
</div>

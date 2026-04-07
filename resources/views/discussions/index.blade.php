@php
$layout = auth()->check()
    ? (auth()->user()->isStudent() ? 'layouts.student-inner' : (auth()->user()->isInstructor() ? 'layouts.instructor-inner' : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.app-inner')))
    : 'layouts.public-inner';
@endphp
@extends($layout)

@push('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 1rem;
        padding: 1.35rem 1.5rem;
        color: #fff;
        margin-bottom: 1.25rem;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.2);
    }
    .page-hero .hero-left { display: flex; align-items: center; gap: 1rem; }
    .page-hero .hero-icon {
        width: 48px; height: 48px; border-radius: 0.85rem;
        background: rgba(255,255,255,0.14);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .page-hero .hero-title { margin: 0; font-weight: 800; letter-spacing: -0.02em; font-size: 1.35rem; }
    .page-hero .hero-subtitle { margin: 0.35rem 0 0; color: rgba(255,255,255,0.85); font-size: 0.9rem; max-width: 36rem; line-height: 1.45; }
    .disc-filter-toolbar {
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .disc-filter-toolbar__head {
        padding: 0.85rem 1.15rem;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
    }
    .disc-filter-toolbar__head h2 {
        margin: 0;
        font-size: 0.8125rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #64748b;
    }
    .disc-filter-toolbar__body { padding: 1rem 1.15rem 1.1rem; }
    .disc-chips-scroll {
        display: flex;
        flex-wrap: nowrap;
        gap: 0.45rem;
        overflow-x: auto;
        padding-bottom: 0.2rem;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .disc-chips-scroll::-webkit-scrollbar { height: 6px; }
    .disc-chips-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    .discussion-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.85rem;
        font-size: 0.8125rem;
        font-weight: 600;
        border-radius: 9999px;
        text-decoration: none;
        white-space: nowrap;
        flex-shrink: 0;
        transition: background 0.15s, color 0.15s, border-color 0.15s, box-shadow 0.15s;
    }
    .discussion-chip.active {
        background: #0f172a;
        color: #fff;
        border: 1px solid #0f172a;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.2);
    }
    .discussion-chip.inactive {
        background: #fff;
        color: #334155;
        border: 1px solid #e2e8f0;
    }
    .discussion-chip.inactive:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .discussion-instructor-badge { background: #0f172a; color: #fff; padding: 0.25rem 0.6rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 600; }
    .discussion-post-btn { background: #0f172a; color: #fff; border: none; padding: 0.35rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; }
    .discussion-post-btn:hover { background: #1e293b; color: #fff; }
    .discussion-card-main {
        border-radius: 1rem;
        background: #fff;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
        transition: box-shadow 0.2s;
    }
    .discussion-card-main:hover { box-shadow: 0 8px 32px rgba(15, 23, 42, 0.08); }
    .discussion-card-main .discussion-meta { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
    .discussion-card-main .discussion-meta .avatar { width: 44px; height: 44px; border-radius: 50%; background: #e2e8f0; color: #0f172a; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; font-weight: 600; flex-shrink: 0; }
    .discussion-card-main .discussion-meta .name { font-weight: 600; color: #111; }
    .discussion-card-main .discussion-meta .context { color: #64748b; font-size: 0.8125rem; }
    .discussion-card-main .engagement-row { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9; }
    .discussion-card-main .engagement-left { display: flex; align-items: center; gap: 1.25rem; }
    .discussion-card-main .action-btn { background: none; border: none; padding: 0.25rem 0.5rem; font-size: 0.8125rem; color: #64748b; display: inline-flex; align-items: center; gap: 0.35rem; }
    .discussion-card-main .action-btn:hover { color: #0f172a; }
    .discussion-card-main .action-btn.delete { color: #dc2626; }
    .discussion-card-main .action-btn.delete:hover { color: #b91c1c; }
    .discussion-card-reply { margin-top: 1rem; padding: 1rem 0 1rem 1.5rem; border-left: 3px solid #e2e8f0; margin-left: 0.5rem; }
    .discussion-card-reply .reply-children { margin-left: 0.5rem; }
    .discussion-card-reply .reply-actions .btn-reply-toggle:hover { color: #0f172a !important; }
    .discussion-card-reply .discussion-meta { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem; }
    .discussion-card-reply .discussion-meta .avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600; flex-shrink: 0; }
    .discussion-card-reply .discussion-meta .avatar.instructor { background: #0f172a; color: #fff; }
    .discussion-card-reply .discussion-meta .avatar.regular { background: #e2e8f0; color: #0f172a; }
    .discussion-card-reply .name { font-weight: 600; color: #111; font-size: 0.9rem; }
    .discussion-card-reply .context { color: #64748b; font-size: 0.8125rem; }
    .mention { color: #2563eb; font-weight: 600; }
    .discussion-reply-inline { margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid #e5e8f0; }
    .discussion-reply-inline .reply-input-wrap { flex: 1; min-width: 0; }
    .discussion-reply-inline .reply-input-wrap .form-control { border-radius: 0.5rem; background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.6rem 1rem; }
    .discussion-reply-inline .reply-input-wrap .form-control:focus { background: #fff; border-color: #0f172a; }
    .discussion-reply-inline .btn-send { width: 44px; height: 44px; border-radius: 50%; background: #0f172a; color: #fff; border: none; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .discussion-reply-inline .btn-send:hover { background: #1e293b; color: #fff; }
    .discussion-composer {
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        background: #fff;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.06);
    }
    .discussion-composer:focus-within {
        border-color: #cbd5e1;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.08), 0 0 0 3px rgba(15, 23, 42, 0.06);
    }
    .discussion-composer .composer-inner { display: flex; align-items: flex-start; gap: 0.85rem; padding: 1rem 1.15rem 1.1rem; }
    .discussion-composer .composer-avatar {
        width: 44px; height: 44px; border-radius: 50%;
        background: linear-gradient(145deg, #e2e8f0 0%, #f1f5f9 100%);
        color: #0f172a;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.875rem; font-weight: 700; flex-shrink: 0;
        border: 1px solid #e2e8f0;
    }
    .discussion-composer .composer-body { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 0.5rem; }
    .discussion-composer .composer-textarea { border: none; resize: none; font-size: 0.9375rem; padding: 0.25rem 0; min-height: 2.5rem; }
    .discussion-composer .composer-textarea:focus { box-shadow: none; outline: none; }
    .discussion-composer .composer-textarea::placeholder { color: #94a3b8; }
    .discussion-composer .composer-footer { display: flex; justify-content: space-between; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
    .discussion-composer .composer-actions { display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap; }
    .discussion-composer .composer-actions .composer-icon { background: none; border: none; padding: 0.45rem; color: #64748b; cursor: pointer; border-radius: 0.5rem; display: inline-flex; align-items: center; justify-content: center; }
    .discussion-composer .composer-actions .composer-icon:hover { color: #0f172a; background: #f1f5f9; }
    .discussion-composer .composer-send {
        width: 44px; height: 44px; border-radius: 50%;
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        color: #fff; border: none;
        display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.2);
    }
    .discussion-composer .composer-send:hover { background: #0f172a; color: #fff; }
    .discussion-composer .composer-files-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: #64748b;
        margin-left: 0.15rem;
        padding: 0.25rem 0;
    }
    .composer-popover { position: fixed; z-index: 9999; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(0,0,0,.15); padding: 0.5rem; display: none; }
    .composer-popover.show { display: block; }
    .composer-emoji-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: 0.25rem; max-height: 200px; overflow-y: auto; }
    .composer-emoji-grid span { font-size: 1.25rem; cursor: pointer; padding: 0.25rem; border-radius: 0.25rem; }
    .composer-emoji-grid span:hover { background: #f1f5f9; }
    .composer-users-dropdown { max-height: 220px; overflow-y: auto; min-width: 200px; }
    .composer-users-dropdown .user-item { padding: 0.5rem 0.75rem; cursor: pointer; border-radius: 0.375rem; display: flex; align-items: center; gap: 0.5rem; }
    .composer-users-dropdown .user-item:hover { background: #f1f5f9; }
    .composer-body-wrap { position: relative; }
    .disc-empty {
        text-align: center;
        padding: 3rem 1.5rem;
        border-radius: 1rem;
        border: 1px dashed #cbd5e1;
        background: #fafbfc;
        color: #64748b;
    }
    .disc-empty svg { opacity: 0.4; margin-bottom: 0.75rem; color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="hero-left">
        <div class="hero-icon">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M8 10h8M8 14h5M5 20l1.5-3H19a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <h1 class="hero-title">Discussions</h1>
            <p class="hero-subtitle">{{ $discussions->total() }} {{ Str::plural('thread', $discussions->total()) }} across your courses</p>
        </div>
    </div>
</div>

<div class="disc-filter-toolbar">
    <div class="disc-filter-toolbar__head">
        <h2>Filter by course</h2>
    </div>
    <div class="disc-filter-toolbar__body">
        <div class="disc-chips-scroll">
            <a href="{{ route('discussions.index', array_merge(request()->except('course_id', 'page'), ['course_id' => null])) }}"
               class="discussion-chip {{ !request('course_id') ? 'active' : 'inactive' }}"
               title="All courses">All courses</a>
            @foreach($courses as $c)
                <a href="{{ route('discussions.index', array_merge(request()->except('course_id', 'page'), ['course_id' => $c->id])) }}"
                   class="discussion-chip {{ (string)request('course_id') === (string)$c->id ? 'active' : 'inactive' }}"
                   title="{{ $c->title }}">{{ Str::limit($c->title, 40) }}</a>
            @endforeach
        </div>
    </div>
</div>

@auth
<script>window.MENTION_USERS = @json($mentionUsers ?? []);</script>
@php
    $composerName = auth()->user()->name ?? 'U';
    $composerParts = array_filter(explode(' ', $composerName));
    $composerCleanParts = array_values(array_filter(array_map(fn ($p) => preg_replace('/[^A-Za-z0-9]/', '', $p), $composerParts)));
    if (count($composerCleanParts) >= 2) {
        $composerInitials = Str::upper(mb_substr($composerCleanParts[0], 0, 1) . mb_substr($composerCleanParts[count($composerCleanParts)-1], 0, 1));
    } else {
        $composerFallback = preg_replace('/[^A-Za-z0-9]/', '', $composerName);
        $composerInitials = Str::upper(mb_substr($composerFallback !== '' ? $composerFallback : 'U', 0, 2));
    }
@endphp
{{-- New discussion input --}}
<div class="discussion-composer mb-4">
    <form method="POST" action="{{ route('discussions.store') }}" enctype="multipart/form-data">
        @csrf
        @if(request('course_id'))
            <input type="hidden" name="course_id" value="{{ request('course_id') }}">
        @endif
        <div class="composer-inner">
            <div class="composer-avatar">{{ $composerInitials }}</div>
            <div class="composer-body composer-body-wrap">
                <textarea name="body" id="composer-textarea" class="composer-textarea form-control" rows="2" required placeholder="Start a new discussion or @ mention someone..." style="resize:none;"></textarea>
                {{-- Emoji popover --}}
                <div class="composer-popover" id="emoji-popover">
                    <div class="composer-emoji-grid" id="emoji-grid"></div>
                </div>
                {{-- People/Users dropdown --}}
                <div class="composer-popover" id="people-popover">
                    <div class="composer-users-dropdown" id="people-list"></div>
                </div>
                {{-- @ mention dropdown --}}
                <div class="composer-popover" id="mention-popover">
                    <div class="composer-users-dropdown" id="mention-list"></div>
                </div>
                <div class="composer-footer">
                    <div class="composer-actions">
                        <button type="button" class="composer-icon" id="btn-people" title="Mention someone" aria-label="Mention someone">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4" stroke-width="2"/><path stroke-width="2" d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        </button>
                        <button type="button" class="composer-icon" id="btn-emoji" title="Add emoji" aria-label="Add emoji">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-width="2" d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9" stroke-width="2" stroke-linecap="round"/><line x1="15" y1="9" x2="15.01" y2="9" stroke-width="2" stroke-linecap="round"/></svg>
                        </button>
                        <label class="composer-icon mb-0" title="Attach image" style="cursor:pointer;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <input type="file" name="attachments[]" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" multiple class="d-none">
                        </label>
                        <span class="composer-files-label" id="discussion-files-label">No file chosen</span>
                    </div>
                    <button type="submit" class="composer-send" title="Post">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endauth

{{-- Thread list --}}
@forelse($discussions as $d)
    <div class="discussion-card-main card border-0 mb-4">
        <div class="card-body p-4">
            @php
                $name = $d->user->name ?? 'U';
                $parts = array_filter(explode(' ', $name));
                $cleanParts = array_values(array_filter(array_map(fn ($p) => preg_replace('/[^A-Za-z0-9]/', '', $p), $parts)));
                if (count($cleanParts) >= 2) {
                    $initials = Str::upper(mb_substr($cleanParts[0], 0, 1) . mb_substr($cleanParts[count($cleanParts)-1], 0, 1));
                } else {
                    $fallback = preg_replace('/[^A-Za-z0-9]/', '', $name);
                    $initials = Str::upper(mb_substr($fallback !== '' ? $fallback : 'U', 0, 2));
                }
                $topReplies = $d->replies;
            @endphp
            {{-- Main post --}}
            <div class="discussion-meta">
                <div class="avatar">{{ $initials }}</div>
                <div class="flex-grow-1 min-w-0">
                    <div class="name">{{ $d->user->name }}</div>
                    <div class="context">
                        @if($d->course){{ Str::limit($d->course->title, 30) }} · @endif{{ $d->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            @php
                $bodyLen = strlen($d->body);
                $truncateAt = 80;
                $showSeeMore = $bodyLen > $truncateAt;
            @endphp
            <div class="post-body mb-3" @if($showSeeMore) data-full="{{ \App\Support\MentionHelper::highlight($d->body) }}" data-short="{{ \App\Support\MentionHelper::highlight(Str::limit($d->body, $truncateAt)) }}" @endif>
                <p class="mb-0 text-dark post-body-text">{!! \App\Support\MentionHelper::highlight($showSeeMore ? Str::limit($d->body, $truncateAt) : $d->body) !!}</p>
            </div>
                    @if($d->attachments->isNotEmpty())
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach($d->attachments as $att)
                        <a href="{{ asset($att->path) }}" target="_blank" rel="noopener" class="d-inline-block">
                            <img src="{{ asset($att->path) }}" alt="{{ $att->original_name }}" class="rounded" style="max-width:120px;max-height:80px;object-fit:cover;">
                        </a>
                    @endforeach
                </div>
            @endif
            <div class="engagement-row">
                <div class="engagement-left text-muted small d-flex align-items-center gap-3">
                    @auth
                    <form action="{{ route('discussions.like', $d) }}" method="POST" class="d-inline mb-0">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 d-inline-flex align-items-center gap-1 text-decoration-none {{ $d->hasLiked(auth()->user()) ? 'text-primary' : 'text-muted' }}" style="border:none;background:none;font-size:inherit;">
                            <svg width="16" height="16" fill="{{ $d->hasLiked(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"/></svg>
                            {{ $d->likes->count() }}
                        </button>
                    </form>
                    @else
                    <span class="d-inline-flex align-items-center gap-1">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M14 9V5a3 3 0 00-3-3l-4 9v11h11.28a2 2 0 002-1.7l1.38-9a2 2 0 00-2-2.3zM7 22H4a2 2 0 01-2-2v-7a2 2 0 012-2h3"/></svg>
                        {{ $d->likes->count() }}
                    </span>
                    @endauth
                    @auth
                    <button type="button" class="btn-reply-toggle btn btn-link p-0 border-0 bg-transparent d-inline-flex align-items-center gap-1 text-muted text-decoration-none" data-target="reply-form-post-{{ $d->id }}" style="font-size:inherit;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Reply
                    </button>
                    @endauth
                    @if($totalReplies = $topReplies->count())
                    <button type="button" class="btn-view-replies btn btn-link p-0 border-0 bg-transparent text-muted text-decoration-none" data-target="replies-thread-{{ $d->id }}" style="font-size:inherit;">
                        View {{ $totalReplies }} {{ Str::plural('reply', $totalReplies) }}
                    </button>
                    @endif
                    @if($showSeeMore)
                    <button type="button" class="see-more-btn btn btn-link p-0 border-0 bg-transparent text-primary text-decoration-none" data-expanded="0" style="font-size:inherit;">See more</button>
                    @endif
                </div>
                @if(auth()->check() && auth()->user()->id === $d->user_id)
                <form action="{{ route('discussions.destroy', $d) }}" method="POST" class="d-inline mb-0" onsubmit="return confirm('Delete this discussion?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete
                    </button>
                </form>
                @endif
            </div>

            {{-- Post-level reply form (hidden until Reply clicked) --}}
            @auth
            <div class="discussion-reply-inline reply-form-toggle" id="reply-form-post-{{ $d->id }}" style="display:none;">
                <form method="POST" action="{{ route('discussions.reply') }}" class="d-flex align-items-center gap-2" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="discussion_id" value="{{ $d->id }}">
                    <div class="reply-input-wrap flex-grow-1">
                        <textarea name="body" class="form-control" rows="2" required placeholder="{{ auth()->user()->isInstructor() ? 'Reply as instructor...' : 'Write a reply...' }}" style="resize:none;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-send" title="Send">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                </form>
            </div>
            @endauth

            {{-- Replies (hidden until "View X replies" clicked, Facebook-style) --}}
            @if($topReplies->isNotEmpty())
            <div class="replies-thread mt-2" id="replies-thread-{{ $d->id }}" style="display:none;">
                @php
                    $initialReplyCount = 3;
                    $totalReplies = $topReplies->count();
                    $visibleReplies = $topReplies->take($initialReplyCount);
                    $hiddenReplies = $topReplies->slice($initialReplyCount);
                    $hiddenCount = $hiddenReplies->count();
                @endphp
                @foreach($visibleReplies as $reply)
                    @include('discussions.partials.reply-tree', ['reply' => $reply, 'discussion' => $d])
                @endforeach
                @if($hiddenCount > 0)
                <div class="replies-collapsed mt-2" id="replies-hidden-{{ $d->id }}" style="display:none;">
                    @foreach($hiddenReplies as $reply)
                        @include('discussions.partials.reply-tree', ['reply' => $reply, 'discussion' => $d])
                    @endforeach
                </div>
                <div class="replies-toggle-wrap mt-2">
                    <button type="button" class="btn-see-more-replies btn btn-link p-0 text-primary text-decoration-none small" data-target="replies-hidden-{{ $d->id }}">
                        See {{ $hiddenCount }} more {{ Str::plural('reply', $hiddenCount) }}
                    </button>
                    <button type="button" class="btn-see-less-replies btn btn-link p-0 text-primary text-decoration-none small" data-target="replies-hidden-{{ $d->id }}" style="display:none;">
                        See less
                    </button>
                </div>
                @endif
                <button type="button" class="btn-hide-replies btn btn-link p-0 text-muted text-decoration-none small mt-2" data-target="replies-thread-{{ $d->id }}">
                    Hide replies
                </button>
            </div>
            @endif
        </div>
    </div>
@empty
    <div class="disc-empty">
        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M8 10h8M8 14h5M5 20l1.5-3H19a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        <p class="fw-semibold text-secondary mb-1">No threads yet</p>
        <p class="small mb-0">
            @auth
                Start a discussion above — use @@mention to ping someone, or attach images.
            @endauth
            @guest
                Sign in to join the conversation.
            @endguest
        </p>
    </div>
@endforelse

{{ $discussions->links() }}

@push('scripts')
<script>
document.querySelector('input[name="attachments[]"]')?.addEventListener('change', function() {
    var n = this.files.length;
    document.getElementById('discussion-files-label').textContent = n ? n + ' file(s) chosen' : 'No file chosen';
});
document.querySelectorAll('.btn-view-replies').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var targetId = this.getAttribute('data-target');
        var target = targetId ? document.getElementById(targetId) : null;
        if (target) target.style.display = 'block';
    });
});
document.querySelectorAll('.btn-hide-replies').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var targetId = this.getAttribute('data-target');
        var target = targetId ? document.getElementById(targetId) : null;
        if (target) target.style.display = 'none';
    });
});
document.querySelectorAll('.btn-reply-toggle').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var targetId = this.getAttribute('data-target');
        var target = targetId ? document.getElementById(targetId) : null;
        if (target) {
            var isHidden = target.style.display === 'none' || !target.style.display;
            document.querySelectorAll('.reply-form-toggle').forEach(function(f) { f.style.display = 'none'; });
            target.style.display = isHidden ? 'block' : 'none';
            if (isHidden) {
                var card = this.closest('.card-body');
                var repliesThread = card ? card.querySelector('.replies-thread') : null;
                if (repliesThread) repliesThread.style.display = 'block';
            }
        }
    });
});
document.querySelectorAll('.btn-see-more-replies').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var targetId = this.getAttribute('data-target');
        var target = targetId ? document.getElementById(targetId) : null;
        var seeLess = this.parentElement.querySelector('.btn-see-less-replies');
        if (target && seeLess) {
            target.style.display = 'block';
            this.style.display = 'none';
            seeLess.style.display = 'inline';
        }
    });
});
document.querySelectorAll('.btn-see-less-replies').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var targetId = this.getAttribute('data-target');
        var target = targetId ? document.getElementById(targetId) : null;
        var seeMore = this.parentElement.querySelector('.btn-see-more-replies');
        if (target && seeMore) {
            target.style.display = 'none';
            this.style.display = 'none';
            seeMore.style.display = 'inline';
            seeMore.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
});
document.querySelectorAll('.see-more-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var card = this.closest('.card-body');
        var postBody = card ? card.querySelector('.post-body') : null;
        if (!postBody || !postBody.dataset.full) return;
        var textEl = postBody.querySelector('.post-body-text');
        var expanded = this.getAttribute('data-expanded') === '1';
        if (expanded) {
            textEl.innerHTML = postBody.dataset.short;
            this.textContent = 'See more';
            this.setAttribute('data-expanded', '0');
        } else {
            textEl.innerHTML = postBody.dataset.full;
            this.textContent = 'See less';
            this.setAttribute('data-expanded', '1');
        }
    });
});
(function(){
var ta = document.getElementById('composer-textarea');
if (!ta) return;
var emojiPopover = document.getElementById('emoji-popover');
var peoplePopover = document.getElementById('people-popover');
var mentionPopover = document.getElementById('mention-popover');
var mentionList = document.getElementById('mention-list');
var peopleList = document.getElementById('people-list');
var emojiGrid = document.getElementById('emoji-grid');
var btnEmoji = document.getElementById('btn-emoji');
var btnPeople = document.getElementById('btn-people');

var emojis = ['😀','😃','😄','😁','😅','😂','🤣','😊','😇','🙂','😉','😌','😍','🥰','😘','😗','👍','👎','👏','🙌','✌️','🤞','💪','🙏','❤️','🧡','💛','💚','💙','💜','🖤','💯','🔥','✨','⭐','🌟','💫','😎','🤔','😢','😭','🎉','🎊','🙈','🙉','🙊'];
emojiGrid.innerHTML = emojis.map(function(e){ return '<span data-emoji="'+e+'">'+e+'</span>'; }).join('');

function positionEmojiPopover(popover, btn) {
    if (!popover || !btn) return;
    var rect = btn.getBoundingClientRect();
    popover.style.left = rect.left + 'px';
    popover.style.top = (rect.bottom + 8) + 'px';
    popover.style.transform = 'none';
}

function positionPeoplePopover(popover, btn) {
    if (!popover || !btn) return;
    var rect = btn.getBoundingClientRect();
    popover.style.left = rect.left + 'px';
    popover.style.top = (rect.bottom + 8) + 'px';
    popover.style.transform = 'none';
}

function positionMentionPopover(popover, el) {
    if (!popover || !el) return;
    var rect = el.getBoundingClientRect();
    popover.style.left = rect.left + 'px';
    popover.style.top = (rect.bottom + 8) + 'px';
    popover.style.transform = 'none';
}

function insertAtCursor(text) {
    var start = ta.selectionStart, end = ta.selectionEnd, val = ta.value;
    ta.value = val.slice(0, start) + text + val.slice(end);
    ta.selectionStart = ta.selectionEnd = start + text.length;
    ta.focus();
}

function closeAllPopovers() {
    [emojiPopover, peoplePopover, mentionPopover].forEach(function(p){ if(p) p.classList.remove('show'); });
}

btnEmoji && btnEmoji.addEventListener('click', function(e) {
    e.preventDefault();
    var isOpen = emojiPopover.classList.toggle('show');
    if (isOpen) { positionEmojiPopover(emojiPopover, btnEmoji); }
    peoplePopover && peoplePopover.classList.remove('show');
    mentionPopover && mentionPopover.classList.remove('show');
});

emojiGrid.querySelectorAll('span').forEach(function(span) {
    span.addEventListener('click', function() {
        insertAtCursor(this.dataset.emoji);
        closeAllPopovers();
    });
});

function renderUserList(container, users, onSelect) {
    var esc = function(s){ return String(s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); };
    container.innerHTML = users.length ? users.map(function(u) {
        return '<div class="user-item" data-name="'+esc(u.name)+'">'+esc(u.name)+'</div>';
    }).join('') : '<div class="p-2 text-muted small">No users found</div>';
    container.querySelectorAll('.user-item').forEach(function(el) {
        el.addEventListener('click', function() {
            onSelect(this.dataset.name);
            closeAllPopovers();
        });
    });
}

function filterUsers(query) {
    var users = window.MENTION_USERS || [];
    if (!query) return users.slice(0, 15);
    var q = query.toLowerCase();
    return users.filter(function(u){ return (u.name || '').toLowerCase().indexOf(q) >= 0; }).slice(0, 15);
}

btnPeople && btnPeople.addEventListener('click', function(e) {
    e.preventDefault();
    peoplePopover.classList.toggle('show');
    if (peoplePopover.classList.contains('show')) {
        positionPeoplePopover(peoplePopover, btnPeople);
        renderUserList(peopleList, filterUsers(''), function(name) { insertAtCursor('@' + name + ' '); });
    }
    emojiPopover && emojiPopover.classList.remove('show');
    mentionPopover && mentionPopover.classList.remove('show');
});

var mentionQuery = '';
var mentionStart = 0;

ta.addEventListener('input', function() {
    var val = this.value, pos = this.selectionStart;
    var before = val.slice(0, pos);
    var lastAt = before.lastIndexOf('@');
    if (lastAt === -1 || (lastAt > 0 && /[\w]/.test(before[lastAt - 1]))) {
        mentionPopover.classList.remove('show');
        return;
    }
    mentionQuery = before.slice(lastAt + 1).split(/\s/)[0] || '';
    mentionStart = lastAt;
    var users = filterUsers(mentionQuery);
    if (!users.length) { mentionPopover.classList.remove('show'); return; }
    renderUserList(mentionList, users, function(name) {
        var v = ta.value;
        ta.value = v.slice(0, mentionStart) + '@' + name + ' ' + v.slice(ta.selectionStart);
        ta.selectionStart = ta.selectionEnd = mentionStart + name.length + 3;
        ta.focus();
    });
    positionMentionPopover(mentionPopover, ta);
    mentionPopover.classList.add('show');
});

ta.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAllPopovers();
});

document.addEventListener('click', function(e) {
    if (!ta.closest('.discussion-composer').contains(e.target)) closeAllPopovers();
});
})();
</script>
@endpush
@endsection

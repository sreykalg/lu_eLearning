<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('discussions.index') }}" class="text-decoration-none small mb-1 d-inline-block" style="color: var(--lu-purple);">&larr; Back to Community</a>
            <h2 class="mb-0 fw-bold" style="color: var(--lu-deep-purple);">{{ $discussion->title }}</h2>
        </div>
    </x-slot>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <p class="mb-3">{{ $discussion->body }}</p>
                <div class="text-muted small">
                    {{ $discussion->user->name }}
                    @if($discussion->course) · {{ $discussion->course->title }} @endif
                    · {{ $discussion->created_at->diffForHumans() }}
                </div>
            </div>
        </div>

        <h5 class="mb-3">Replies ({{ $discussion->allReplies->count() }})</h5>
        @foreach ($discussion->replies as $reply)
            @include('discussions.partials.reply', ['reply' => $reply])
        @endforeach

        @auth
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('discussions.reply') }}">
                        @csrf
                        <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
                        <div class="mb-3">
                            <label class="form-label">Your Reply</label>
                            <textarea name="body" class="form-control" rows="3" required placeholder="Help your peers or ask a follow-up..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-lu-primary">Post Reply</button>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</x-app-layout>

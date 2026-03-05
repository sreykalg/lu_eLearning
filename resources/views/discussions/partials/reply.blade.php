<div class="card mb-2 {{ $reply->is_instructor_answer ? 'border-success' : 'border-0' }} shadow-sm">
    <div class="card-body py-3">
        @if($reply->is_instructor_answer)
            <span class="badge bg-success mb-2">Instructor</span>
        @endif
        <p class="mb-2">{{ $reply->body }}</p>
        <div class="text-muted small">{{ $reply->user->name }} · {{ $reply->created_at->diffForHumans() }}</div>
        @foreach($reply->replies as $child)
            <div class="ms-4 mt-2">
                @include('discussions.partials.reply', ['reply' => $child])
            </div>
        @endforeach
    </div>
</div>

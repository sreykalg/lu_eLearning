<div class="card mb-2 {{ $reply->is_instructor_answer ? 'border-success' : 'border-0' }} shadow-sm">
    <div class="card-body py-3">
        @if($reply->is_instructor_answer)
            <span class="badge bg-success mb-2">Instructor</span>
        @endif
        <p class="mb-2">{{ $reply->body }}</p>
        @if($reply->attachments->isNotEmpty())
            <div class="d-flex flex-wrap gap-2 mb-2">
                @foreach($reply->attachments as $att)
                    <a href="{{ asset($att->path) }}" target="_blank" rel="noopener" class="d-inline-block">
                        <img src="{{ asset($att->path) }}" alt="{{ $att->original_name }}" class="rounded shadow-sm" style="max-width:160px;max-height:120px;object-fit:cover;">
                    </a>
                @endforeach
            </div>
        @endif
        <div class="text-muted small">{{ $reply->user->name }} · {{ $reply->created_at->diffForHumans() }}</div>
        @foreach($reply->replies as $child)
            <div class="ms-4 mt-2">
                @include('discussions.partials.reply', ['reply' => $child])
            </div>
        @endforeach
    </div>
</div>

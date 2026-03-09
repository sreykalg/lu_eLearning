@php
$layout = auth()->check()
    ? (auth()->user()->isStudent() ? 'layouts.student-inner' : (auth()->user()->isInstructor() ? 'layouts.instructor-inner' : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.app-inner')))
    : 'layouts.app-simple';
@endphp
@extends($layout)

@push('styles')
<style>
    .discussion-instructor-badge { background: #0f172a; color: #fff; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 500; }
    .discussion-reply-hidden { transition: opacity 0.2s; }
</style>
@endpush

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('discussions.index') }}" class="text-decoration-none small text-muted d-inline-block mb-1">&larr; Back to Community</a>
    <h1 class="h3 fw-bold mb-0" style="color: #0f172a;">{{ $discussion->title }}</h1>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <p class="mb-3">{{ $discussion->body }}</p>
        @if($discussion->attachments->isNotEmpty())
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach($discussion->attachments as $att)
                    <a href="{{ asset($att->path) }}" target="_blank" rel="noopener" class="d-inline-block">
                        <img src="{{ asset($att->path) }}" alt="{{ $att->original_name }}" class="rounded shadow-sm" style="max-width:200px;max-height:150px;object-fit:cover;">
                    </a>
                @endforeach
            </div>
        @endif
        <div class="text-muted small">
            {{ $discussion->user->name }}
            @if($discussion->course) · {{ $discussion->course->title }} @endif
            · {{ $discussion->created_at->diffForHumans() }}
        </div>
    </div>
</div>

@php
    $initialRepliesShown = 5;
    $topReplies = $discussion->replies;
    $totalReplies = $topReplies->count();
@endphp
<h5 class="mb-3 fw-semibold">Replies ({{ $discussion->allReplies->count() }})</h5>
@foreach ($topReplies as $index => $reply)
    <div class="discussion-reply-item {{ $index >= $initialRepliesShown ? 'discussion-reply-hidden' : '' }}" style="{{ $index >= $initialRepliesShown ? 'display: none;' : '' }}">
        @include('discussions.partials.reply', ['reply' => $reply])
    </div>
@endforeach
@if($totalReplies > $initialRepliesShown)
    <div class="mt-2">
        <button type="button" class="btn btn-link p-0 text-primary text-decoration-none small" id="discussion-see-more-btn" data-shown="0" data-total-hidden="{{ $totalReplies - $initialRepliesShown }}">
            See more ({{ $totalReplies - $initialRepliesShown }} more)
        </button>
    </div>
@endif

@auth
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <form method="POST" action="{{ route('discussions.reply') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
                <div class="mb-3">
                    <label class="form-label">Your Reply</label>
                    <textarea name="body" class="form-control" rows="3" required placeholder="Help your peers or ask a follow-up..."></textarea>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <label class="mb-0 d-flex align-items-center gap-1 text-muted small" style="cursor: pointer;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Attach image</span>
                        <input type="file" name="attachments[]" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" multiple class="d-none">
                    </label>
                    <span class="text-muted small" id="reply-files-label">No file chosen</span>
                </div>
                <button type="submit" class="btn btn-lu-primary">Post Reply</button>
            </form>
        </div>
    </div>
@endauth

@push('scripts')
<script>
(function() {
    document.querySelector('input[name="attachments[]"]')?.addEventListener('change', function() {
        var n = this.files.length;
        var el = document.getElementById('reply-files-label');
        if (el) el.textContent = n ? n + ' file(s) chosen' : 'No file chosen';
    });
    var seeMoreBtn = document.getElementById('discussion-see-more-btn');
    if (seeMoreBtn) {
        var hidden = document.querySelectorAll('.discussion-reply-hidden');
        var totalHidden = parseInt(seeMoreBtn.getAttribute('data-total-hidden'), 10) || 0;
        seeMoreBtn.addEventListener('click', function() {
            var isExpanded = seeMoreBtn.getAttribute('data-expanded') === '1';
            if (isExpanded) {
                hidden.forEach(function(el) { el.style.display = 'none'; });
                seeMoreBtn.textContent = 'See more (' + totalHidden + ' more)';
                seeMoreBtn.setAttribute('data-expanded', '0');
            } else {
                hidden.forEach(function(el) { el.style.display = ''; });
                seeMoreBtn.textContent = 'See less';
                seeMoreBtn.setAttribute('data-expanded', '1');
            }
        });
    }
})();
</script>
@endpush
@endsection

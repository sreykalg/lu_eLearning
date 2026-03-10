@php
$layout = auth()->check()
    ? (auth()->user()->isStudent() ? 'layouts.student-inner' : (auth()->user()->isInstructor() ? 'layouts.instructor-inner' : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.app-inner')))
    : 'layouts.public-inner';
@endphp
@extends($layout)

@push('styles')
<style>
    .discussion-instructor-badge { background: #0f172a; color: #fff; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 500; }
    .mention { color: #2563eb; font-weight: 600; }
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

<div class="card border-0 shadow-sm mb-2">
    <div class="card-body">
        <p class="mb-3">{!! \App\Support\MentionHelper::highlight($discussion->body) !!}</p>
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
        @auth
            <div class="mt-3 pt-3 border-top">
                @include('discussions.partials.reply-form', ['discussion' => $discussion, 'parentId' => null])
            </div>
        @endauth
    </div>
</div>

@php
    $initialRepliesShown = 5;
    $topReplies = $discussion->replies;
    $totalReplies = $topReplies->count();
    $totalCount = $discussion->allReplies->count();
@endphp
<div class="d-flex align-items-center gap-2 flex-wrap mb-3">
    <h5 class="mb-0 fw-semibold">Replies ({{ $totalCount }})</h5>
    @if($totalReplies > $initialRepliesShown)
        <button type="button" class="btn btn-link p-0 text-primary text-decoration-none small" id="discussion-see-more-btn" data-shown="0" data-total-hidden="{{ $totalReplies - $initialRepliesShown }}">
            See more ({{ $totalReplies - $initialRepliesShown }} more)
        </button>
    @endif
</div>
@foreach ($topReplies as $index => $reply)
    <div class="discussion-reply-item {{ $index >= $initialRepliesShown ? 'discussion-reply-hidden' : '' }}" style="{{ $index >= $initialRepliesShown ? 'display: none;' : '' }}">
        @include('discussions.partials.reply', ['reply' => $reply, 'discussion' => $discussion])
    </div>
@endforeach

@push('scripts')
<script>
(function() {
    document.querySelectorAll('.reply-attach-input').forEach(function(input) {
        input.addEventListener('change', function() {
            var n = this.files.length;
            var label = this.closest('form').querySelector('.reply-files-label');
            if (label) label.textContent = n ? n + ' file(s) chosen' : '';
        });
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

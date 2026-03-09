@php
    $placeholder = auth()->user()->isInstructor() ? 'Reply as instructor...' : 'Reply...';
@endphp
<form method="POST" action="{{ route('discussions.reply') }}" class="discussion-reply-inplace mt-2" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="discussion_id" value="{{ $discussion->id }}">
    @if(isset($parentId) && $parentId)
        <input type="hidden" name="parent_id" value="{{ $parentId }}">
    @endif
    <div class="d-flex flex-wrap align-items-end gap-2">
        <div class="flex-grow-1 min-width-0">
            <textarea name="body" class="form-control form-control-sm" rows="2" required placeholder="{{ $placeholder }}" style="resize: none;"></textarea>
            <div class="d-flex align-items-center gap-2 mt-1">
                <label class="mb-0 d-flex align-items-center gap-1 text-muted small" style="cursor: pointer;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="small">Image</span>
                    <input type="file" name="attachments[]" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" multiple class="d-none reply-attach-input">
                </label>
                <span class="reply-files-label text-muted small"></span>
            </div>
        </div>
        <button type="submit" class="btn btn-sm flex-shrink-0 d-flex align-items-center gap-1" style="background:#0f172a;color:#fff;border:none;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            Reply
        </button>
    </div>
</form>

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscussionReplyAttachment extends Model
{
    protected $fillable = ['discussion_reply_id', 'path', 'original_name'];

    public function discussionReply(): BelongsTo
    {
        return $this->belongsTo(DiscussionReply::class);
    }
}

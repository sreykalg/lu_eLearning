<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscussionReplyLike extends Model
{
    protected $table = 'discussion_reply_likes';

    protected $fillable = ['discussion_reply_id', 'user_id'];

    public function discussionReply(): BelongsTo
    {
        return $this->belongsTo(DiscussionReply::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

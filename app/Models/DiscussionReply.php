<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionReply extends Model
{
    use HasFactory;

    protected $table = 'discussion_replies';

    protected $fillable = [
        'discussion_id',
        'user_id',
        'parent_id',
        'body',
        'is_instructor_answer',
    ];

    protected $casts = [
        'is_instructor_answer' => 'boolean',
    ];

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(DiscussionReply::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(DiscussionReply::class, 'parent_id');
    }

    public function attachments()
    {
        return $this->hasMany(DiscussionReplyAttachment::class, 'discussion_reply_id');
    }
}

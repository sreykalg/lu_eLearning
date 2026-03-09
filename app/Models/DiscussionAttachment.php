<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscussionAttachment extends Model
{
    protected $fillable = ['discussion_id', 'path', 'original_name'];

    public function discussion(): BelongsTo
    {
        return $this->belongsTo(Discussion::class);
    }
}

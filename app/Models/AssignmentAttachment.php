<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'path',
        'original_name',
        'mime_type',
        'size',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStoryArchive extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'cover_image',
        'visibility',
        'story_items',
    ];

    protected $casts = [
        'story_items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

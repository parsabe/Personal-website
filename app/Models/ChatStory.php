<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'media_url',
        'expires_at',
        'is_archived',
        'is_highlight',
        'countdown_target_at',
        'privacy',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'countdown_target_at' => 'datetime',
        'is_archived' => 'boolean',
        'is_highlight' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now())->where('is_archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where(function ($q) {
            $q->where('expires_at', '<=', now())->orWhere('is_archived', true);
        });
    }

    public function scopeHighlights($query)
    {
        return $query->where('is_highlight', true);
    }
}

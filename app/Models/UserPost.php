<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'content',
        'media_url',
        'media_type',
        'likes_count',
        'reposts_count',
        'bookmarks_count',
        'liked_by_users',
        'bookmarked_by_users',
        'reposted_by_users',
        'privacy',
        'scheduled_at',
    ];

    protected $casts = [
        'liked_by_users' => 'array',
        'bookmarked_by_users' => 'array',
        'reposted_by_users' => 'array',
        'scheduled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class, 'user_post_id')->orderBy('created_at', 'asc');
    }

    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('scheduled_at')->orWhere('scheduled_at', '<=', now());
        });
    }
}

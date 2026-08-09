<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_post_id',
        'user_id',
        'comment',
    ];

    public function post()
    {
        return $this->belongsTo(UserPost::class, 'user_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

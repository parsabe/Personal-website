<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NigmaPuzzle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'riddle',
        'cipher_type',
        'encrypted_solution',
        'is_solved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

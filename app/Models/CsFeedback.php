<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsFeedback extends Model
{
    use HasFactory;

    protected $table = 'cs_feedbacks';

    protected $fillable = [
        'cs_student_id',
        'email',
        'ideas',
        'feedback',
        'questions',
        'received_all_files',
        'reply',
        'replied_at',
    ];

    protected $casts = [
        'received_all_files' => 'boolean',
        'replied_at'         => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(CsStudent::class, 'cs_student_id');
    }
}

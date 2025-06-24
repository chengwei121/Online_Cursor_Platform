<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assignment_id',
        'submission_text',
        'submission_file',
        'score',
        'feedback',
        'submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
} 
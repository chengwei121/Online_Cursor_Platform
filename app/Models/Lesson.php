<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_url',
        'duration',
        'order'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function previous()
    {
        return $this->course->lessons()
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();
    }

    public function next()
    {
        return $this->course->lessons()
            ->where('order', '>', $this->order)
            ->orderBy('order', 'asc')
            ->first();
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}

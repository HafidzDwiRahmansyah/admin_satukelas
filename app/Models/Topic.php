<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';

    protected $fillable = [
        'title',
        'course_id',
        'urutan',
    ];

    protected $casts = [
        'course_id' => 'integer',
        'urutan' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class)->orderBy('id');
    }
}

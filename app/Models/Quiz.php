<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';

    protected $fillable = [
        'title',
        'duration',
        'passing_grade',
        'topic_id',
    ];

    protected $casts = [
        'duration' => 'integer',
        'passing_grade' => 'decimal:2',
        'topic_id' => 'integer',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
    }
}

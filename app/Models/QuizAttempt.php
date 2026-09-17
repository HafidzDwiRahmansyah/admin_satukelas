<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $table = 'quiz_attempts';

    protected $fillable = [
        'quiz_id',
        'user_id',
        'earned_mark',
        'remaining_time',
        'result',
        'status',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'is_published',
        'total_seats',
        'time_starts',
        'time_ends',
        'meeting_link',
        'passcode',
        'is_free', // tetap auto TRUE
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_free' => 'boolean',
        'time_starts' => 'datetime',
        'time_ends' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

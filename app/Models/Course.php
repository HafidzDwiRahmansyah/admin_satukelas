<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Membership;
use App\Models\Certificate;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'learning_path_id',
        'course_type_id',
        'title',
        'thumbnail',
        'price',
        'description',
        'instructor_id',
        'objective',
        'is_favorit',
    ];

    // Relasi ke Learning Path
    public function learningPath()
    {
        return $this->belongsTo(LearningPath::class, 'learning_path_id');
    }

    // Relasi ke Course Type
    // public function courseType()
    // {
    //     return $this->belongsTo(CourseType::class, 'course_type_id');
    // }

    // Relasi ke Instructor (user dengan role instructor)
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id')
                    ->where('role', 'instructor');
    }

    public function paketMemberships()
    {
        return $this->hasMany(PaketMembership::class);
    }

    public function learningPaths()
    {
        return $this->belongsToMany(
            LearningPath::class,
            'course_learning_path',
            'course_id',
            'learning_path_id'
        );
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }
}

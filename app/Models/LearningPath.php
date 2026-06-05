<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningPath extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_method_id',
        'title',
        'thumbnail',
        'price',
        'description',
        'objective',
        'certificate_url',
    ];

    protected $attributes = [
        'is_show' => true,
        'sub_title' => null,
        'sub_title_ujian' => null,
    ];

    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'course_learning_path',
            'learning_path_id',
            'course_id'
        );
    }
}

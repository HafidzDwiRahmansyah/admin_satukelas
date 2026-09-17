<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';

    protected $fillable = [
        'title',
        'topic_id',
        'material_type',
        'duration',
        'material_url',
    ];

    protected $casts = [
        'topic_id' => 'integer',
        'duration' => 'integer',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}

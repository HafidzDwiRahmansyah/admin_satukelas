<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaketMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',       // Nama paket
        'price',       // Harga paket
        'masa_aktif',  // Masa aktif dalam hari/bulan
        'description', // Deskripsi paket
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function learningPaths()
    {
        return $this->belongsToMany(
            LearningPath::class,
            'paket_membership_learning_path',
            'paket_membership_id',
            'learning_path_id'
        );
    }
}

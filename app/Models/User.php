<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'position',
        // 'type_user',
        'telephone',
        'sex',
        'company',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Ganti hasOne menjadi hasMany untuk multiple memberships
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function certificates()
    {
        return $this->hasMany(\App\Models\Certificate::class);
    }
}

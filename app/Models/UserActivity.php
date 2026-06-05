<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserActivity extends Model
{
    protected $table = 'user_activity';

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'last_activity',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
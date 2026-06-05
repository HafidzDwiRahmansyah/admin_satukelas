<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'paket_membership_id',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function paket()
    {
        return $this->belongsTo(PaketMembership::class, 'paket_membership_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

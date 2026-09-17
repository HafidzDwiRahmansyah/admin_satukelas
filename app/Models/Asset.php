<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'url',
        'bucket',
        'file_name',
        'extension',
    ];

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'asset_id');
    }

    public function certificateTemplates()
    {
        return $this->hasMany(CertificateTemplate::class, 'asset_id');
    }
}

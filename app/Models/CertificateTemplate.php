<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $table = 'certificate_template';

    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}

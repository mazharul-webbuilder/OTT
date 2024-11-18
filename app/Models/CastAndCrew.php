<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CastAndCrew extends Model
{
    use HasFactory, Loggable;
    protected $guarded = [];

    public function ottContents()
    {
        return $this->belongsToMany(OttContent::class, 'ott_content_cast_and_crew')->withPivot('role');
    }

}

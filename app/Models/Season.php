<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];
    public function series(){
        return $this->belongsTo(OttSeries::class, 'ott_series_id');
    }

    public function ottContents(){
        return $this->hasMany(OttContent::class,'season_id')->orderBy('episode_number','asc');
    }
}

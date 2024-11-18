<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OttSeries extends Model
{
    use HasFactory, Loggable;
    protected $guarded = [];

    public function ottContents(){
        return $this->hasMany(OttContent::class,'series_id')->orderBy('episode_number','asc');
    }
    public function rootCategory(){
        return $this->belongsTo(RootCategory::class)->withDefault();
    }
    public function subCategory(){
        return $this->belongsTo(SubCategory::class)->withDefault();
    }
    public function subSubCategory(){
        return $this->belongsTo(SubSubCategory::class)->withDefault();
    }

    public function seasons(){
        return $this->hasMany(Season::class,'ott_series_id');
    }

}

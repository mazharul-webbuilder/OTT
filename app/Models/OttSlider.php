<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OttSlider extends Model
{
    use HasFactory, Loggable;
    protected $guarded = [];

    public function rootCategory()
    {
        return $this->belongsTo(RootCategory::class)->withDefault();
    }

    public function ottContent()
    {
        return $this->belongsTo(OttContent::class, 'content_url', 'uuid')->withDefault();
    }
}

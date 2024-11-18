<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    public function ott_content()
    {
        return $this->belongsTo(OttContent::class,'content_id');
    }

}

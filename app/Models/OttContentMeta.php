<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OttContentMeta extends Model
{
    use HasFactory, Loggable;
    protected $guarded = [];
    public function ottContent(){
        return $this->belongsTo(OttContent::class);
    }
}

<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OttContentReview extends Model
{
    use HasFactory, Loggable;
    protected $guarded = [];
    public function ottContents(){
        return $this->belongsTo(OttContent::class,'content_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function user_meta(){
        return $this->belongsTo(UserMeta::class,'user_id');
    }
}

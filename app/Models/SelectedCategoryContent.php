<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedCategoryContent extends Model
{
    use HasFactory;

    public function ottContent(){
        return $this->belongsTo(OttContent::class,'ott_content_id');
    }
}

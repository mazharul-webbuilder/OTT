<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontendCustomContentSectionSlider extends Model
{
    use HasFactory, Loggable;

    public function frontentCustomContentSection()
    {
        return $this->belongsTo(FrontendCustomContentSection::class, 'frontend_custom_content_type_id');
    }
}

<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontendCustomContentSection extends Model
{
    use HasFactory, Loggable;

    public function frontendCustomContent()
    {
        return $this->hasMany(FrontendCustomContent::class, 'frontend_custom_content_type_id');
    }
    public function frontendCustomContentLimitedData()
    {
        return $this->hasMany(FrontendCustomContent::class, 'frontend_custom_content_type_id');
    }
    public function frontendCustomContentSectionSlider()
    {
        return $this->hasMany(FrontendCustomContentSectionSlider::class, 'frontend_custom_content_type_id');
    }
}

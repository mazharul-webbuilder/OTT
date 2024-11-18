<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrontendCustomContent extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    public function ottContent(): BelongsTo
    {
        return $this->belongsTo(OttContent::class, 'content_id')->where('status', 'Published')->orderBy('order', 'asc');
    }

    public function frontentCustomContentSection(): BelongsTo
    {
        return $this->belongsTo(FrontendCustomContentSection::class, 'frontend_custom_content_type_id');
    }
}

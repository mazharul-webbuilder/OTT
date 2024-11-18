<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubSubCategory extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    public function rootCategory(): BelongsTo
    {
        return $this->belongsTo(RootCategory::class);
    }
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function ottContents(): HasMany
    {
        return $this->hasMany(OttContent::class)->where('status', 'Published')->orderBy('order', 'asc');
        // return $this->belongsTo(OttContent::class, 'content_id')->where('status', 'Published')->orderBy('order', 'asc');
    }
}

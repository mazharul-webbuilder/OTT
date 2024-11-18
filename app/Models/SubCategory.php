<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCategory extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    public function rootCategory(): BelongsTo
    {
        return $this->belongsTo(RootCategory::class);
    }
    public function subSubCategories(): HasMany
    {
        return $this->hasMany(SubSubCategory::class);
    }
    public function ottContents(): HasMany
    {
        return $this->hasMany(OttContent::class)->where('status', 'Published')->orderBy('order', 'asc');
        // return $this->belongsTo(OttContent::class, 'content_id')->where('status', 'Published')->orderBy('order', 'asc');
    }
}

<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RootCategory extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];


    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class)->orderBy('order','asc');
    }
    public function subSubCategories(): HasMany
    {
        return $this->hasMany(SubSubCategory::class)->orderBy('order','asc');
    }

    public function ottContents(): HasMany
    {
        return $this->hasMany(OttContent::class)->where('status','Published')->orderBy('order','asc');
    }

    public function ottSeries(): HasMany
    {
        return $this->hasMany(OttSeries::class);
    }

    public function categorySliders(): HasMany
    {
        return $this->hasMany(OttSlider::class)->where('status','Published')->where('is_home',0);
    }

    public function selectCategoryContentents(): HasMany
    {
        return $this->hasMany(SelectedCategoryContent::class,'root_category_id');
    }
    /*==================================Cache Manage for this Model==========================*/
    /**
     * Generate cache keys related to this model.
     *
     * @return array
     */
    public function getCacheKeys(): array
    {
        return [
            "ROOT_CATEGORIES",
            "ROOT_CATEGORY_{$this->slug}",
            "ROOT_CATEGORY_CONTENTS_{$this->slug}",
        ];
    }
}

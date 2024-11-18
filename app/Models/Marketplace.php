<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Marketplace extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ['name', 'slug'];

    protected $guarded = ['status'];

    public function ottContents(): BelongsToMany
    {
        return $this->belongsToMany(OttContent::class, 'ott_content_marketplace', 'marketplace_id', 'ott_content_id');
    }
}

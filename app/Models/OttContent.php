<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\OttContentEnum;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\Providers\JWT;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OttContent extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    /**
     * Local Scope
     */
    public function scopeGetContentByUUID($query, $uuid)
    {
        return $query->where('uuid', $uuid)->first();
    }

    public function rootCategory(): BelongsTo
    {
        return $this->belongsTo(RootCategory::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class)->withDefault();
    }

    public function subSubCategory(): BelongsTo
    {
        return $this->belongsTo(SubSubCategory::class)->withDefault();
    }

    public function ottContentMeta(): HasMany
    {
        return $this->hasMany(OttContentMeta::class, 'content_id');
    }

    public function ottSeries(): BelongsTo
    {
        return $this->belongsTo(OttSeries::class, 'series_id')->withDefault();
    }

    public function contentSource(): HasMany
    {
        return $this->hasMany(ContentSource::class, 'ott_content_id');
    }

    public function contentTrailer(): HasMany
    {
        return $this->hasMany(OttContentTrailer::class, 'content_id');
    }
    public function wishList(): HasOne
    {
        return $this->hasOne(WishList::class, 'content_id');
    }

    // protected $casts = [
    //     'access' => OttContentEnum::class,
    // ];
    public function reviews(): HasMany
    {
        return $this->hasMany(OttContentReview::class, 'content_id')->with('user');
    }

    //Get CastAndCrews
    public function castAndCrew(): BelongsToMany
    {
        return $this->belongsToMany(CastAndCrew::class, 'ott_content_cast_and_crew')->withPivot('role');
    }

    public function contentOwner(): BelongsTo
    {
        return $this->belongsTo(ContentOwners::class)->withDefault();
    }
    /**
     * Content has many marketplaces
     */
    public function marketplaces(): BelongsToMany
    {
        return $this->belongsToMany(Marketplace::class, 'ott_content_marketplace', 'ott_content_id', 'marketplace_id')->where('status', '=', '1');
    }
    public function ottDownloadableContent(): HasOne
    {
        return $this->hasOne(OttDownloadableContent::class);
    }
    public function ottSeason(): BelongsTo
    {
        return $this->belongsTo(Season::class, 'season_id','id')->withDefault();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'creator_id');
    }
    public function modifier(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'modifier_id');
    }
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'publisher_id');
    }
    public function userActivitySync(): HasMany
    {
        return $this->hasMany(UserActivitySync::class, 'content_id');
    }
    public function tVodSubscriptions() :HasMany
    {
        return $this->hasMany(TVodSubscription::class, 'ott_content_id');
    }

    /*===========================================================================
    - Model Events
    -
    ============================================================================*/
    public static function boot(): void
    {
        parent::boot();

        // Event fired before a new OttContent is created
        static::creating(function ($ottContent) {
            if (Auth::guard('admin')->check()) {
                $ottContent->creator_id = Auth::guard('admin')->id();
            }
        });

        static::saving(function ($ottContent){
            if (Auth::guard('admin')->check()){
                // Modifier
                if ($ottContent->exists){
                    $ottContent->modifier_id = Auth::guard('admin')->id();
                }
                // Publisher
                if ($ottContent->status === ContentStatus::PUBLISHED->value)
                {
                    $ottContent->publisher_id = Auth::guard('admin')->id();
                }
            }
        });

    }


}

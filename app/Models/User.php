<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{

    use   Notifiable, HasFactory, Loggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'phone',
    //     'otp',
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
        'password',
        'google_id',
        'facebook_id',
        'otp',
        'otp_created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Phone accessor
    */
    public function phone(): Attribute
    {
        return new Attribute(get: function ($value){
            if (Str::contains($value, 'DEMO')){
                return null;
            }
            return $value;
        });
    }
    /**
     * User Age Accessor
    */
    public function age(): Attribute
    {
        return new Attribute(get: function (){
            return Carbon::parse($this->dob)->age;
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userMeta(): HasMany
    {
        return $this->hasMany(UserMeta::class);
    }

    public function userDevice(): HasMany
    {
        return $this->hasMany(UserDevice::class, 'user_id');
    }
    public function subscriptionPlans(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'user_id')
            ->with('payment')
            ->where('is_active', 1)
            ->with('subscriptionPlan');
    }
    public function allSubscriptionPlans(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'user_id')
            ->with('subscriptionPlan')
            ->orderBy('id', 'desc');
    }

    public function activeSubscription(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'user_id')
            ->with('subscriptionPlan')
            ->where('is_active', 1)
            ->whereDate('end_date', '>=', Carbon::today());
    }

    public function wishLists(): HasMany
    {
        return $this->hasMany(WishList::class, 'user_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function userTVodSubscription(): HasMany
    {
        return $this->hasMany(UserTVodSubscription::class, 'user_id')
            ->with('payment')
            ->where('is_active', 1)
            ->with('subscriptionPlan');
    }
}

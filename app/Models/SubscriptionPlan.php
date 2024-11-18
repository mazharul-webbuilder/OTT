<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    public function coupon_code_subscription_plans(): HasMany
    {
        return $this->hasMany(CouponCodeSubscriptionPlan::class);
    }

    public function subscription_source_formats(): HasMany
    {
        return $this->hasMany(SubscriptionSourceFormat::class);
    }
    public function userSubscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }
}

<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class CouponCode extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    public function coupon_code_subscription_plans(): HasMany
    {
        return $this->hasMany(CouponCodeSubscriptionPlan::class, 'coupon_code_id');
    }

    public function subscription_plans(): HasManyThrough
    {
        return $this->hasManyThrough(
            SubscriptionPlan::class,
            CouponCodeSubscriptionPlan::class,
            'coupon_code_id', // Foreign key on CouponCodeSubscriptionPlan table
            'id', // Foreign key on SubscriptionPlan table
            'id', // Local key on CouponCode table
            'subscription_plan_id' // Local key on CouponCodeSubscriptionPlan table
        );
    }
}

<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponCodeSubscriptionPlan extends Model
{
    use HasFactory, Loggable;

    protected $guarded = [];

    public function subscription_plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
    public function coupon_code(): BelongsTo
    {
        return $this->belongsTo(CouponCode::class, 'coupon_code_id');
    }
}

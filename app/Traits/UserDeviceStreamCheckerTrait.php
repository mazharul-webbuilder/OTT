<?php

namespace App\Traits;

use App\Enums\OttContentEnum;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait UserDeviceStreamCheckerTrait
{
    public function isDeviceLimitExceeded()
    {
      
        $user =  Auth::guard('api')->user();
        $user_subscription_plans = $user->subscriptionPlans->where('end_date', '>=', Carbon::now())->where('is_active', 1);

        $total_allowed_device = 0;
        foreach ($user_subscription_plans as $user_plan) {
            $total_allowed_device = $total_allowed_device + $user_plan->subscriptionPlan->number_of_allowed_device;
        }

        $userTotalDevice = $user->userDevice->where('device_lock', false)->count();
        if ($userTotalDevice <= $total_allowed_device) {
            return false;
        } else {
            return true;
        }
    }

    
}

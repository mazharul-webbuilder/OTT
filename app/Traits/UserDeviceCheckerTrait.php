<?php

namespace App\Traits;

use App\Enums\OttContentEnum;
use App\Models\SubscriptionPlan;
use App\Models\UserActivitySync;
use App\Models\UserDevice;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

trait UserDeviceCheckerTrait
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
    public function isDeviceStreamLimitExceeded(Request $request, $content_id)
    {
        $user_id = Auth::guard('api')->user()->id;
        // dd($user_id);
        $valid_device = UserDevice::where('user_id', $user_id)->where('device_unique_id', $request->header('deviceuniqueid'))->pluck('id')->first();
        $data = [
            'content_id' => $content_id,
            'device_id' => $valid_device,
            'user_id' => $user_id,
            'updated_at' => Carbon::now()
        ];
        UserActivitySync::updateOrCreate(['device_id' => $valid_device], $data);
        // dd(Carbon::now()->format('Y-m-d H:i:s'), Carbon::now()->subSeconds(10)->format('Y-m-d H:i:s'));
        $active_device_count = UserActivitySync::where('user_id', $user_id)->where('updated_at', '>', Carbon::now()->subSeconds(5)->format('Y-m-d H:i:s'))->count();
        // dd($active_device_count);
        $user_subscription_plan = UserSubscription::where('user_id', $user_id)
            ->where('is_active', 1)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();
        $stream_limit = SubscriptionPlan::where('id', $user_subscription_plan->subscription_plan_id)->pluck('number_of_allowed_stream')->first();
        // dd($stream_limit);
        if ($active_device_count <= $stream_limit) {
            return false;
        } else {
            return true;
        }
    }
}

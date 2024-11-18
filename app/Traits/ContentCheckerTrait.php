<?php

namespace App\Traits;

use App\Enums\OttContentEnum;
use App\Models\UserSubscription;
use App\Models\UserTVodSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait ContentCheckerTrait
{
    public function ContentAccessCheck($content_data)
    {

        if ($content_data == config("constants.OTTCONTENTPREMIUM")) {
            if (Auth::guard('api')->user()) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
    public function HasSubscriptionPlan()
    {
        $user_id = Auth::guard('api')->user()->id;
        $user_subscription_plan = UserSubscription::where('user_id', $user_id)
            ->where('is_active', 1)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();
        // dd($user_subscription_plan);
        if (!empty($user_subscription_plan)) {
            return true;
        } else {
            return false;
        }
    }

    public function HasTVodSubscription($contentId)
    {
        if (empty(Auth::guard('api')->user())) {
            return false;
        }
        $user_id = Auth::guard('api')->user()->id;
        $user_tvod_subscription = UserTVodSubscription::where('user_id', $user_id)
            ->where('content_id', $contentId)
            ->where('is_active', 1)
            ->where('start_time', '<=', Carbon::now())
            ->where('end_time', '>=', Carbon::now())
            ->orderBy('id','desc')
            ->first();
        // dd($user_tvod_subscription);
        if (!empty($user_tvod_subscription)) {
            return true;
        } else {
            return false;
        }
    }
}

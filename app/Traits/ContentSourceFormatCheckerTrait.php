<?php

namespace App\Traits;

use App\Enums\OttContentEnum;
use App\Models\OttContent;
use App\Models\SubscriptionSourceFormat;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait ContentSourceFormatCheckerTrait
{

    public function AvailavleSources($ott_content_id)
    {
        $ott_content = OttContent::where('id', $ott_content_id)->first();
        if ($ott_content->access == config("constants.OTTCONTENTPREMIUM")) {
            $user = Auth::guard('api')->user();
            // dd($user->subscriptionPlans);
            $user_subscription_plans = $user->subscriptionPlans->where('end_date', '>=', Carbon::now())->where('is_active',true);

            $planId = [];
            foreach ($user_subscription_plans as $plans) {
                $planId[] = $plans->subscription_plan_id;
            }
            // dd($planId);
            $subscription_plans = SubscriptionSourceFormat::whereIn('subscription_plan_id', $planId)->get();
            // dd($subscription_plans);
            $source_formats = [];
            foreach ($subscription_plans as $plan) {
                $source_formats[] = $plan->source_format;
            }
            // dd($source_formats);
            return  $source_formats;
            // return   $user_subscription = $user->subscriptions;
        } else {
            
        }
    }
}

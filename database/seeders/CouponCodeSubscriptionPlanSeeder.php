<?php

namespace Database\Seeders;

use App\Models\CouponCodeSubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CouponCodeSubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CouponCodeSubscriptionPlan::insert(
            [
                [
                    'coupon_code_id' => 1,
                    'subscription_plan_id' => 1,
                    'is_active' => true,
                    'maximum_apply' => 4,
                    'maximum_single_user_apply' => 1,
                    'created_at' => Carbon::now(),
                ],
                [
                    'coupon_code_id' => 1,
                    'subscription_plan_id' => 2,
                    'is_active' => true,
                    'maximum_apply' => null,
                    'maximum_single_user_apply' => 1,
                    'created_at' => Carbon::now(),
                ],
                [
                    'coupon_code_id' => 3,
                    'subscription_plan_id' => 3,
                    'is_active' => true,
                    'maximum_apply' => 1,
                    'maximum_single_user_apply' => 1,
                    'created_at' => Carbon::now(),
                ],
                // [
                //     'coupon_code_id' => 1,
                //     'subscription_plan_id' => 4,
                //     'is_active' => true,
                //     'maximum_apply' => 1,
                //     'maximum_single_user_apply' => 1,
                //     'created_at' => Carbon::now(),
                // ],
            ]
        );
    }
}

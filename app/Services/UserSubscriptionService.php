<?php

namespace App\Services;

use App\Models\CouponCode;
use App\Models\Payment;
use App\Models\SubscriptionPlan;
use App\Models\TVodSubscription;
use App\Models\UserSubscription;
use App\Models\UserTVodSubscription;
use App\Traits\PaymentInitiateTrait;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionService
{
    use  ResponseTrait, PaymentInitiateTrait;
    public function createUserSubscription($request, $planId)
    {
        $temp = false;
        if ($request->filled('coupon_code')) {
            $coupon_code = CouponCode::where('code', $request->coupon_code)->with('coupon_code_subscription_plans')->first();
            if (!empty($coupon_code)) {
                $coupon_code_subscription_plan = $coupon_code->coupon_code_subscription_plans->where('subscription_plan_id', $planId)->first();
                if (!empty($coupon_code_subscription_plan)) {
                    $subscription_plan = $coupon_code_subscription_plan->subscription_plan;
                    if ($coupon_code->discount_type == 'flat') {
                        $price = $subscription_plan->regular_price - $coupon_code->discount_value;
                    } else {
                        $price = ceil(($subscription_plan->regular_price - (($subscription_plan->regular_price * $coupon_code->discount_value) / 100)));
                    }
                    $count_single_user_apply = UserSubscription::where('user_id', Auth::guard('api')->user()->id)->where('subscription_plan_id', $planId)->count();
                    if (!empty($coupon_code_subscription_plan->maximum_apply) && $coupon_code_subscription_plan->maximum_apply > 0) {

                        if (!empty($coupon_code_subscription_plan->maximum_single_user_apply) && $count_single_user_apply < $coupon_code_subscription_plan->maximum_single_user_apply) {
                            $data = [
                                'subscription_plan_id' => $planId,
                                'start_date' => Carbon::now()->toDateTimeString(),
                                'end_date' => date('Y-m-d H:i:s', strtotime('+' . $subscription_plan->duration . 'days')),
                                'price' => $price,
                                'user_id' => Auth::guard('api')->user()->id,
                                'is_auto_renewal' => $subscription_plan->is_renewable,
                                'is_discounted' => $subscription_plan->is_discounted
                            ];
                            $temp = true;
                        } else {
                            if (!empty($coupon_code_subscription_plan->maximum_single_user_apply)) {
                                return $this->errorResponse('Already availed this coupon', null);
                            } else {

                                $data = [
                                    'subscription_plan_id' => $planId,
                                    'start_date' => Carbon::now()->toDateTimeString(),
                                    'end_date' => date('Y-m-d H:i:s', strtotime('+' . $subscription_plan->duration . 'days')),
                                    'price' => $price,
                                    'user_id' => Auth::guard('api')->user()->id,
                                    'is_auto_renewal' => $subscription_plan->is_renewable,
                                    'is_discounted' => $subscription_plan->is_discounted
                                ];
                            }
                        }
                    } else {
                        if ($coupon_code_subscription_plan->maximum_apply == 0 && !is_null($coupon_code_subscription_plan->maximum_apply)) {
                            return $this->errorResponse('Coupon Code maximum apply limit is exceeded!', null);
                        } else {
                            if (!empty($coupon_code_subscription_plan->maximum_single_user_apply) && $count_single_user_apply < $coupon_code_subscription_plan->maximum_single_user_apply) {
                                $data = [
                                    'subscription_plan_id' => $planId,
                                    'start_date' => Carbon::now()->toDateTimeString(),
                                    'end_date' => date('Y-m-d H:i:s', strtotime('+' . $subscription_plan->duration . 'days')),
                                    'price' => $price,
                                    'user_id' => Auth::guard('api')->user()->id,
                                    'is_auto_renewal' => $subscription_plan->is_renewable,
                                    'is_discounted' => $subscription_plan->is_discounted
                                ];
                            } else {
                                if (!empty($coupon_code_subscription_plan->maximum_single_user_apply)) {
                                    return response()->json('Already availed this coupon');
                                } else {
                                    $data = [
                                        'subscription_plan_id' => $planId,
                                        'start_date' => Carbon::now()->toDateTimeString(),
                                        'end_date' => date('Y-m-d H:i:s', strtotime('+' . $subscription_plan->duration . 'days')),
                                        'price' => $price,
                                        'user_id' => Auth::guard('api')->user()->id,
                                        'is_auto_renewal' => $subscription_plan->is_renewable,
                                        'is_discounted' => $subscription_plan->is_discounted
                                    ];
                                }
                            }

                            $data = [
                                'subscription_plan_id' => $planId,
                                'start_date' => Carbon::now()->toDateTimeString(),
                                'end_date' => date('Y-m-d H:i:s', strtotime('+' . $subscription_plan->duration . 'days')),
                                'price' => $price,
                                'user_id' => Auth::guard('api')->user()->id,
                                'is_auto_renewal' => $subscription_plan->is_renewable,
                                'is_discounted' => $subscription_plan->is_discounted
                            ];
                        }
                    }
                } else {
                    return $this->validationError('Subscription plan for this Coupon is not valid!!', null);
                }
            } else {
                return $this->validationError('Coupon Code is not valid!!', null);
            }
        } else {
            $subscription_plan = SubscriptionPlan::where('id', $planId)->first();


            if (!empty($subscription_plan) && $subscription_plan->status == "Active") {
                if ($subscription_plan->is_discounted) {
                    $data = [
                        'subscription_plan_id' => $planId,
                        'start_date' => Carbon::now()->toDateTimeString(),
                        'end_date' => date('Y-m-d H:i:s', strtotime('+' . $subscription_plan->duration . 'days')),
                        'price' => $subscription_plan->discount_price,
                        'user_id' => Auth::guard('api')->user()->id,
                        'is_auto_renewal' => $subscription_plan->is_renewable,
                        'is_discounted' => $subscription_plan->is_discounted
                    ];
                } else {
                    $data = [
                        'subscription_plan_id' => $planId,
                        'start_date' => Carbon::now()->toDateTimeString(),
                        'end_date' => date('Y-m-d H:i:s', strtotime('+' . $subscription_plan->duration . 'days')),
                        'price' => $subscription_plan->regular_price,
                        'user_id' => Auth::guard('api')->user()->id,
                        'is_auto_renewal' => $subscription_plan->is_renewable,
                        'is_active' => false
                        // $data['is_active'] = false;
                    ];
                }
            } else {
                return $this->errorResponse('Subscription plan is not valid', null);
            }
        }

        $user_last_purchased_subscription_package = UserSubscription::where('user_id', Auth::guard('api')->user()->id)->where('is_active', true)->latest()->first();
        if (isset($user_last_purchased_subscription_package)) {
            $data['start_date'] = Carbon::now()->toDateTimeString();
            $data['end_date'] = date('Y-m-d H:i:s', strtotime('+' . $subscription_plan->duration . 'days', strtotime($data['start_date'])));
            $data['is_active'] = false;
        }

        try {
            if ($temp) {
                $coupon_code_subscription_plan->decrement('maximum_apply', 1);
            }
            $userSubscriptionPlan = UserSubscription::create($data);

            // dd($request->client_redirect_url);

            $paymentInitiate = $this->initiatePayment($userSubscriptionPlan, $request->payment_method,$request->client_redirect_url);
            return $this->successResponse('Payment Initiated Successfully', $paymentInitiate);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function createUserTvodSubscription($request, $t_vod_subscription_id)
    {
        $tvodSubscription = TVodSubscription::where('id', $t_vod_subscription_id)->first();

            if (!empty($tvodSubscription)) {

                    $data = [
                        't_vod_plan_id' => $tvodSubscription->id,
                        'content_id' => $tvodSubscription->ott_content_id,
                        'start_time' => Carbon::now()->toDateTimeString(),
                        'end_time' => Carbon::parse(Carbon::now()->toDateTimeString())->addHours($tvodSubscription->ticket_duration_hour),
                        'user_id' => Auth::guard('api')->user()->id,
                        'is_active' => false
                    ];
            } else {
                return response()->json('Subscription plan is not valid!!');
            }

            try {
                $userTvodSubscription = UserTVodSubscription::create($data);
                $tvodSubscription->user_tvod_subscription = $userTvodSubscription->id;
                $paymentInitiate = $this->initiatePayment($tvodSubscription, $request->payment_method, $request->client_redirect_url, true);
                return $this->successResponse('Payment Initiated Successfully', $paymentInitiate);
            } catch (Exception $e) {
                return $this->errorResponse($e->getMessage(), null, null);
            }
    }
}

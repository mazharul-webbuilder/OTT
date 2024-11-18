<?php

namespace App\Traits;

use App\Enums\OttContentEnum;
use App\Models\Payment;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait PaymentInitiateTrait
{

   public function initiatePayment($userSubscriptionPlan, $paymentMethod, $client_redirect_url, $is_tvod_subscription_request = false)
   {
      try {
         $paymentData = [
            'user_id' => Auth::guard('api')->user()->id,
            'currency' => 'BDT',
            'charge_id' => $paymentMethod . '_' . Str::uuid(),
            'payment_channel' => $paymentMethod,
            'amount' => $userSubscriptionPlan->price,
            'user_subscription_plan_id' => !$is_tvod_subscription_request ? $userSubscriptionPlan->subscription_plan_id : 0,
            'user_subscription_id' => !$is_tvod_subscription_request ? $userSubscriptionPlan->id : 0,
            'tvod_subscription_plan_id' => $is_tvod_subscription_request ? $userSubscriptionPlan->id : 0,
            'user_tvod_subscription_id' => $is_tvod_subscription_request ? $userSubscriptionPlan->user_tvod_subscription : 0,
            'status' => "initiated",
            'validation_token' => bin2hex(openssl_random_pseudo_bytes(16)),
         ];

         Log::error('error payment', $paymentData);

         $paymentDataInitiated = Payment::create($paymentData);

         Log::error("Payment created", ['data payment initiated' => $paymentDataInitiated]);
         if (App::environment(['local'])) {
            $client_redirect_url = $client_redirect_url;
            Log::error("Client rediriect url", ['client redirect url' => $client_redirect_url]);
         } else {
            $client_redirect_url = env('live_url', null);
            Log::error("Client rediriect url live", ['client_redirect_url' => $client_redirect_url]);
         }
 
         $response = Http::withHeaders([
            'Authorization' => config('constants.PAYMENT_GATEWAY_TOKEN')
         ])->post(config("constants.PAYMENT_GATEWAY_URL") . 'api/v1/payment', [
            'total_amount' => $paymentDataInitiated->amount,
            'value_a' => $paymentDataInitiated->charge_id,
            'payment_method' => $paymentMethod,
            'currency' => $paymentDataInitiated->currency,
            'cus_name' => Auth::guard('api')->user()->name ?? Str::random(8),
            'email' => Auth::guard('api')->user()->email ?? Str::random(8) . "@gmail.com",
            'cus_add1' => "default address",
            'cus_add2' =>  "default address",
            'cus_city' => "city",
            'cus_state' => "state",
            'cus_postcode' => "1011",
            'cus_country' => "country",
            'cus_phone' => Auth::guard('api')->user()->phone ?? Str::padLeft(value: 'DEMO', length: 4) . strtotime(Carbon::now()) + rand(min: 1000, max: 9999),
            'cus_fax' => "cus_fax",
            'payment_id' => $paymentDataInitiated->id,
            'charge_id' => $paymentDataInitiated->charge_id,
            'validation_token' => $paymentDataInitiated->validation_token,
            'value_c' => $client_redirect_url,
         ]);
         // dd(json_decode($response));
         Log::error("decode_data for Payment api server", [
            'total_amount' => $paymentDataInitiated->amount,
            'value_a' => $paymentDataInitiated->charge_id,
            'payment_method' => $paymentMethod,
            'currency' => $paymentDataInitiated->currency,
            'cus_name' => Auth::guard('api')->user()->phone,
            'email' => Auth::guard('api')->user()->phone . "@gmail.com",
            'cus_add1' => "default address",
            'cus_add2' =>  "default address",
            'cus_city' => "city",
            'cus_state' => "state",
            'cus_postcode' => "1011",
            'cus_country' => "country",
            'cus_phone' => Auth::guard('api')->user()->phone,
            'cus_fax' => "cus_fax",
            'payment_id' => $paymentDataInitiated->id,
            'charge_id' => $paymentDataInitiated->charge_id,
            'validation_token' => $paymentDataInitiated->validation_token,
            'value_c' => $client_redirect_url,
         ]);

         $decode_data = json_decode($response);


         // dd($decode_data);
         $decode_data->payment_id = $paymentDataInitiated->id;
         $decode_data->charge_id = $paymentDataInitiated->charge_id;
         return $decode_data;
      } catch (Exception $e) {
         return false;
      }
   }
}

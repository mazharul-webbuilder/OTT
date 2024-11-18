<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\OttContentEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\WatchHistoryTrackRequest;
use App\Http\Resources\OttContentResource;
use App\Http\Resources\OttContentReviewResource;
use App\Http\Resources\SubscriptionPlanResource;
use App\Http\Resources\UserDeviceResource;
use App\Http\Resources\WishListResource;
use App\Models\OttContent;
use App\Models\OttContentReview;
use App\Models\OttNotification;
use App\Models\OttNotificationDeviceToken;
use App\Models\PaymentMethods;
use App\Models\Payment;
use App\Models\PhotoGalary;
use App\Models\SubscriptionPlan;
use App\Models\SubSubCategory;
use App\Models\User;
use App\Models\UserActivitySync;
use App\Models\UserDevice;
use App\Models\UserSubscription;
use App\Models\UserTVodSubscription;
use App\Models\WatchHistory;
use App\Models\WishList;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ContentCheckerTrait;
use App\Traits\UserDeviceCheckerTrait;
use App\Traits\VerifyDeviceTrait;
use Illuminate\Validation\Rule;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class FrontendController extends Controller
{
    use ContentCheckerTrait, UserDeviceCheckerTrait, VerifyDeviceTrait;

    public function singleSubSubCategory($device, $slug)
    {
        try {
            $data = SubSubCategory::where('slug', $slug)->with('ottContents')->first();
            if ($data) {
                $query = $data->ottContents()->paginate(20);
                $data =  OttContentResource::collection($query);
                return $this->successResponse('Data fetched Successfully', $data);
            } else {
                return $this->errorResponse('content not found', null, null);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function singleOttContentReview($device, $uuid)
    {
        $ottContent = OttContent::where('uuid', $uuid)->first();
        $data = [];
        if ($ottContent != null) {
            $reviews = OttContentReviewResource::collection($ottContent->reviews);
            $data['ott_content_title'] = $ottContent->title;
            $data['review_calculation'] = $this->average_review($ottContent->id);
            $data['all_reviews'] = $reviews;
        } else {
            $reviews = null;
        }

        return $this->successResponse('Fetched Successfully', $data);
    }
    private function average_review($content_id)
    {
        $ott_content_reviews = OttContentReview::where('content_id', $content_id)->get();

        if (count($ott_content_reviews) == 0) {
            return 0;
        }
        if ($ott_content_reviews != null) {
            $ott_content_count_review = OttContentReview::where('content_id', $content_id)->sum('review_star');
            $average_review =  ceil($ott_content_count_review / count($ott_content_reviews));
        } else {
            $average_review = 0;
        }

        $totals = DB::table('ott_content_reviews')
            ->where('content_id', $content_id)
            ->selectRaw('count(*) as total')
            ->selectRaw("count(case when review_star = '1' then 1 end) as OneStar")
            ->selectRaw("count(case when review_star = '2' then 1 end) as TwoStar")
            ->selectRaw("count(case when review_star = '3' then 1 end) as ThreeStar")
            ->selectRaw("count(case when review_star = '4' then 1 end) as FourStar")
            ->selectRaw("count(case when review_star = '5' then 1 end) as FiveStar")
            ->get();

        if ($totals[0]->total != 0) {
            $percentage = [
                'one_star' => ceil(($totals[0]->OneStar / $totals[0]->total) * 100),
                'two_star' => ceil(($totals[0]->TwoStar / $totals[0]->total) * 100),
                'three_star' => ceil(($totals[0]->ThreeStar / $totals[0]->total) * 100),
                'four_star' => ceil(($totals[0]->FourStar / $totals[0]->total) * 100),
                'five_star' => ceil(($totals[0]->FiveStar / $totals[0]->total) * 100),
            ];
        } else {
            $percentage = null;
        }

        $total_review_info = [
            'average_review_count' => $average_review,
            'review_percentage' => $percentage
        ];
        return $total_review_info;
    }



    public function availableSubscriptionPlansForMe($device)
    {
        try {
            $user_subcriptions = UserSubscription::where('user_id', Auth::guard('api')->user()->id)->where('is_active', 1)->first();
            if ($user_subcriptions->subscription_plan_id != null) {
                $query = SubscriptionPlan::where('status', 'Active')->where('id', '!=', $user_subcriptions->subscription_plan_id)->get();
                $data = SubscriptionPlanResource::collection($query);
                return $this->successResponse('Data fetched Successfully', $data);
            } else {
                $query = SubscriptionPlan::where('status', 'Active')->get();
                $data = SubscriptionPlanResource::collection($query);
                return $this->successResponse('Data fetched Successfully', $data);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }


    public function userDeviceRemove($device, Request $request)
    {
        try {
            $device = UserDevice::where('device_unique_id', $request->user_unique_device_id)->delete();

            return $this->successResponse($device ? 'You have been successfully removed.' : 'Incorrect device unique id', (bool) $device);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    //Get Devices
    public function devices($device)
    {
        try {
            $devices = Auth::guard('api')->user()->userDevice;
            $format_devices = UserDeviceResource::collection($devices);
            return $this->successResponse('User Devices Fetch Successfully', $format_devices);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function subscriptionDeviceInfo($device)
    {
        try {
            $user =  Auth::guard('api')->user();
            $user_subscription_plans = $user->subscriptionPlans->where('end_date', '>=', Carbon::now())->where('is_active', 1);
            $total_allowed_device = 0;
            $number_of_allowed_stream = 0;
            foreach ($user_subscription_plans as $user_plan) {
                $total_allowed_device = $total_allowed_device + $user_plan->subscriptionPlan->number_of_allowed_device;
                $number_of_allowed_stream = $total_allowed_device + $user_plan->subscriptionPlan->number_of_allowed_stream;
            }

            $data = [
                'number_of_allowed_stream' => $number_of_allowed_stream,
                'total_allowed_device' => $total_allowed_device
            ];

            return $this->successResponse('Subscription Device Info Fetch Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
    public function subscriptionInfo($device)
    {
        try {
            $user =  Auth::guard('api')->user();
            $user_subscription_plans = $user->subscriptionPlans->where('end_date', '>=', Carbon::now())->where('is_active', 1);
            // dd($user_subscription_plans);
            if ($user_subscription_plans->count() > 0) {
                // $data['running_subscription_package'] = $user_subscription_plans[0];
                foreach ($user_subscription_plans as $user_plan) {
                    $data['all_active_packages_info'] = $user_plan;
                }
            } else {
                $data = [];
            }
            return $this->successResponse('Subscription Device Info Fetch Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function cancelUserSubscriptionPlan($device, $userSubscriptionPlanId)
    {
        // return $request->all();
    }

    /**
     * Track user content watch history
    */
    public function trackUserWatchHistory(string $device, WatchHistoryTrackRequest $request): JsonResponse
    {
        try {
            $watchHistories = [];

            foreach ($request->input('history') as $item) {
                $watchHistories[] = [
                    'user_id' => Auth::guard('api')->id(),
                    'ott_content_id' => $item['ott_content_id'],
                    'ott_content_type' => $item['ott_content_type'],
                    'watched_at' => isset($item['watched_at']) ? Carbon::parse($item['watched_at']): \Illuminate\Support\Carbon::now(),
                    'watched_duration' => $item['watched_duration'] ?? 0,
                ];
            }
            WatchHistory::upsert($watchHistories,
                ['user_id', 'ott_content_id', 'ott_content_type'], // Columns that uniquely identify records
                ['watched_at', 'watched_duration']); // Columns to update if the record exists

            return $this->successResponse('History tracked successfully', null);

        } catch (QueryException|Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
    public function updatePaymentTable(Request $request)
    {

        // return $this->successResponse('Payment Updated Successfully', $request->all());



        // $data = [];
        // $data = $request->all();
        // dd($data);

        try {
            $data = Payment::where('charge_id', $request->charge_id)->where('validation_token', $request->validation_token)->first();


            if ($data != null) {


                if ($request->status == 'completed') {
                    if ($data->user_tvod_subscription_id != 0 || $data->user_tvod_subscription_id != null) {
                        UserTVodSubscription::where('id', $data->user_tvod_subscription_id)->update(['is_active' => true]);
                    } else {
                        UserSubscription::where('id', $data->user_subscription_id)->update(['is_active' => true]);
                    }

                    $data->update(['status' => $request->status]);
                    return $this->successResponse('Payment Updated Successfully', $data->status);
                }
                return $this->successResponse('Something went wrong...', null);
            }
            return $this->successResponse('Payment Not found with this id', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
    // public function updatePaymentTableWithParameter($chargeId, $validationToken, $status)
    // {
    //     return 'aman';
    //     try {
    //         $data = Payment::where('charge_id', $chargeId)->where('validation_token', $validationToken)->first();
    //         dd($data);
    //         if ($data != null) {
    //             if ($status == 1) {
    //                 UserSubscription::where('id', $data->user_subscription_id)->update(['is_active' => true]);
    //                 $data->update(['status' => 'completed']);
    //             }
    //             return $this->successResponse('Payment Updated Successfully', null);
    //         }
    //         return $this->successResponse('Payment Not found with this id', null);
    //     } catch (Exception $e) {
    //         return $this->errorResponse($e->getMessage(), null, null);
    //     }
    // }



    //Elastic Search
    public function contentSearch($device, Request $request)
    {

        try {
            $query = $request->input('q');
            $sort_by = $request['sort-by'];

            if ($query) {

                $page = $request->input('page', 1);
                $paginate = 30;

                $search_fields = [
                    'multi_match' =>
                    [
                        'query' => $query,
                        'fields' => ["name^10", "description"]
                    ]
                ];

                switch ($sort_by) {

                    case ('date_desc'):
                        $sort_array = ['created_at' => 'desc'];
                        $contents = OttContent::searchByQuery($search_fields, null, null, $paginate, ($page - 1) * $paginate, $sort_array);
                        break;

                    case ('date_asc'):
                        $sort_array = ['created_at' => 'asc'];
                        $contents = OttContent::searchByQuery($search_fields, null, null, $paginate, ($page - 1) * $paginate, $sort_array);
                        break;

                    case ('title_asc'):
                        $sort_array = ['title' => 'asc'];
                        $contents = OttContent::searchByQuery($search_fields, null, null, $paginate, ($page - 1) * $paginate, $sort_array);
                        break;
                    default:
                        $contents = OttContent::searchByQuery($search_fields, null, null, $paginate, ($page - 1) * $paginate);
                }
            }
            $total = $contents->totalHits()['value'];
            $contents = new \Illuminate\Pagination\LengthAwarePaginator($contents, $total, $paginate, $page, [
                'path'  => Paginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]);
            return $this->successResponse('Data Fetch Successfully', $contents);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function addFCMToken(Request $request)
    {
        $data = [
            'user_id' => Auth::guard('api')->user()->id,
            'fcm_token' => $request->token,
        ];

        try {
            OttNotificationDeviceToken::create($data);
            return $this->successResponse('Added Successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
    public function getNotifications()
    {
        try {
            $data =    OttNotification::where('user_id', Auth::guard('api')->user()->id)
                // ->select('id','user_id')
                // ->groupBy('user_id')
                // ->distinct()
                ->get();
            return $this->successResponse('Successfully Fetched', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
    public function removeAllNotification()
    {
        OttNotification::where('user_id', Auth::guard('api')->user()->id)->delete();
        return $this->successResponse('All notification cleared', null);
    }

}

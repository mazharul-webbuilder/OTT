<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionPlanResource;
use App\Models\OttContent;
use App\Models\SubscriptionPlan;
use App\Services\UserSubscriptionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class SubscriptionController extends Controller
{
    public function __construct(protected UserSubscriptionService $userSubscriptionService){}

    /**
     * All Subscription Plans with t_vod
     */
    public function availableSubscriptionPlans(string $device, Request $request): JsonResponse
    {
        try {
            $subscriptionPlans = SubscriptionPlan::where('status', 'Active')->get();

            // Manage content t_vod and content source
            if ($request->filled('content_uuid')){
                $content = OttContent::select('id', 'uuid', 'title', 'access','thumbnail_portrait', 'thumbnail_landscape', 'poster', 'is_tvod_available')
                    ->where('uuid', $request->input('content_uuid'))->with([
                        'contentSource' => function($q){
                            $q->where('source_type', 'trailer_path')->where('processing_status', 'encoded');
                        },
                        'tVodSubscriptions'])->firstOrFail();
            }

            $subscriptionResource = SubscriptionPlanResource::collection($subscriptionPlans);

            $responseData = [
                'subscription_plans' => $subscriptionResource,
                'ott_content' => $content ?? null
            ];

            return $this->successResponse('Data fetched Successfully', $responseData);

        } catch (ModelNotFoundException|QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get Subscription Detail
     */
    public function subscriptionDetail(string $device, int|string $subscriptionPlanId): JsonResponse
    {
        try {
            $subscriptionPlan = SubscriptionPlan::findOrFail($subscriptionPlanId);

            return $this->successResponse(message: 'Data fetched Successfully', data: $subscriptionPlan);

        }catch (ModelNotFoundException|Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * User Subscription also manages TVOD subscription
    */
    public function subscribeUser(string $device, Request $request,string $planId): JsonResponse
    {
        try {
            return $this->userSubscriptionService->createUserSubscription($request, $planId);

        }catch (Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }


    /**
     * User Subscription also manages TVOD subscription
    */
    public function subscribeUserWithTVOD(string $device, Request $request,string $planId): JsonResponse
    {
        try {
            return $this->userSubscriptionService->createUserTvodSubscription($request, $planId);

        }catch (Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CouponCode;
use App\Models\CouponCodeSubscriptionPlan;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class CouponCodeController extends Controller
{
    use ResponseTrait;

    // public function __construct()
    // {
    //     $this->middleware('permission:subscription-plan-list|subscription-plan-create|subscription-plan-edit|subscription-plan-delete', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:subscription-plan-create', ['only' => ['store']]);
    //     $this->middleware('permission:subscription-plan-edit', ['only' => ['show', 'update']]);
    //     $this->middleware('permission:subscription-plan-delete', ['only' => ['destroy']]);
    // }

    /**
     * All Available Coupon Codes
    */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 10);

        // Basic Query
        $query = CouponCode::query()->orderByDesc('id');

        // Manage Filter
        if ($request->filled('only_active')){
            $query->where('expiry_date', '>=', Carbon::today());
        }

        // Manage Search
        if ($request->filled('query_string')){
            $query = getSearchQuery($query, $request->input('query_string'), 'code');
        }

        // Search by date
        if($request->filled('start_date') && $request->filled('end_date')){
            $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
        }

        return $this->successResponse('Data fetched successfully', $query->paginate($perPage));
    }

    /**
     * Store new coupon code
    */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required|string|max:16|unique:coupon_codes',
                'start_date' => 'required|date',
                'expiry_date' => 'required|date|after:start_date',
                'discount_type' => 'required|string',
                'discount_value' => 'required|numeric',
                'maximum_amount_for_percent' => 'nullable|numeric',
                'for_new_user_only' => 'nullable|boolean',
            ]);
            $couponCode = CouponCode::create($validatedData);

            return $this->successResponse('Coupon code created successfully', $couponCode);
        } catch (ValidationException|Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get Coupon code info with available subscription plans,
     * if plan is empty this code is not applicable for user subscriptions.
    */
    public function show($id): JsonResponse
    {
        try {
            $couponCode = CouponCode::with('subscription_plans')->findOrFail($id);

            return $this->successResponse('Data fetched successfully', $couponCode);
        } catch (ModelNotFoundException $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Update Coupon codes
    */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'code' => 'required|string|max:16|unique:coupon_codes,code,'.$id,
                'start_date' => 'required|date',
                'expiry_date' => 'required|date|after:start_date',
                'discount_type' => 'required|string',
                'discount_value' => 'required|numeric',
                'maximum_amount_for_percent' => 'nullable|numeric',
                'for_new_user_only' => 'nullable|boolean',
            ]);

            $couponCode = CouponCode::findOrFail($id);

            $couponCode->update($validatedData);

            return $this->successResponse('Data updated successfully', $couponCode);
        } catch (ValidationException|ModelNotFoundException|Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Delete Coupon code
    */
    public function destroy($id): JsonResponse
    {
        try {
            $couponCode = CouponCode::findOrFail($id);

            CouponCodeSubscriptionPlan::where('coupon_code_id', $couponCode->id)->delete();

            $couponCode->delete();

            return $this->successResponse('Data deleted successfully', null);
        } catch (ModelNotFoundException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get Coupon code subscription plans
    */
    public function getCouponCodeSubscriptionsPlans(Request $request): JsonResponse
    {
        try {
            $perPage = (int)$request->input('per_page', 10);

            $query = CouponCodeSubscriptionPlan::with('coupon_code', 'subscription_plan')->orderByDesc('id');

            return $this->successResponse('Data fetched successfully', $query->paginate($perPage));

        } catch (QueryException|Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get details of show coupon code subscription plans
    */
    public function showCouponCodeSubscriptionsPlans(string $id): JsonResponse
    {
        try {
            $data = CouponCodeSubscriptionPlan::with('coupon_code', 'subscription_plan')->findOrFail($id);

            return $this->successResponse('Data fetched successfully',$data);
        }catch (ModelNotFoundException $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Add coupon code to subscription plans
    */
    public function storeSubscriptionPlan(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'coupon_code_id' => 'required|exists:coupon_codes,id',
                'subscription_plan_id' => 'required|exists:subscription_plans,id',
                'is_active' => 'nullable|boolean',
                'maximum_apply' => 'nullable|integer',
                'maximum_single_user_apply' => 'nullable|integer',
            ]);
            $subscriptionPlan = CouponCodeSubscriptionPlan::create($validatedData);

            return $this->successResponse('Successfully added to subscription plans coupon', $subscriptionPlan);
        } catch (ValidationException|Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Update subscription plans
    */
    public function updateSubscriptionPlan(Request $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'coupon_code_id' => 'required|exists:coupon_codes,id',
                'subscription_plan_id' => 'required|exists:subscription_plans,id',
                'is_active' => 'nullable|boolean',
                'maximum_apply' => 'nullable|integer',
                'maximum_single_user_apply' => 'nullable|integer',
            ]);

            $subscriptionPlan = CouponCodeSubscriptionPlan::findOrFail($id);
            $subscriptionPlan->update($validatedData);

            return $this->successResponse('Subscription plans for coupon updated successfully', $subscriptionPlan);
        }catch ( ModelNotFoundException|ValidationException $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Coupon code subscription delete
    */
    public function destroySubscriptionPlan($id): JsonResponse
    {
        try {
            $subscriptionPlan = CouponCodeSubscriptionPlan::findOrFail($id);

            $subscriptionPlan->delete();

            return $this->successResponse('Data deleted successfully', null);
        } catch (ModelNotFoundException $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

}

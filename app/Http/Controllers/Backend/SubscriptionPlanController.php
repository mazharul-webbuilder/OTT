<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\SubscriptionPlanResource;
use App\Models\SubscriptionPlan;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class SubscriptionPlanController extends Controller
{
    use ResponseTrait;
    public function __construct()
    {
        $this->middleware('permission:subscription-plan-list|subscription-plan-create|subscription-plan-edit|subscription-plan-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:subscription-plan-create', ['only' => ['store']]);
        $this->middleware('permission:subscription-plan-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:subscription-plan-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            if ($request->has('per_page')) {
                $per_page = $request->per_page;
             }else{
                $per_page = 10;
             }
            $data =  SubscriptionPlan::latest()->paginate($per_page);

            return $this->successResponse('Data fetched Successfully', $data);

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $rules = array(
            'plan_name' => 'required',
            'plan_slug' => 'required',
            'is_discounted' => 'required',
            'number_of_allowed_device' => 'required',
            'number_of_allowed_stream' => 'required',
            'duration' => 'required',
            'regular_price' => 'required',
            'status' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse(message: "Validation Error", data: $validator->errors());
        }
        try {
            $subscription_plan = new SubscriptionPlan();
            $subscription_plan->plan_name = $request->plan_name;
            $subscription_plan->plan_slug = $request->plan_slug;
            $subscription_plan->discount_type = $request->discount_type;
            $subscription_plan->description = $request->description;
            $subscription_plan->is_discounted = $request->is_discounted;
            $subscription_plan->discount_rate = $request->discount_rate;
            $subscription_plan->discount_price = $request->discount_price;
            $subscription_plan->number_of_allowed_stream = $request->number_of_allowed_stream;
            $subscription_plan->number_of_allowed_device = $request->number_of_allowed_device;
            $subscription_plan->discount_expiry_date = $request->discount_expiry_date;
            $subscription_plan->duration = $request->duration;
            $subscription_plan->regular_price = $request->regular_price;
            $subscription_plan->status = $request->status;
            $subscription_plan->is_renewable = $request->is_renewable;
            $subscription_plan->save();

            return $this->successResponse(message: 'Data store successfully', data:  $subscription_plan);
        } catch (Exception $e) {
            return $this->errorResponse(message: 'Something went wrong!' ,data: $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $subscription_plan = SubscriptionPlan::findOrFail($id);

            $data = new SubscriptionPlanResource($subscription_plan);

            return $this->successResponse('Data Fetch successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(),null,null);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $rules = array(
            'plan_name' => 'required',
            'plan_slug' => 'required',
            'is_discounted' => 'required',
            'number_of_allowed_device' => 'required',
            'number_of_allowed_stream' => 'required',
            'duration' => 'required',
            'regular_price' => 'required',
            'status' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse(message: 'Something went wrong!', data: $validator->fails());
        }
        try {
            $subscription_plan =  SubscriptionPlan::findOrFail($id);
            $subscription_plan->plan_name = $request->plan_name;
            $subscription_plan->plan_slug = $request->plan_slug;
            $subscription_plan->discount_type = $request->discount_type;
            $subscription_plan->description = $request->description;
            $subscription_plan->is_discounted = $request->is_discounted;
            $subscription_plan->discount_price = $request->discount_price;
            $subscription_plan->discount_rate = $request->discount_rate;
            $subscription_plan->number_of_allowed_device = $request->number_of_allowed_device;
            $subscription_plan->number_of_allowed_stream = $request->number_of_allowed_stream;
            $subscription_plan->discount_expiry_date = $request->discount_expiry_date;
            $subscription_plan->duration = $request->duration;
            $subscription_plan->regular_price = $request->regular_price;
            $subscription_plan->status = $request->status;
            $subscription_plan->is_renewable = $request->is_renewable;
            $subscription_plan->update();

            return $this->successResponse('Data store successfully', $subscription_plan);
        } catch (Exception $e) {
            return $this->errorResponse(message: "Something went wrong!", data: $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $data = SubscriptionPlan::find($id);
            if ($data != null) {
                $data->delete();
                return $this->successResponse('Data deleted Successfully', null);
            }
            return $this->successResponse('Content Not found with this id', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

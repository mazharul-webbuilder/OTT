<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ResponseTrait;

    public function getCustomerList(Request $request) 
    {
        if ($request->has('per_page')) {
            $per_page = $request->per_page;
        } else {
            $per_page = 10;
        }

        $query = User::with(['subscriptionPlans', 'allSubscriptionPlans']);
        
        // Apply search query if present
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if (!empty($request->from_date) && !empty($request->to_date)) {
            $query = $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        // Include users with subscription plans
        $totalSubscribersQuery = clone $query;
        $totalSubscribersCount = $totalSubscribersQuery->has('allSubscriptionPlans')->count();

        $subscribersQuery = clone $query;
        $subscribersCount = $subscribersQuery->has('subscriptionPlans')->count();

        // Exclude users with subscription plans
        $nonSubscribersQuery = clone $query;
        $nonSubscribersCount = $nonSubscribersQuery->whereDoesntHave('subscriptionPlans')->count();

        // Include only users with exactly one subscription plan
        $newSubscribersQuery = clone $query;
        $newSubscribersCount = $newSubscribersQuery->has('subscriptionPlans', '=', 1)->count();

        if ($request->type == 'customer') {
            $query = $query->whereDoesntHave('subscriptionPlans');
        }

        if ($request->type == 'subscriber') {
            $query = $query->has('subscriptionPlans');
        }

        $query = $query->OrderBy('id', 'desc')->paginate($per_page);

        // Transform the data to include the desired fields
        $transformedData = $query->map(function ($user) {
            $subscriptionPlan = $user->subscriptionPlans->first();
            
            return [
                'id' => $user->id,
                'phone' => $user->phone,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'dob' => $user->dob,
                'status' => $user->account_status, 
                'created_at' => $user->created_at->format('d-M,Y'),
                'package_name' => $subscriptionPlan != null ? optional($subscriptionPlan->subscriptionPlan)->plan_name : "",
                'price' => $subscriptionPlan != null ? $subscriptionPlan->price : '',
                'payment_channel' => $subscriptionPlan != null ? optional($subscriptionPlan->payment)->payment_channel : '',
            ];
        });

        $data = [
            'nonSubscribersCount' => $nonSubscribersCount,
            'subscriber_count' => $subscribersCount,
            'total_subscription' => $totalSubscribersCount,
            'active_subscription' => $subscribersCount,
            'new_subscription' => $newSubscribersCount,
            'customer_list' => $transformedData,
            'pagination' => [
                'total' => $query->total(),
                'per_page' => $query->perPage(),
                'current_page' => $query->currentPage(),
                'last_page' => $query->lastPage(),
                'from' => $query->firstItem(),
                'to' => $query->lastItem(),
            ],
        ];

        return $this->successResponse('Data fetched Successfully', $data);

    }

    public function deleteCustomer($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            // Optional: Add additional checks or logic before deleting
            
            $user->delete();

            return $this->successResponse('Data Deleted Successfully', []);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Error deleting user'], 500);
        }
    }

    public function changeCustomerStatus($userId, Request $request)
    {
        $this->validate($request, [
            'status' => 'required|string',
        ]);

        try {
            $user = User::findOrFail($userId);
            
            // Optional: Add additional checks or logic before deleting
            
            $user->account_status = $request->status;

            $user->save();

            return $this->successResponse('Data Update Successfully', $user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['message' => 'Error Updating user'], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ResponseTrait, Media;

    /**
     * Return User Profile Data
     */
    public function userProfile($device): JsonResponse
    {
        try {
            $user = User::where('id', Auth::guard('api')->id())->with('userDevice', 'subscriptionPlans')
                ->with(['payments' => function($q){
                    $q->select('id', 'user_id', 'currency', 'charge_id', 'payment_channel', 'amount', 'status','created_at');
                }
                ])->first();

            if ($user->userMeta->isEmpty()) {
                // If user meta is empty, manually create an empty object
                $user['user_profile_meta'] = (object)[];
            } else {
                // If user meta exists, pluck the values
                $user['user_profile_meta'] = $user->userMeta->pluck('value', 'key');
            }
            unset($user->userMeta);

            return $this->successResponse('User found Successfully', $user);
        } catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Update user profile
    */
    public function updateProfile(UserUpdateRequest $request): JsonResponse
    {
        try {
            if ($request->filled('dob')) {
                $age = Carbon::parse($request->input('dob'))->age;

                if ($age < 6) {
                    return $this->errorResponse('You are too young', null);
                }
            }

            $user = Auth::guard('api')->user();

            // include avatar
            if ($request->hasFile('avatar')){
                $file = $request->file('avatar');
                $avatarUrl = $this->uploads($file, $request->path());
            }
            $user->update([
                'name' => $request->input('name'),
                'gender' => $request->input('gender'),
                'dob' => $request->input('dob'),
                'avatar' => $request->hasFile('avatar') ? $avatarUrl['fileName'] : $user->avatar
            ]);

            return $this->successResponse('Profile Updated Successfully', $user);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Update Avatar
    */
    public function updateAvatar(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'avatar' => ['required', 'mimes:jpeg,png,jpg,webp']
        ], ['avatar.mime' => '']);
        if ($validate->fails()){
            return $this->errorResponse($validate->messages(), null);
        }
        $file = $request->file('avatar');

        $avatarUrl = $this->uploads($file, $request->path());

        $user = Auth::guard('api')->user();


        $user->update([
            'avatar' => $request->hasFile('avatar') ? $avatarUrl['fileName'] : $user->avatar
        ]);

        return $this->successResponse('Avatar updated successfully',  $avatarUrl);
    }

    /**
     * Return User Meta Info
    */
    public function userMetaInfo(): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();

            return $this->successResponse(message: 'Data fetched successfully', data: [
                'user' => [
                    'phone' => $user->phone,
                    'metas' => $user->userMeta()->exists() ? $user->userMeta->pluck('value', 'key') : (object)[],
                    'subscription' => [
                        'is_have_any' => $user->allSubscriptionPlans()->exists(),
                        'is_have_any_active' => $user->activeSubscription()->exists(),
                    ],
                ]
            ]);

        } catch (\Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage(), data: null);
        }
    }
}

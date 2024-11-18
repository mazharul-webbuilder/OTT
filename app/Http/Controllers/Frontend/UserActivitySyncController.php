<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\UserActivitySync;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Exception;

class UserActivitySyncController extends Controller
{
    /**
     * Update user activity sync
     */
    public function updateUserSyncActivity(string $device, Request $request): JsonResponse
    {
        try {
            $request->merge([
                'deviceuniqueid' => $request->header( key: 'deviceuniqueid')
            ]);
            // Input Validation
            $validator = Validator::make(data: $request->all(), rules: [
                'deviceuniqueid' => ['required', Rule::exists(table: 'user_devices', column: 'device_unique_id')],
                'content_id' => ['required', Rule::exists(table: 'ott_contents', column: 'id')],
                'last_watch_time' => ['required', 'numeric', 'gt:0'],
            ]);

            if ($validator->fails()){
                throw new ValidationException($validator);
            }
            // Process with Valid data
            $user_id = Auth::guard('api')->id();

            $device_id = UserDevice::where('user_id', $user_id)->where('device_unique_id', $request->header('deviceuniqueid'))->value('id');

            if (is_null($device_id)){
                throw new Exception('Device id is not match with user device list');
            }

            $activitySyncData = [
                'last_watch_time' => $request->input('last_watch_time'),
                'updated_at' => Carbon::now()
            ];

            UserActivitySync::updateOrCreate([
                'user_id' => Auth::guard(name: 'api')->id(),
                'device_id' => $device_id,
                'content_id' => $request->input('content_id')
            ], $activitySyncData);

            return $this->successResponse('User data sync successfully', null);

        }catch (Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

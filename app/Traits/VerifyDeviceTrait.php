<?php

namespace App\Traits;

use App\Enums\OttContentEnum;
use App\Models\UserDevice;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait VerifyDeviceTrait
{
    public function isDeviceVerified(Request $request)
    {

        // dd($request->header('deviceuniqueid'));
        $user_id = Auth::guard('api')->user()->id;
        // dd($user_id);
        $valid_device = UserDevice::where('user_id', $user_id)->where('device_unique_id', $request->header('deviceuniqueid'))->pluck('id')->first();
        // dd($valid_device);
        if ($valid_device) {
            return true;
        } else {
            return false;
        }
    }
}

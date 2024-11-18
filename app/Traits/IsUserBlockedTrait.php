<?php

namespace App\Traits;

use App\Enums\OttContentEnum;
use App\Models\UserBlackList;
use App\Models\UserDevice;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait IsUserBlockedTrait
{
    public function isUserBlockedBeforeLogin($user_id)
    {
        $blocked_user = UserBlackList::where('user_id', $user_id)->where('status', 1)->latest()->first();
        // dd($blocked_user);
        if (!empty($blocked_user)) {
            if ($blocked_user->finish_date == null) {
                return true;
            } else {

                if ($blocked_user->finish_date > Carbon::now() || $blocked_user->finish_date == Carbon::now()) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
    public function isUserBlockedAfterLogin()
    {
        $user_id = Auth::guard('api')->user()->id;
        $blocked_user = UserBlackList::where('user_id', $user_id)->where('status', 1)->latest()->first();
        if (!empty($blocked_user)) {
            if ($blocked_user->finish_date == null) {
                return true;
            } else {

                if ($blocked_user->finish_date > Carbon::now() || $blocked_user->finish_date == Carbon::now()) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
}

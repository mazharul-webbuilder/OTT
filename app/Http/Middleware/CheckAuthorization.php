<?php

namespace App\Http\Middleware;

use App\Models\UserDevice;
use App\Traits\IsUserBlockedTrait;
use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthorization
{
    use  ResponseTrait, IsUserBlockedTrait;
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::guard('admin')->check()) {
            return $next($request);
        } else {
            if (Auth::guard('api')->check()) {

                if ("api/v1/logout" == $request->path()) {
                    return $next($request);
                }
                $user_blocked = $this->isUserBlockedAfterLogin();
                if ($user_blocked) {
                    return response()->json(['error' => 'This user has been blocked for this system.'], 401);
                }
                $user_id = Auth::guard('api')->user()->id;
                $valid_device = UserDevice::where('user_id', $user_id)->where('device_unique_id', $request->header('deviceuniqueid'))->pluck('id')->first();

                !!$valid_device ? $next($request) : response()->json(['error' => 'Device not found make user log out.'], 420);

                return $next($request);
            }
        }

        return response()->json(['error' => 'Unauthenticated.'], 401);
    }
}

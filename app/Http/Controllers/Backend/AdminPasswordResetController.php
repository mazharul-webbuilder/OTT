<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Jobs\AdminPasswordResetJob;
use App\Models\Admin;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class AdminPasswordResetController extends Controller
{
    use ResponseTrait;

    private string $VERIFIED_TEXT = 'APPROVED';

    /**
     * Sent Verification code to Admin email
    */
    public function sendVerificationCode(Request $request): JsonResponse
    {
        $validate = Validator::make($request->all(), [
            'email' => ['required', 'email', Rule::exists('admins', 'email')]
        ],[
           'email.exists' => 'Email is not correct'
        ]);

        if ($validate->fails()){
            return $this->errorResponse($validate->messages(), null);
        }
        try {
            $user = Admin::where('email', $request->input('email'))->first();

            dispatch(new AdminPasswordResetJob($user));

            return $this->successResponse(message: 'Verification code has been send to your email.', data: [
                'email' => $request->input('email'),
                'code_will_expired_in_second' => $this->getVerificationCodeExpiredTime()
            ]);

        } catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }

    }

    /**
     * Password Verification Code Verify
    */
    public function verifyCode(Request $request): JsonResponse
    {
        $validate = Validator::make(data: $request->all(), rules: [
            'email' => ['required', 'email'],
            'verify_code' => ['required', 'min:6', 'max:6' ]
        ], messages: [
            'verify_code.min' => 'Invalid verify code',
            'verify_code.max' => 'Invalid verify code',
        ]);

        if ($validate->fails()){
            return $this->errorResponse($validate->messages(), null);
        }
        try {
            $admin = Admin::where('email', $request->input('email'))->first();
            if (!is_null($admin)){
                $timeDifferenceInSec = Carbon::now()->diffInSeconds($admin->verification_code_created_at); // EX: 60
                // Check is code expired or not
                if ($timeDifferenceInSec <=  $this->getVerificationCodeExpiredTime()){
                    // Check user has attempt left
                    if ($admin->verify_attempt_left > 0){
                        // Check code
                        if ($admin->verification_code == $request->input('verify_code')){
                            $admin->update([
                                'verification_code' => $this->VERIFIED_TEXT
                            ]);
                            return $this->successResponse('Verified Successfully', ['email' => $admin->email]);
                        } else{
                            $admin->update([
                                'verify_attempt_left' => $admin->verify_attempt_left - 1
                            ]);
                            return $this->errorResponse('Verification code is not correct,', ['attempt_left' => $admin->verify_attempt_left]);
                        }
                    }else{
                        return $this->errorResponse('You have no attempt left', null);
                    }
                } else{
                    return $this->errorResponse('Verification code is expired', null);
                }

            }else{
                return $this->errorResponse('Something went wrong', null);
            }
        } catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Set New Password
    */
    public function setNewPassword(Request $request): JsonResponse
    {
        $validate = Validator::make(data: $request->all(), rules: [
            'email' => ['required', Rule::exists('admins', 'email')],
            'password' => ['required', 'confirmed'],
        ],messages: [
            'email.exists' => 'Incorrect email'
        ]);
        if ($validate->fails()){
            return $this->errorResponse($validate->messages(), null);
        }
        // Process with valid data
        try {
            $admin = Admin::where('email', $request->input('email'))->first();

            $timeDifferenceInSec = Carbon::now()->diffInSeconds($admin->verification_code_created_at); // EX: 60
            // Check is code expired or not
            if ($timeDifferenceInSec <= $this->getVerificationCodeExpiredTime()){
                // Check is user done verification for password change
                if ($admin->verification_code == $this->VERIFIED_TEXT){
                    $admin->update([
                        'verification_code' => null,
                        'password' => Hash::make($request->input('password'))
                    ]);
                    return $this->successResponse(message: 'Password changed successfully', data: null);
                }else{
                    return $this->errorResponse(message: 'You are not verified for reset password', data: null);
                }
            } else{
                $admin->update([
                    'verification_code' => null,
                ]);
                return $this->errorResponse('Verification code is expired', null);
            }
        } catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * get Verification code expired in sec
    */
    private function getVerificationCodeExpiredTime()
    {
        return config('app.email_verification_code_expired_time');
    }
}

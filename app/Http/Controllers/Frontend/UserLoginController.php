<?php

namespace App\Http\Controllers\Frontend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\EmailVerifyUserRegistrationRequest;
use App\Http\Requests\LoginWithEmailRequest;
use App\Http\Requests\LoginWithFacebookRequest;
use App\Http\Requests\LoginWithGoogleRequest;
use App\Http\Requests\OtpVerifyRequest;
use App\Http\Requests\PhoneRegisterRequest;
use App\Jobs\EmailVerificationJob;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\UserMeta;
use App\Traits\IsUserBlockedTrait;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserLoginController extends Controller
{
    use  ResponseTrait, IsUserBlockedTrait, Media;

     public function __construct()
     {
         $this->middleware('authorization')->only(methods: ['removeProfileimage', 'userMeta', 'logout', 'refresh']);
     }

    /**
     * Register user by phone
    */
    public function phoneRegister($device, PhoneRegisterRequest $request): JsonResponse
    {
        $user = User::where('phone', $request->input('phone'))->first();

        // $otp =  rand(100000, 999999);
        $otp = 123456;

        $data = [
            'phone' => $request->input('phone'),
            'otp' => $otp,
            'password' => Hash::make($otp),
            'otp_created_at' => Carbon::now(),
            'otp_verify_attempt_left' => config('app.maximum_otp_verify_attempt', 5)
        ];

        try {
            if (!empty($user)) {
                if ($this->isUserBlockedBeforeLogin($user->id)) {
                    return $this->successResponse('This user has been blocked for this system', null);
                } else {
                    $user->update($data);
                }
            } else {
                User::create($data);
            }
            unset($data['otp']);
            unset($data['password']);
            return $this->successResponse('We have sent an otp to this phone number', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null);
        }
    }

    /**
     * Verify the OTP
    */
    public function otpVerifyForLogin($device, OtpVerifyRequest $request): JsonResponse
    {
        $now = Carbon::now();
        $user = User::where('phone', $request->input('phone'))->first();

        $verifySecond = $now->diffInSeconds($user->otp_created_at);

        if ($verifySecond <= 120) {
            if ($user->otp_verify_attempt_left > 0) {
                if ($user->otp == $request->input('otp')) {
                    try {
                        $user->update([
                            'account_status' => 'Active',
                            'is_verified' => 1,
                            'otp_verify_attempt_left' => 0
                        ]);
                        $request['password'] = $request->input('otp');
                        $credentials = $request->only('phone', 'password');
                        if ($token = $this->guard()->attempt($credentials)) {

                            $this->manageUserDevice(request: $request, user: $user, token: $token);

                            return $this->respondWithToken($token);
                        }
                    } catch (Exception $e) {
                        return $this->errorResponse($e->getMessage(), null);
                    }
                } else {
                    $user->update([
                        'otp_verify_attempt_left' => $user->otp_verify_attempt_left - 1
                    ]);
                    return $this->errorResponse('Otp Not Verified', ['Otp verify attempt lest' => $user->otp_verify_attempt_left]);
                }
            } else {
                return $this->errorResponse('You attempt maximum time or otp already used', null);
            }
        } else {
            $user->update([
                'otp' => '',
                'otp_created_at' => null,
                'password' => '',
                'otp_verify_attempt_left' => 0
            ]);
            return $this->errorResponse('Otp verification time exceeded !', null, null);
        }
        return $this->errorResponse(data: null);
    }
    /**
     * Login With Google
    */
    public function loginWithGoogle(LoginWithGoogleRequest $request): JsonResponse
    {
        try {
            $data = $this->setAndGetUserData($request);

            if (DB::table('users')->where('email', $data['email'])->where('google_id', $data['google_id'])->exists()){
                $user = User::where('email', $data['email'])->first();
            } else {
                // Check for duplicate user email
                if (DB::table('users')->where('email', $data['email'])->exists()){
                    return $this->errorResponse('An account is exist with this email. Please login with email & password.', null);
                }
                // Check for duplicate user google_id
                if (DB::table('users')->where('google_id', $data['google_id'])->exists()) {
                    return $this->errorResponse('Google id is already used', null);
                }
                $user = User::create($data);
            }

            // Generate JWT token for the user
            $token = JWTAuth::fromUser($user);

            if (isset($token)){
                $this->manageUserDevice(request: $request, user: $user, token: $token);
            }

            return $this->respondWithToken($token);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Login With Facebook
    */
    public function loginWithFacebook(LoginWithFacebookRequest $request): JsonResponse
    {
        try {
            $data = $this->setAndGetUserData($request);

            if (DB::table('users')->where('email', $data['email'])->where('facebook_id', $data['facebook_id'])->exists()){
                $user = User::where('facebook_id', $data['facebook_id'])->first();
            } else {
                // Check for duplicate user email
                if (DB::table('users')->where('email', $data['email'])->exists()){
                    return $this->errorResponse('Email is already used', null);
                }
                // Check for duplicate user google_id
                if (DB::table('users')->where('facebook_id', $data['facebook_id'])->exists()) {
                    return $this->errorResponse('Facebook id is already used', null);
                }
                $user = User::create($data);
            }

            // Generate JWT token for the user
            $token = JWTAuth::fromUser($user);

            if (isset($token)){
                $this->manageUserDevice(request: $request, user: $user, token: $token);
            }

            return $this->respondWithToken($token);

        } catch (Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Send verification code for email login
    */
    public function sendVerificationCodeForEmailLogin(EmailVerificationRequest $request): JsonResponse
    {
        try {
            // Get first or create a new user object
            $user = User::firstOrNew(['email' => $request->input('email')]);
            $now = Carbon::now();
            if ($user->exists){
                // Control overwhelming request
                $previousCodeSendInSec = Carbon::parse($user->otp_created_at)->diffInSeconds(Carbon::parse($now));
                if ($previousCodeSendInSec < config('app.email_verification_code_resend_time_in_sec', 600)){ // 600sec equals to 10 minutes
                    return $this->errorResponse('A code is already send to your email. You can request a new code in '.intdiv(config('app.email_verification_code_resend_time_in_sec', 600),60).' minutes.', null);
                }
            } else{
                // Set the required field with demo data and get
                $data = $this->setAndGetUserData($request);
                $user->fill($data);
            }
            $user->otp = getVerificationCode();
            $user->otp_created_at = $now;
            $user->otp_verify_attempt_left = config('app.maximum_otp_verify_attempt', 5);
            $user->save();
            // Dispatch job for send verification code by mail
            dispatch(new EmailVerificationJob($user));

            return $this->successResponse(
                message: 'Email Verification code send successfully',
                data: Str::contains($request->path(), 'app/') ? null : 'You have '.$user->otp_verify_attempt_left.' attempt left'
            );
        } catch (Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * verify Email Verification Code
     * @throws ValidationException
     */
    public function verifyEmailVerificationCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email',Rule::exists('users', 'email')],
            'verify_code' => ['required', 'digits:6']
        ],[
            'verify_code.required' => 'Verify code is required',
            'email.exists' => 'Email address is not correct'
        ]);
        if ($validator->fails()){
            throw new ValidationException($validator);
        }
        // Process with valid data
        try {
            $user = User::where('email', $request->input('email'))->first();

            $codeTimeCount = Carbon::parse($user->otp_created_at)->diffInSeconds(Carbon::parse(Carbon::now()));
            // Check the code is valid to proceed
            if ($codeTimeCount < config('app.email_verification_code_valid_time_in_sec', 600)){
                // Check is able to attempt for verify code
                if ($user->otp_verify_attempt_left > 0){
                    // Check the code and marked the user verified
                    if ($user->otp == $request->input('verify_code')){
                        $user->update([
                            'is_verified' => 1,
                            'otp_verify_attempt_left' => 0
                        ]);
                        return $this->successResponse('Account verified.', null);
                    }else{
                        $user->update([
                            'otp_verify_attempt_left' => $user->otp_verify_attempt_left - 1
                        ]);
                        return $this->errorResponse('Incorrect code','You have '.$user->otp_verify_attempt_left.' attempt left');
                    }
                }else{
                    return $this->errorResponse('You have no attempt left', null);
                }
            }else{
                return $this->errorResponse('Your code is invalid. Try new one', null);
            }
        } catch (Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Register Email Verified User and
     * Provide a jwt token
    */
    public function registerEmailVerifiedUser(EmailVerifyUserRegistrationRequest $request): JsonResponse
    {
        try {
            // Retrieve email verified and pending user
            $user = User::where('email', $request->input('email'))->where('is_verified', 1)->where('account_status', 'Pending')->first();
            if (!is_null($user)){
                $verifiedTime = Carbon::parse($user->otp_created_at)->diffInSeconds(Carbon::parse(Carbon::now()));
                // Check is email verified in last ex:10 minutes, and extra ex:10 minutes to complete registration
                if ($verifiedTime < (config('app.email_verification_code_valid_time_in_sec', 600) + 600)){
                    $data = $request->except('deviceuniqueid', 'device_name', 'password_confirmation');
                    $data['password'] = Hash::make($request->input('password'));
                    $data['account_status'] = 'Active';
                    $user->update($data);

                    // Generate JWT token for the user
                    $token = JWTAuth::fromUser($user);

                    if (isset($token)){
                        $this->manageUserDevice(request: $request, user: $user, token: $token);
                    }
                    return $this->respondWithToken($token);
                }else{
                    throw new Exception('Registration session expired');
                }
            }else{
                throw new Exception('Invalid request');
            }
        } catch (Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Login with email and password
    */
    public function loginWithEmail(LoginWithEmailRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        try {
            if ($token = $this->guard()->attempt($credentials)) {

                $user = Auth::guard('api')->user();

                $this->manageUserDevice(request: $request, user: $user, token: $token);

                return $this->respondWithToken($token);
            }else{
                throw new Exception("Incorrect Email Or Password");
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null);
        }
    }

    /**
     * To remove user profile picture
    */
    public function removeProfileimage($device): JsonResponse
    {
        $user_id = Auth::guard('api')->id();
        $user_meta_image = UserMeta::where('user_id',$user_id)->where('key','image')->first();
        if($user_meta_image != null){
            $user_meta_image->delete();
            return $this->successResponse('Successfully deleted',null);
        }else{
            return $this->successResponse('Image not found',null);
        }
    }

    /**
     * Get user Meta Info
    */
    public function userMeta($device, Request $request): JsonResponse
    {
        $user_id = Auth::guard('api')->id();

        foreach ($request->all() as $key => $value) {
            // dump($key, $value);
            $data = [
                'key' => $key,
                'value' => $value,
                'user_id' => $user_id
            ];
            if ($key == 'image') {
                $path = "/OTT/User/Images";
                $fileData = $this->uploads($value, $path);
                $image = $fileData;
                unset($data['value']);
                $data = [
                    'key' => $key,
                    'value' => $image['fileName'],
                    'user_id' => $user_id
                ];
                // return response()->json($data, 200);
                // dd($data);
            }

            $exist_meta = UserMeta::where('user_id', $user_id)->where('key', $key)->first();
            // dump($exist_meta);
            if (!empty($exist_meta)) {
                UserMeta::where('user_id', $user_id)->where('key', $key)->update($data);
            } else {
                UserMeta::create($data);
            }
        }
        // unset($data['image']);
        // dd($data);
        $user_meta = UserMeta::where('user_id', $user_id)->get();
        return $this->successResponse('User found Successfully', $user_meta);
    }

    /**
     * Log the user out (Invalidate the token)
     */
    public function logout($device, Request $request): JsonResponse
    {
        UserDevice::where('device_unique_id', $request->input('device_unique_id'))->delete();

        $this->guard()->logout();

        return $this->successResponse('Successfully logged out', null, null);
    }

    /**
     * Refresh a token.
     */
    public function refresh($device): JsonResponse
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Formatted Response
    */
    protected function respondWithToken($token): JsonResponse
    {
        $data_collection = collect([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
        return response()->json([
            'status'    => true,
            'message'   => 'Token generated',
            'data'      => $data_collection,
            'errors'    => null,
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     */
    public function guard()
    {
        return Auth::guard('api');
    }

    /**
     * @param LoginWithFacebookRequest|LoginWithGoogleRequest|Request $request
     * @return array
     */
    private function setAndGetUserData(LoginWithFacebookRequest | LoginWithGoogleRequest | Request $request): array
    {
        $data = $request->except('device_name', 'deviceuniqueid');
        // Common Keys
        $data['phone'] = Str::padLeft(value: 'DEMO', length: 4) . strtotime(Carbon::now()) + rand(min: 1000, max: 9999);
        $data['password'] = '';
        $data['otp'] = '';
        // Add extra key for Facebook & Google Login
        if ($request instanceof LoginWithFacebookRequest || $request instanceof LoginWithGoogleRequest){
            $data['account_status'] = 'Active';
            $data['is_verified'] = 1;
        }
        return $data;
    }

    /**
     * Manage User Device
    */
    private function manageUserDevice($request, $user, $token): void
    {
        $deviceData = [
            'user_id' => $user->id,
            'device_name' => $request->device_name,
            'device_unique_id' => $request->deviceuniqueid,
            'last_access' => Carbon::now(),
            'auth_token'  => $token,
        ];
        $userDevice = UserDevice::where('device_unique_id', $request->deviceuniqueid)->first();
        if (!is_null($userDevice)) {
            UserDevice::where('id', $userDevice->id)->delete();
        }
        UserDevice::create($deviceData);
    }
}

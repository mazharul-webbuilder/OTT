<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    use ResponseTrait;
    public function adminLogin(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        try {
            if ($token = $this->guard()->attempt($credentials)) {
                return $this->respondWithToken($token);
            }else{
                return $this->validationError("Incorrect Email Or Password", null, null);

            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
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
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard(): \Illuminate\Contracts\Auth\Guard
    {
        return Auth::guard('admin');
    }
    public function logout(): JsonResponse
    {
        $this->guard()->logout();
        return $this->successResponse('Successfully logged out', null, null);
    }
}

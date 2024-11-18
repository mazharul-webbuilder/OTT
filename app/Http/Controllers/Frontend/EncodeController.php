<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OttContent;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EncodeController extends Controller
{
    /**
     * Get Content List
    */
    public function contentList(Request $request): JsonResponse
    {
        if (!$request->bearerToken() || Hash::check(config('constants.ENCODING_PASSWORD'), $request->bearerToken()) == false) {
            return $this->unauthorizedResponse("Unauthorized", null);
        }
        try {
            $contents = OttContent::select('id', 'uuid', 'created_at')->with('contentSource')->latest()->paginate(5);

            return $this->successResponse('Successfully Fetched', $contents);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

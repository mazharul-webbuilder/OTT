<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{


    /*
    *title: Success Response Function
    *description:
    *Author: Yeapes
    *date:2022-07-18 12:04:10
    *
    */
    public function successResponse($message, $data, $errors = null):JsonResponse
    {

        return response()->json([
            'status'    => true,
            'message'   => $message,
            'data'      => $data,
            'errors'    => $errors
        ]);
    }


    /*
    *title: Error Response Function
    *description:
    *Author: Yeapes
    *date:2022-07-18 12:04:10
    *
    */
    public function errorResponse($message = 'Something went wrong', $data, $errors = null):JsonResponse
    {
        return response()->json([
            'status'    => false,
            'message'   => $message,
            'data'      => $data,
            'errors'    => true
        ],400);
    }
    public function unauthorizedResponse($message = 'Unauthorized', $data, $errors = null):JsonResponse
    {
        return response()->json([
            'status'    => false,
            'message'   => $message,
            'data'      => $data,
            'errors'    => true
        ],401);
    }
    public function validationError($message = 'Validation Error', $data, $errors = null):JsonResponse
    {
        return response()->json([
            'status'    => false,
            'message'   => $message,
            'data'      => $data,
            'errors'    => $errors
        ],422);
    }
}

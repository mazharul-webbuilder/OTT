<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentMethods;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{

    /**
     * Get available payment methods
    */
    public function getAvailablePaymentMethods(string $device): JsonResponse
    {
        try {
            $paymentMethodsCollection = PaymentMethods::where('status', true)->get();

            return $this->successResponse('Data fetched Successfully', $paymentMethodsCollection);

        } catch (QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Check payment status
     */
    public function getPaymentStatus(string $device,string $paymentID): JsonResponse
    {
        try {
            $paymentModel = Payment::where('charge_id', $paymentID)->firstOrFail();

            if ($paymentModel->user_id != Auth::guard('api')->id()){
                throw new Exception('Unauthorized to access this payment info');
            }

            return $this->successResponse(message: 'Fetched Successfully', data:[
                'is_payment_success' => $paymentModel->status === 'completed'
            ]);

        } catch (ModelNotFoundException|Exception $exception){
            return $this->errorResponse(message: $exception->getMessage(), data: null);
        }
    }
}

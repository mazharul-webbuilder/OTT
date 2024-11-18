<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FrontendCustomSlider;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class CustomSliderController extends Controller
{
    /**
     * Custom Sliders
    */
    public function customSlidersFrontend(string $device): JsonResponse
    {
        try {
            $frontendSliders = FrontendCustomSlider::where('is_active', 1)
                ->orderBy('sorting_order', 'asc')->get();

            $responseData = response()->json($frontendSliders);

            return $this->successResponse('Data fetched Successfully', $responseData);

        } catch (ModelNotFoundException|Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage(), data: null);
        }
    }


    /**
     * Get custom single slider
    */
    public function customSingleSliderFrontend(string $device, string|int $slug): JsonResponse
    {
        try {
            $frontendSliders = FrontendCustomSlider::where('slider_type_slug', $slug)
                ->where('is_active', 1)
                ->orderBy('sorting_order', 'asc')
                ->get();

            $responseData = response()->json($frontendSliders);

            return $this->successResponse('Data fetched Successfully', $responseData);

        } catch (QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

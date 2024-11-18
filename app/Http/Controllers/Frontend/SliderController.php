<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OttSlider;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class SliderController extends Controller
{
    /**
     * Get all Sliders with rootCategory and contents
    */
    public function sliders(string $device): JsonResponse
    {
        try {
            $sliderCollection = OttSlider::where('status', 'Published')->with(['rootCategory' => function ($query) {
                    $query->select('id', 'title as movie_type');
                }])->with(['ottContent' => function ($query) {
                    $query->with('contentSource')->select('id','uuid', 'title', 'runtime', 'imdb', 'release_date');
                }])->orderBy('order', 'desc')->get();

            $responseData = response()->json($sliderCollection);

            return $this->successResponse('Data fetched Successfully', $responseData);

        } catch (QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }


    /**
     * Get Category sliders with content
    */
    public function categorySliders(string $device, string $id): JsonResponse
    {
        try {
            $sliderCollection = OttSlider::where('status', 'Published')->where('root_category_id', $id)->with(['rootCategory' => function ($query) {
                    $query->select('id', 'title as movie_type');
                }])->with(['ottContent' => function ($query) {
                    $query->with('contentSource')->select('id','uuid', 'title', 'runtime', 'imdb', 'release_date');
                }])->orderBy('order', 'desc')->get();

            $responseData = response()->json($sliderCollection);

            return $this->successResponse('Data fetched Successfully', $responseData);

        } catch (QueryException|Exception $e) {
            return $this->errorResponse(message: $e->getMessage(), data: null);
        }
    }
}

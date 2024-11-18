<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\OttSeriesResource;
use App\Http\Resources\SingleOttSeriesResource;
use App\Models\OttSeries;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    use ResponseTrait;

    /**
     * Get Ott Series
     */
    public function getSeries(string $device, Request $request): JsonResponse
    {
        try {
            $ottSeriesCollection = OttSeries::get();

            $seriesResource = OttSeriesResource::collection($ottSeriesCollection);

            return $this->successResponse('Data fetched Successfully', $seriesResource);

        } catch (QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get single series details
    */
    public function singleSeries(string $device,string  $slug): JsonResponse
    {
        try {
            $seriesModel = OttSeries::where('slug', $slug)->with('ottContents')->firstOrFail();

            $seriesResource =  new SingleOttSeriesResource($seriesModel);

            return $this->successResponse('Data fetched Successfully', $seriesResource);

        } catch (ModelNotFoundException|Exception $exception) {
            $message = $exception instanceof ModelNotFoundException ? "Slug is incorrect" : $exception->getMessage();

            return $this->errorResponse($message, null);
        }
    }
}

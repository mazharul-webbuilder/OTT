<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\SingleOttSeasonResource;
use App\Models\Season;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class SeasonController extends Controller
{
    /**
     * Get single season
    */
    public function singleSeason(string $device, string $id): JsonResponse
    {
        try {
            $seasonModel = Season::with('ottContents')->findOrFail($id);

            $seasonResource =  new SingleOttSeasonResource($seasonModel);

            return $this->successResponse('Data fetched Successfully', $seasonResource);

        } catch (ModelNotFoundException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\OttContentResource;
use App\Models\OttContent;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    use ResponseTrait;

    /**
     * Ott content search by key
    */
    public function searchContent(string $device, Request $request): JsonResponse
    {
        $keyword = (string) $request->input('keyword');
        try {
            $cacheKey = 'ott_content_search_' . $keyword;

            if (Cache::has($cacheKey)) {
                $ottContentCollection = Cache::get($cacheKey);
            } else {
                $query = OttContent::where('status','Published');

                getSearchQuery($query, $keyword, 'title', 'short_title');

                $ottContentCollection = $query->get();

                Cache::put($cacheKey, $ottContentCollection, 60); // Cache for 60 minutes
            }
            if ($ottContentCollection->isNotEmpty()) {
                $responseData =  OttContentResource::collection($ottContentCollection);
                $countTotalContent = count($responseData);
                return $this->successResponse($countTotalContent . ' contents found with ' . $keyword, $responseData);
            } else {
                return $this->successResponse('Content not found', null);
            }
        } catch (QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

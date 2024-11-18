<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OttContent;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublishedContentController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:all-published-content', ['only' => ['getAllPublishedContent']]);
    }

    /**
     * Return all published content resources
    */
    public function getAllPublishedContent(Request $request): JsonResponse
    {
        try {
            $perPage =  (int) $request->input('per_page', 10);
            $query = OttContent::with('contentTrailer', 'contentOwner','contentSource', 'contentTrailer', 'marketplaces')->where('status', '=', 'Published');
            // filter
            if ($request->filled('query_string')) {
                $query = getSearchQuery($query, $request->input('query_string'),'title', 'short_title');
            }
            return $this->successResponse("Data fetched successfully", $query->paginate($perPage));
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\OttContentResource;
use App\Http\Resources\PageResource;
use App\Models\OttContent;
use App\Models\Page;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    use ResponseTrait;
    public function searchContent($device, Request $request)
    {
        try {
            $pages = Page::select('id','title','slug')->where('is_published', 1)->get();
            return $this->successResponse('Pages Fetch Successfully.', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Get all pages
    */
    public function getPages(string $device): JsonResponse
    {
        try {
            $pageCollection = Page::where('is_published', 1)->get();

            $pagesResource =  PageResource::collection($pageCollection);

            return $this->successResponse('Page Data Fetch Successfully', $pagesResource);

        } catch (QueryException|Exception $exception) {

            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Single Page details
    */
    public function getPage(string $device,string  $slug): JsonResponse
    {
        try {
            $pageModel = Page::where('slug', $slug)->where('is_published', 1)->firstOrFail();

            $pageResource = new PageResource($pageModel);

            return $this->successResponse('Page Data Fetch Successfully', $pageResource);

        } catch (ModelNotFoundException|QueryException|Exception $exception) {

            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

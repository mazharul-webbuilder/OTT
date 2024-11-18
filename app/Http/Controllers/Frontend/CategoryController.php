<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\OttContentResource;
use App\Http\Resources\RootCategoryResource;
use App\Http\Resources\SingleRootCategoryResource;
use App\Http\Resources\SingleSubCategoryResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\RootCategory;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Exception;

class CategoryController extends Controller
{
    /**
     * Get Root Categories
    */
    public function getRootCategory(string $device): JsonResponse
    {
        try {
            $categoryCollection = $this->rememberCache(
                key: 'ROOT_CATEGORIES',
                query: function () {
                    return RootCategory::where('status', 'Published')->with('subCategories')->get();
                }
            );

            $responseData =  RootCategoryResource::collection($categoryCollection);

            return $this->successResponse('Data fetched Successfully', $responseData);

        } catch (QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Return Single Root category detail with subcategories
     */
    public function singleRootCategory(string $device, string $slug): JsonResponse
    {
        try {
            $categoryCollection = $this->rememberCache(
                key: "ROOT_CATEGORY_$slug",
                query: function () use ($slug){
                    return RootCategory::with('subCategories')
                        ->where('status', 'Published')
                        ->where('slug', $slug)
                        ->get();
                }
            );

            $responseData = SingleRootCategoryResource::collection($categoryCollection);

            return $this->successResponse('Data fetched Successfully', $responseData);
        } catch (QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }



    /**
     * Root category contents by $slug
     */
    public function rootCategoryContents(string $device, string $slug): JsonResponse
    {
        try {
            $categoryModel = $this->rememberCache(
                key: "ROOT_CATEGORY_CONTENTS_$slug",
                query: function () use ($slug){
                    return RootCategory::where('slug', $slug)->with('ottContents')->firstOrFail();
                }
            );

            $categoryContentCollection = $categoryModel?->ottContents()->take(20)->get();

            $responseData =  OttContentResource::collection($categoryContentCollection);

            return $this->successResponse('Data fetched Successfully', $responseData);

        } catch (ModelNotFoundException|QueryException|Exception $exception) {
            return $this->errorResponse($exception instanceof  ModelNotFoundException ? 'Slug is incorrect' : $exception->getMessage(), null);
        }
    }



    /**
     * Get Sub categories
    */
    public function getSubCategories(string $device): JsonResponse
    {
        try {
            $subCategoryCollection = SubCategory::with(['rootCategory', 'subSubCategories'])->get();

            $responseData =  SubCategoryResource::collection($subCategoryCollection);

            return $this->successResponse('Data fetched Successfully', $responseData);
        } catch (QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get single subcategory with content
    */
    public function singleSubCategory(string $device,string $slug): JsonResponse
    {
        try {
            $subCategoryCollection = SubCategory::where('slug', $slug)->with(['subSubCategories'])->get();

            $responseData = SingleSubCategoryResource::collection($subCategoryCollection);

            return $this->successResponse('Data fetched Successfully', $responseData);
        } catch (QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }


    /**
     * Get Subcategory contents
    */
    public function subCategoryContents(string $device, string $slug): JsonResponse
    {
        try {
            $subCategoryModel = SubCategory::where('slug', $slug)->with(['ottContents' => function($query){
                $query->with(['contentSource' => function($query){
                    $query->select('id', 'ott_content_id', 'content_source', 'source_type', 'video_type')
                        ->where('source_type', 'trailer_path')
                        ->where('processing_status', 'encoded');
                },'tVodSubscriptions']);
            }])->firstOrFail();

            return $this->successResponse('Data fetched Successfully', $subCategoryModel);

        } catch (ModelNotFoundException|QueryException|Exception $exception) {
            return $this->errorResponse($exception instanceof  ModelNotFoundException ? 'Slug is incorrect' : $exception->getMessage(), null);
        }
    }
}

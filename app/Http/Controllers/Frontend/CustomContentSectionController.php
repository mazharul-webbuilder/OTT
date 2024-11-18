<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FrontendCustomContent;
use App\Models\FrontendCustomContentSection;
use App\Models\OttContent;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomContentSectionController extends Controller
{
    /**
     * Frontend custom section data
     */
    public function sectionFrontend(string $device, Request $request): JsonResponse
    {
        try {
            $perPage = $request->input(key: 'per_page', default: 10);

            $data = FrontendCustomContentSection::orderBy('order', 'asc')->paginate($perPage);

            $data->transform(function ($section) {
                // Pluck ottContent from frontendCustomContentLimitedData relation
                $ottContents = $section->frontendCustomContentLimitedData()->with(['ottContent' => function ($query) {
                    $query->select('id', 'title', 'uuid', 'poster', 'access', 'runtime', 'thumbnail_portrait', 'thumbnail_landscape', 'is_tvod_available')
                        ->with(['contentSource' => function($query){
                            $query->select('id', 'ott_content_id', 'content_source', 'source_type', 'video_type')
                                ->where('source_type', 'trailer_path')
                                ->where('processing_status', 'encoded');
                        },'tVodSubscriptions']);
                }])->get()->pluck('ottContent');

                // Assign ottContents to a new property
                $section->ott_contents = $ottContents;

                // Unset frontendCustomContentLimitedData to remove it from the response
                unset($section->frontendCustomContentLimitedData);

                return $section;
            });

            return $this->successResponse('Data fetched Successfully', $data);

        } catch (QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Single Section Frontend
     */
    public function singleSectionFrontend(string $device, string $customSectionId): JsonResponse
    {
        try {
            $customSectionContentIds  = FrontendCustomContent::where([
                'frontend_custom_content_type_id' => $customSectionId,
                'is_active' => 1
            ])->pluck('content_id');

            $frontendCustomSection = FrontendCustomContentSection::findOrFail($customSectionId);

            $responseData = [
                'title' => $frontendCustomSection->content_type_title,
                'ott_contents' => OttContent::whereIn('id', $customSectionContentIds)->with(['contentSource' => function ($q) {
                    $q->where('source_type', 'trailer_path')->select('id', 'ott_content_id', 'source_type', 'content_source');
                }, 'tVodSubscriptions'])
                    ->select('id', 'title', 'uuid', 'poster', 'access', 'thumbnail_portrait', 'thumbnail_landscape', 'is_tvod_available')
                    ->get()
            ];

            return $this->successResponse('Data fetched Successfully', $responseData);

        } catch (QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get single section frontend sliders
    */
    public function singleSectionFrontendSliders(string $device, string $slug): JsonResponse
    {
        try {
            $sliderModel = FrontendCustomContentSection::with('frontendCustomContentSectionSlider')
                ->where('content_type_slug', $slug)
                ->firstOrFail();

            return $this->successResponse('Data fetched Successfully', $sliderModel);

        } catch (ModelNotFoundException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

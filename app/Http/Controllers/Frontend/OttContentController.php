<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\OttContentResource;
use App\Http\Resources\SingleOttContentResource;
use App\Models\FrontendCustomContentSection;
use App\Models\OttContent;
use App\Traits\ContentCheckerTrait;
use App\Traits\ResponseTrait;
use App\Traits\UserDeviceCheckerTrait;
use App\Traits\VerifyDeviceTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OttContentController extends Controller
{
    use ResponseTrait, ContentCheckerTrait, UserDeviceCheckerTrait, VerifyDeviceTrait;

    /**
     * Get Single OTT content
     */
    public function singleOttContent(string $device, Request $request, string $uuid): JsonResponse
    {
        try {
            $contentModel = OttContent::where('uuid', $uuid)
                ->with('ottContentMeta', 'ottSeries', 'rootCategory', 'contentSource', 'castAndCrew', 'ottSeason','tVodSubscriptions')
                ->firstOrFail();

            $is_verify_content_access_for_login = $this->ContentAccessCheck($contentModel->access);

            if ($is_verify_content_access_for_login) {
                // T_VOD Manage
                if ($contentModel->is_tvod_available) {
                    if ($this->HasTVodSubscription($contentModel->id)) {
                        $data = new SingleOttContentResource($contentModel);
                        return $this->successResponse('Data fetched Successfully', [
                            'content_data' => $data,
                            'subscription' => true,
                            'device_stream_limit_exceeded' => false]);
                    } else {
                        return $this->successResponse('No TVod Su Available!!', [
                            'content_data' => null,
                            'subscription' => false,
                            'device_stream_limit_exceeded' => false,
                            'tvod_subscription_data' => $contentModel->tVodSubscriptions]);
                    }
                }
                if ($contentModel->access == config("constants.OTTCONTENTPREMIUM")) {
                    $verifiSubscriptionPlan = $this->HasSubscriptionPlan();
                    if ($verifiSubscriptionPlan) {
                        if ($this->isDeviceVerified($request)) {
                            if ($this->isDeviceLimitExceeded()) {
                                return $this->successResponse('Device Limit Exceeded !!', null);
                            } else {
                                if ($this->isDeviceStreamLimitExceeded($request, $contentModel->id)) {
                                    return $this->successResponse('Device Stream Limit Exceeded !!', [
                                        'device_stream_limit_exceeded' => true,
                                        'subscription' => true
                                    ]);
                                }
                                $data = new SingleOttContentResource($contentModel);
                                return $this->successResponse('Data fetched Successfully', [
                                    'content_data' => $data,
                                    'subscription' => true,
                                    'device_stream_limit_exceeded' => false]);
                            }
                        } else {
                            return response()->json(['error' => 'Device not found make user log out.'], 420);
                        }
                    } else {
                        return $this->successResponse('No Subscription Plan Available!!', [
                            'content_data' => null,
                            'subscription' => false,
                            'device_stream_limit_exceeded' => false]);
                    }
                } else {
                    $data = new SingleOttContentResource($contentModel);
                    return $this->successResponse('Data fetched Successfully', [
                        'content_data' => $data,
                        'subscription' => Str::contains($request->path(), 'app/') ? $request->user('api')->activeSubscription()->exists(): true,
                        'device_stream_limit_exceeded' => false
                    ]);
                }
            } else {
                return $this->unauthorizedResponse('Unauthorized', null);
            }
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Get Related Contents
     */
    public function getRelatedContent(string $device, string $uuid): JsonResponse
    {
        try {
            $ottContentModel = OttContent::select('id', 'sub_category_id')->where('uuid', $uuid)->firstOrFail();

            $category_contents_collection = OttContent::where('sub_category_id', $ottContentModel->sub_category_id)
                ->where('uuid', '!=', $uuid)
                ->where('status', config(key: "constants.OTTCONTENTSTATUS.OTTCONTENTPUBLISHED",default:  'Published'))
                ->get();

            $responseData['single_content_related_contents'] = OttContentResource::collection($category_contents_collection);
            $responseData['fixed_related_contents'] = FrontendCustomContentSection::where('is_available_on_single_page', 1)
                ->with(['frontendCustomContentLimitedData' => function ($query) {
                    $query->with(['ottContent' => function ($query) {
                        $query->select('id', 'title', 'uuid', 'poster', 'access');
                    }]);
                }])->get();

            return $this->successResponse('Related Data Fetch Successfully', $responseData);
        } catch (ModelNotFoundException|QueryException|Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

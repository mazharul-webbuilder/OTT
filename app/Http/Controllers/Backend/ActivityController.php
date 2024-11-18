<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityDeleteRequest;
use App\Traits\ResponseTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    use ResponseTrait;
    public function __construct()
    {
        $this->middleware('permission:all-activity-log|activity-delete', ['only' => ['getAllActivities', 'activityDelete']]);
        $this->middleware('permission:activity-delete', ['only' => ['activityDelete']]);
    }

    /**
     * Get all activities
    */
    public function getAllActivities(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);

            // Fetch activities with eager loading to retrieve the related user
            $activities = Activity::with(['causer' => function ($query) {
                $query->select('id', 'name');
            }])->latest()->paginate($perPage);

            return $this->successResponse('Data fetched successfully', $activities);

        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Delete Activity
     */
    public function activityDelete(string $id): JsonResponse
    {
        try {
            $activity = Activity::findOrFail($id);

            $activity->delete();

            return $this->successResponse('Activity Deleted Successfully', null);

        } catch (\Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Delete multiple activities at a time
    */
    public function multipleDelete(ActivityDeleteRequest $request): JsonResponse
    {
        try {
            $activityIds = json_decode($request->input('activities_ids'));
            Activity::whereIn('id', $activityIds)->delete();
            return $this->successResponse('Deleted successfully', null);
        }catch (QueryException|\Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

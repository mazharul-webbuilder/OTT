<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\UserActivitySync;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class WatchListController extends Controller
{

    /**
     * User Watch List
     */
    public function watchList(Request $request): JsonResponse
    {
        try {
            $watch_history = UserActivitySync::with(['content' => function ($q) {
                $q->select('id', 'uuid', 'title', 'short_title', 'poster', 'thumbnail_portrait', 'thumbnail_landscape', 'access',);
            }])->where('user_id', Auth::guard('api')->id())
                ->select('id', 'content_id', 'last_watch_time')
                ->get();

            return $this->successResponse('Data fetched successfully', $watch_history);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }


    /**
     * Remove user watch history by id
     */
    public function watchListRemove(string $device,  $id): JsonResponse
    {
        try {
            $user_watch_history = UserActivitySync::findOrFail($id);

            $user_watch_history->delete();

            return $this->successResponse('Data Deleted Successfully', null);
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }


    /**
     * Remove user all watchlist
     */
    public function watchListAllRemove(string $device): JsonResponse
    {
        try {
            UserActivitySync::where('user_id', Auth::guard('api')->id())->delete();

            return $this->successResponse('Data Deleted Successfully', null);

        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

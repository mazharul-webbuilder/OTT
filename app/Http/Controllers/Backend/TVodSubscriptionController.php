<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OttContent;
use App\Models\TVodSubscription;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TVodSubscriptionController extends Controller
{
    use ResponseTrait;

    /**
     * Get all T_vod subscription
    */
    
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input(key: 'per_page', default: 10);

            $query = TVodSubscription::with(relations: ['ottContent:id,uuid,title,short_title'])->orderBy(column: 'id', direction: 'desc');

            if ($request->filled(key: 'query_string')){
                // Search in own table
                $query = getSearchQuery($query, $request->input('query_string'), 'ticket_title', 'description');
                // Search in relation
                $query->orWhereHas('ottContent', function ($q) use ($request){
                    $queryString = $request->input('query_string');
                    $q->where('uuid', 'like', "%$queryString%")
                        ->orWhere('title', 'like', "%$queryString%");
                });
            }
            return $this->successResponse('Data fetched successfully', $query->paginate($perPage));

        } catch (QueryException $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Store new t_vod
    */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'ticket_title' => 'required|string|max:255',
                'price' => 'required|numeric',
                'access_limit' => 'integer',
                'description' => 'nullable|string',
                'ticket_duration_hour' => 'integer',
                'device_limit' => 'nullable|integer',
                'is_ticket_duration_in_hour' => 'boolean',
                'ott_content_id' => 'required|exists:ott_contents,id|unique:t_vod_subscriptions,ott_content_id'
            ]);

            $tVodSubscription = TVodSubscription::create($validatedData);

            OttContent::where('id', $request->input('ott_content_id'))->update(['is_tvod_available' => 1]);

            return $this->successResponse('Data stored successfully', $tVodSubscription);

        } catch (ValidationException|Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Show details
    */
    public function show(string $id): JsonResponse
    {
        try {
            $tVodSubscription = TVodSubscription::with(relations: ['ottContent:id,uuid,title,short_title'])->findOrFail($id);

            return $this->successResponse('Data fetched successfully', $tVodSubscription);
        }catch (ModelNotFoundException|Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Update t_vod
    */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'ticket_title' => 'sometimes|required|string|max:255',
                'price' => 'sometimes|required|numeric',
                'access_limit' => 'sometimes|integer',
                'description' => 'nullable|string',
                'ticket_duration_hour' => 'sometimes|integer',
                'device_limit' => 'nullable|integer',
                'is_ticket_duration_in_hour' => 'sometimes|boolean',
            ]);

            $tVodSubscription = TVodSubscription::findOrFail($id);

            $tVodSubscription->update($validatedData);

            return $this->successResponse('Data updated successfully', $tVodSubscription->with(relations: ['ottContent:id,uuid,title,short_title'])->get());

        }catch (ValidationException|Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Delete t_vod
    */
    public function destroy(string $id): JsonResponse
    {
        try {
            $tVodSubscription = TVodSubscription::findOrFail($id);

            $tVodSubscription->delete();

            return $this->successResponse('Data deleted successfully', null);

        }catch (ModelNotFoundException|Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

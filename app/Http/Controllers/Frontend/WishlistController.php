<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\WishListResource;
use App\Models\OttContent;
use App\Models\User;
use App\Models\WishList;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    /**
     * Get User Wishlist
     */
    public function getWishList(string $device): JsonResponse
    {
        try {
            $userId = Auth::guard(name: 'api')->id();

            $userModel = User::with(relations: 'wishLists')->where(column: 'id', operator: $userId)->firstOrFail();

            $responseData = WishListResource::collection(resource: $userModel->wishLists);

            return $this->successResponse(message: 'Data Fetch Successfully', data:  $responseData);

        } catch (ModelNotFoundException|Exception $exception) {
            return $this->errorResponse(message: $exception->getMessage(), data: null);
        }
    }

    /**
     * Content Adds to wishlist
     */
    public function addWishList(Request $request): JsonResponse
    {
        // Validate User Input
        $rules = array(
            'content_id' => ['required', Rule::exists('ott_contents', 'uuid')],
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null);
        }
        // Process with valid data
        $content_id = OttContent::where('uuid', $request->input('content_id'))->value('id');

        $data = [
            'user_id' => Auth::guard('api')->id(),
            'content_id' => $content_id,
        ];

        try {
            if (DB::table('wish_lists')->where('user_id', $data['user_id'])->where('content_id', $content_id)->exists()) {
                return $this->successResponse('Already added to wishList', null);
            }
            $wishList = WishList::create($data);

            return $this->successResponse('Data Fetch Successfully', $wishList);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Add content to user wishlist
    */
    public function addToWishlist(Request $request): JsonResponse
    {
        // Validate data
        $validator = Validator::make($request->all(), [
            'content_id' => ['required', Rule::exists('ott_contents', 'id')]
        ], ['content_id.exists' => 'Content id is not valid']);
        if ($validator->fails()){
            return $this->errorResponse($validator->messages(), null);
        }
        // Process with valid data
        try {
            $data = $validator->validated();
            $data['user_id'] = $this->getUserId();

            // Check is user already added to their wishlist
            if (DB::table('wish_lists')->where('user_id', $this->getUserId())->where('content_id', $data['content_id'])->exists()){
                throw new \Exception('Content is already in your wishlist');
            }

            WishList::create($data);

            return $this->successResponse('Content added to wishlist', null);

        } catch (QueryException|\Exception $exception){
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    /**
     * Remove single Wishlist
     */
    public function removeFromWishList(string $device, int $id): JsonResponse
    {
        try {
            $wishList = WishList::findOrFail($id);

            // authorization
            $this->authorizeForUser(user:\request()->user('api'),ability: 'delete', arguments: $wishList);

            $wishList->delete();

            return $this->successResponse('Removed From wishList', null);

        } catch (ModelNotFoundException  $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }


    /**
     * Remove user all wishlist
     */
    public function removeAllFromWishList($device): JsonResponse
    {
        try {
            WishList::where('user_id', Auth::guard('api')->user()->id)->delete();

            return $this->successResponse('Removed All From wishList', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Return Auth User
    */
    private function getUserId(): int|string|null
    {
        return Auth::guard('api')->id();
    }
}

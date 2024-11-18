<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PhotoGalary;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class PhotoGalleryController extends Controller
{
    /**
     * Get Photo Gallery
    */
    public function getGallery(string $device): JsonResponse
    {
        try {
            $photoGalleryCollection = PhotoGalary::where('type', PhotoGalary::GALARY)->get();

            return $this->successResponse(message: 'Successfully Fetched', data:  $photoGalleryCollection);

        } catch (QueryException $exception) {
            return $this->errorResponse(message: $exception->getMessage(), data: null);
        }
    }

    /**
     * Get family members
    */
    public function getFamilyMembers(string $device): JsonResponse
    {
        try {
            $photoGalleryCollection = PhotoGalary::where('type', PhotoGalary::FAMILY_MEMBERS)->get();

            return $this->successResponse(message: 'Successfully Fetched',data:  $photoGalleryCollection);
        } catch (QueryException $exception) {
            return $this->errorResponse(message: $exception->getMessage(), data: null );
        }
    }


    /**
     * Get a single family member gallery
    */
    public function singleFamilyGallery(string $device, int $id): JsonResponse
    {
        try {
            $data = PhotoGalary::findOrFail($id);

            return $this->successResponse(message: 'Successfully Fetched', data: $data);

        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse(message: "No data found with this id " . implode(',', $exception->getIds()), data: null);
        }
    }
}

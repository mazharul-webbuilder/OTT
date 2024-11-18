<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPasswordUpdateRequest;
use App\Http\Requests\AdminProfileUpdateRequest;
use App\Models\Admin;
use App\Traits\ResponseTrait;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    use ResponseTrait, Media;
    public function __construct()
    {
        //        $this->middleware('permission:admin-profile-get|admin-profile-set', ['only' => ['getAdminProfile']]);
        //        $this->middleware('permission:admin-profile-set', ['only' => ['setAdminProfile']]);
    }

    /**
     * Get Admin profile info
     */
    public function getAdminProfile(): JsonResponse
    {
        $admin = auth()->guard('admin')->user();
        if (!is_null($admin)) {
            return $this->successResponse('Data fetched successfully', $admin->load(['roles.permissions']));
        }
        return $this->unauthorizedResponse('Unauthorized', null);
    }

    /**
     * Set admin profile info
     */
    public function setAdminProfile(AdminProfileUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $admin = Admin::findOrFail($id);

            if (!is_null($admin)) {
                $admin->name = $request->input('name');
                /*Store image if it has image*/
                if ($file = $request->file('avatar')) {
                    $path = $request->input('path');
                    $fileData = $this->uploads($file, $path);
                    $request->image = $fileData['fileName'];
                    $admin->avatar = $request->image;
                }
                $admin->save();
                return $this->successResponse('Data Updated successfully', $admin);
            } else {
                return $this->errorResponse('Invalid admin id', null);
            }
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }


    /**
     * Update Admin Password
     */
    public function updatePassword(AdminPasswordUpdateRequest $request)
    {
        try {
            $admin = auth()->guard('admin')->user();
            if ($admin) {
                $admin->password = Hash::make($request->password);
                $admin->save();

                
                return $this->successResponse('Password change successfully', null);
            }
        } catch (QueryException | \Exception $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }
}

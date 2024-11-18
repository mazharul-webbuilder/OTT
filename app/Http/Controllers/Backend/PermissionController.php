<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Permission::oldest();

        return $this->successResponse(message: 'Data fetched successfully', data: $query->get());
    }
}

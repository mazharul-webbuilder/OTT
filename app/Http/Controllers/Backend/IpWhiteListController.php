<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\IpWhiteListResource;
use App\Models\IpWhiteList;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IpWhiteListController extends Controller
{
    use ResponseTrait;
    public function __construct()
    {
        $this->middleware('permission:ip-whitelist-list|ip-whitelist-create|ip-whitelist-edit|ip-whitelist-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:ip-whitelist-create', ['only' => ['store']]);
        $this->middleware('permission:ip-whitelist-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:ip-whitelist-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 10);

            $query =  IpWhiteList::latest();
            if ($request->filled('query_string')){
                $query = getSearchQuery($query, $request->input('query_string'), 'ip');
            }
            return $this->successResponse('Data fetched Successfully', $query->paginate($perPage));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $rules = array(
            'ip' => 'required|ip',
            'reason' => 'nullable|string|min:5'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
           return $this->errorResponse($validator->errors(), null);
        }
        try {
            $data =  IpWhiteList::create($request->all());

            return $this->successResponse('Data stored successfully', new IpWhiteListResource($data));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            if (!is_numeric($id)) {
                return $this->errorResponse("Invalid Id", null);
            }
            $data =  IpWhiteList::find($id);
            if (is_null($data)) {
                return $this->errorResponse("No data found with this id", null);
            }
            return $this->successResponse('Data fetched Successfully', IpWhiteListResource::make($data));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $rules = array(
            'ip' => 'required|ip',
            'reason' => 'nullable|string|min:5'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return $this->errorResponse($validator->errors(), null);
        }
        try {
            $data = IpWhiteList::find((string)$id);
            $data->update($request->all());
            return $this->successResponse('Data updated Successfully', IpWhiteListResource::make($data));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $data =  IpWhiteList::where('id', $id)->delete();

            return $this->successResponse('Data Deleted Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\IpBlackListResource;
use App\Models\IpBlackList;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IpBlackListController extends Controller
{
    use ResponseTrait;
    public function __construct()
    {
        $this->middleware('permission:ip-blacklist-list|ip-blacklist-create|ip-blacklist-edit|ip-blacklist-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:ip-blacklist-create', ['only' => ['store']]);
        $this->middleware('permission:ip-blacklist-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:ip-blacklist-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage =  (int) $request->input('per_page', 10);

            $query =  IpBlackList::latest();
            if ($request->filled('query_string')) {
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
            'ip' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null);
        }
        try {
            $data =  IpBlackList::create($request->all());

            return $this->successResponse('Data fetched Successfully', new IpBlackListResource($data));

        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data =  IpBlackList::find($id);

            return $this->successResponse('Data fetched Successfully', new IpBlackListResource($data));
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
            'ip' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null);
        }
        try {
            $ipBlackList = IpBlackList::find($id);
            if (is_null($ipBlackList)) {
                return $this->errorResponse("Invalid Id", null);
            }
            $ipBlackList->update($request->all());

            return $this->successResponse('Data updated Successfully', new IpBlackListResource($ipBlackList));
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
            $data =  IpBlackList::where('id', $id)->delete();

            return $this->successResponse('Data Deleted Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

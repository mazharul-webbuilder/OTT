<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentOwnerResource;
use App\Models\ContentOwners;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;

class ContentOwnerController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:content-owner-list|content-owner-create|content-owner-edit|content-owner-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:content-owner-create', ['only' => ['store']]);
        $this->middleware('permission:content-owner-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:content-owner-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = $request->filled('per_page') ? $request->input('per_page') : 10;

            $query =  ContentOwners::latest();

            if ($request->filled('query_string')) {
                $query = getSearchQuery($query, $request->input('query_string'), 'title', 'country');
            }

            return $this->successResponse('Data fetched Successfully', $query->paginate($per_page));
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
            'title' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null);
        }
        try {
            $data =  ContentOwners::create($request->all());
            return $this->successResponse('Data created Successfully', new ContentOwnerResource($data));
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
            $data =  ContentOwners::find($id);
            return $this->successResponse('Data fetched Successfully', new ContentOwnerResource($data));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            if($request->has('id')){
                return $this->errorResponse("ID can not be Updated!",null);
            }
            $contentOwner = ContentOwners::find($id);
            if (is_null($contentOwner)) {
                return $this->errorResponse('Invalid content owner id', null);
            }
            $contentOwner->update($request->all());
            $data = $contentOwner;
            DB::commit();
            return $this->successResponse('Data updated Successfully', new ContentOwnerResource($data));
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $data =  ContentOwners::where('id', $id)->delete();

            if(!$data) {
                return $this->errorResponse("Id not found", null);
            }

            return $this->successResponse('Data Deleted Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Response content owners id and title
    */
    public function getAllOwners(): JsonResponse
    {
        $data =  ContentOwners::select('id','title')->get();

        return $this->successResponse('Data fetched Successfully', $data);
    }
}

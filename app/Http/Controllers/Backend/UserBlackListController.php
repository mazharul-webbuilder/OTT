<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserBlackList;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserBlackListController extends Controller
{
    use ResponseTrait;
    public function __construct()
    {
        $this->middleware('permission:user-black-list-list|user-black-list-create|user-black-list-edit|user-black-list-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-black-list-create', ['only' => ['store']]);
        $this->middleware('permission:user-black-list-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:user-black-list-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 10);

            $query =  UserBlackList::latest();
            if ($request->filled('query_string')) {
                $query = getSearchQuery($query, $request->input('query_string'), 'user_id');
            }

            return $this->successResponse('Data fetched Successfully', $query->paginate($perPage));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request): JsonResponse
    {
        $rules = array(
            'user_id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
            // return response()->json(array($validator->errors()));
        }
        try {
            $data =  UserBlackList::create($request->all());
            return $this->successResponse('Data fetched Successfully', $data);
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
            $data =  UserBlackList::findOrfail($id);
            return $this->successResponse('Data fetched Successfully', $data);
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
            'user_id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null);
        }
        try {
            UserBlackList::where('id', $id)->update($request->all());
            $data =  UserBlackList::where('id', $id)->first();
            return $this->successResponse('Data updated Successfully', $data);
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
            $data =  UserBlackList::where('id', $id)->delete();
            return $this->successResponse('Data Deleted Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

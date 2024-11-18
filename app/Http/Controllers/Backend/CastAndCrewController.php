<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\CastAndCrewResource;
use App\Models\CastAndCrew;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CastAndCrewController extends Controller
{
    use Media, ResponseTrait;

    public function __construct()
    {
        $this->middleware('permission:cast-crew-list|cast-crew-create|cast-crew-edit|cast-crew-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:cast-crew-create', ['only' => ['store']]);
        $this->middleware('permission:cast-crew-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:cast-crew-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = $request->filled('per_page') ? $request->per_page : 10;

            $query =  CastAndCrew::oldest();
            if ($request->filled('query_string')) {
                $query = getSearchQuery($query, $request->input('query_string'), 'name');
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
            'name' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null);
        }
        try {
            if ($file = $request->file('photo')) {
                $path = $request->path;
                $fileData = $this->uploads($file, $path);
                $request['image'] = $fileData['fileName'];
            }else{
                $request->image = null;
            }
            unset($request['photo']);

            $data =  CastAndCrew::create($request->except('photo'));
            return $this->successResponse('Data created Successfully', new CastAndCrewResource($data));
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
            $data =  CastAndCrew::find($id);
            return $this->successResponse('Data fetched Successfully', new CastAndCrewResource($data));
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null);
        }
        DB::beginTransaction();
        try {
            $data = CastAndCrew::find($request->id);

            if(is_null($data)){
                return $this->errorResponse("No content Found With this ID!",null);
            }
            if ($file = $request->file('photo')) {
                $path = $request->path;
                $fileData = $this->uploads($file, $path);
                $request['image'] = $fileData['fileName'];
                CastAndCrew::where('id', $request->id)->update($request->except(['photo','id']));
            }else{
                CastAndCrew::where('id', $request->id)->update($request->except('id'));
            }
            $data = CastAndCrew::where('id', $request->id)->first();
            DB::commit();
            return $this->successResponse('Data updated Successfully', new CastAndCrewResource($data));
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
            $data =  CastAndCrew::where('id', $id)->delete();
            return $this->successResponse('Data Deleted Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function getCastAndCrew(): JsonResponse
    {
        $data =  CastAndCrew::select('id','name')->get();

        return $this->successResponse('Data fetched Successfully', $data);
    }
}

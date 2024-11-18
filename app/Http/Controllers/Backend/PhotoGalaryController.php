<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Models\PhotoGalary;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PhotoGalaryController extends Controller
{
    use Media, ResponseTrait;
    public function __construct()
    {
        $this->middleware('permission:photo-gallery-list|photo-gallery-create|photo-gallery-edit|photo-gallery-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:photo-gallery-create', ['only' => ['store']]);
        $this->middleware('permission:photo-gallery-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:photo-gallery-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->has('per_page')) {
                $per_page = $request->per_page;
             }else{
                $per_page = 10;
             }
            $data =  PhotoGalary::paginate($per_page);
            return $this->successResponse('Data fetched Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'type' => 'required',
            'photo' => 'required',
            'title' =>'required|string'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return  $this->validationError($validator->errors(), null, null);
        }
        try {
            if($request['type'] == PhotoGalary::FAMILY_MEMBERS){
                if($request['description'] == null){
                    return $this->validationError("Description Must need", null, null);
                }
            }
            if($request['type'] == PhotoGalary::GALARY){

            }
            if ($file = $request->file('photo')) {
                $path = $request->path;
                $fileData = $this->uploads($file, $path);
                $request['image'] = $fileData['fileName'];
            }
            unset($request['photo']);

            $data =  PhotoGalary::create($request->except('photo'));
            return $this->successResponse('Data created Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data =  PhotoGalary::where('id', $id)->first();
            return $this->successResponse('Data fetched Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
            // return response()->json(array($validator->errors()));
        }
        DB::beginTransaction();
        try {
            $id = $request->id;
            $data = PhotoGalary::where('id', $id)->first();

            if($data == null){
                return $this->errorResponse("No content Found With this ID!",null);
            }
            if ($file = $request->file('photo')) {
                $path = $request->path;
                $fileData = $this->uploads($file, $path);
                $request['image'] = $fileData['fileName'];
                PhotoGalary::where('id', $id)->update($request->except(['photo','id']));
            }else{
                PhotoGalary::where('id', $id)->update($request->except('id'));
            }
            $data = PhotoGalary::where('id', $id)->first();
            DB::commit();
            return $this->successResponse('Data updated Successfully', $data);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data =  PhotoGalary::where('id', $id)->delete();
            return $this->successResponse('Data Deleted Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

}


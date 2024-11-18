<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\SelectedCategoryContentResource;
 
use App\Models\SelectedCategoryContent;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SelectedCategoryContentController extends Controller
{
    use Media, ResponseTrait;

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
            $data =   SelectedCategoryContent::latest()->paginate($per_page); 
            return $this->successResponse('Data fetched Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
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
            'root_category_id' => 'required|numeric',
            'ott_content_id' => 'required|numeric',
            'is_featured' => 'required|numeric',
        ); 

        $validator = Validator::make($request->all(), $rules); 
        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null); 
        }

        try { 
            $type = new SelectedCategoryContent(); 
            $type->root_category_id = $request->root_category_id;
            $type->ott_content_id = $request->ott_content_id;
            $type->is_featured = $request->is_featured;
            $type->save(); 

            return $this->successResponse('Data store successfully', $type);
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
            $customContent = SelectedCategoryContent::findOrFail($id);

            $data = new SelectedCategoryContentResource($customContent);

            return $this->successResponse('Data Fetch successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }

    }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $rules = array(
            'root_category_id' => 'required|numeric',
            'ott_content_id' => 'required|numeric',
            'is_featured' => 'required|numeric',
        ); 

        $validator = Validator::make($request->all(), $rules); 
        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null); 
        }

        try { 
            $type =  SelectedCategoryContent::find($id); 
            $type->root_category_id = $request->root_category_id;
            $type->ott_content_id = $request->ott_content_id;
            $type->is_featured = $request->is_featured;
            $type->update(); 

            return $this->successResponse('Data updated successfully', $type);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

        SelectedCategoryContent::findOrFail($id)->delete();

        return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

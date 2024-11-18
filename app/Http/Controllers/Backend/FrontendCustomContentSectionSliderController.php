<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\FrontendCustomContentSectionSliderResource;
use App\Http\Resources\Backend\FrontendCustomSliderResource;
use App\Http\Resources\Backend\OttSliderResource;
use App\Http\Resources\Backend\SubCategoryResource;
use App\Models\FrontendCustomContent;
use App\Models\FrontendCustomContentSectionSlider;
use App\Models\FrontendCustomSlider;
use App\Models\OttSlider;
use App\Models\SubCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FrontendCustomContentSectionSliderController extends Controller
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
            $data =   FrontendCustomContentSectionSlider::where('status', 'Published')->latest()->paginate($per_page); 
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
            'title' => 'required',
            'frontend_custom_content_type_id' => 'required|numeric',
            'status' => 'required',
            'order' => 'required|numeric',
            'image' => 'required|mimes:jpeg,png,jpg,gif', 
        );

        $validator = Validator::make($request->all(), $rules); 
        if ($validator->fails()) { 
            return $this->errorResponse("Validation Error", $validator->errors(), null);  
        }
        if ($file = $request->file('image')) {
            $path = $request->path;
            $fileData = $this->uploads($file, $path);
            // dd($fileData);
            // $media = Gravatar::create([
            //            'media_name' => $fileData['fileName'],
            //            'media_type' => $fileData['fileType'],
            //            'media_path' => $fileData['fileName'],
            //            'media_size' => $fileData['fileSize']
            //         ]);
            $request->image = $fileData['fileName'];
        }
        try {

            $ottSlider = new FrontendCustomContentSectionSlider(); 
            $ottSlider->title = $request->title; 
            $ottSlider->image = $request->image;
            $ottSlider->frontend_custom_content_type_id = $request->frontend_custom_content_type_id;
            $ottSlider->content_url = $request->content_url;
            $ottSlider->status = $request->status;
            $ottSlider->order = $request->order; 
            $ottSlider->save();

            return $this->successResponse('Data store successfully', $ottSlider);
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
            $ottSlider = FrontendCustomContentSectionSlider::findOrFail($id);

            $data = new FrontendCustomContentSectionSliderResource($ottSlider);

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
            'title' => 'required',
            'frontend_custom_content_type_id' => 'required|numeric',
            'status' => 'required',
            'order' => 'required|numeric',
            'image' => 'mimes:jpeg,png,jpg,gif', 
        );

        $validator = Validator::make($request->all(), $rules); 
        if ($validator->fails()) { 
            return $this->errorResponse("Validation Error", $validator->errors(), null);  
        }
        if ($file = $request->file('image')) {
            $path = $request->path;
            $fileData = $this->uploads($file, $path);
            // dd($fileData);
            // $media = Gravatar::create([
            //            'media_name' => $fileData['fileName'],
            //            'media_type' => $fileData['fileType'],
            //            'media_path' => $fileData['fileName'],
            //            'media_size' => $fileData['fileSize']
            //         ]);
            $request->image = $fileData['fileName'];
        }
        try {

            $ottSlider =  FrontendCustomContentSectionSlider::findOrFail($id); 
            $ottSlider->title = $request->title;  

            if ($request->has('image')) {
                $ottSlider->image = $request->image;
            } 

            $ottSlider->frontend_custom_content_type_id = $request->frontend_custom_content_type_id;
            $ottSlider->content_url = $request->content_url;
            $ottSlider->status = $request->status;
            $ottSlider->order = $request->order; 
            $ottSlider->update(); 
            return $this->successResponse('Data updated successfully', $ottSlider);
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

        FrontendCustomContentSectionSlider::findOrFail($id)->delete();

        return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
        
    }
}

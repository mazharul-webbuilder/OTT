<?php

namespace App\Http\Controllers\Backend;


use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\FrontendCustomSliderResource;
use App\Http\Resources\Backend\OttSliderResource;
use App\Http\Resources\Backend\SubCategoryResource;
use App\Models\FrontendCustomContent;
use App\Models\FrontendCustomSlider;
use App\Models\OttSlider;
use App\Models\SubCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class FrontendCustomSliderController extends Controller
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
            $data =   FrontendCustomSlider::where('is_active', 1)->latest()->paginate($per_page); 
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
            'slider_type_sub_title' => 'required',
            'press_action_slug' => 'required',
            'press_action_slug_activity' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,webp', 
        );

        $validator = Validator::make($request->all(), $rules); 
        if ($validator->fails()) {
            return $validator->errors();
            // return response()->json(array($validator->errors()));    
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

            $ottSlider = new FrontendCustomSlider(); 
            $ottSlider->slider_type_slug = $request->slider_type_slug; 
            $ottSlider->image = $request->image;
            $ottSlider->slider_type_title = $request->slider_type_title;
            $ottSlider->slider_type_sub_title = $request->slider_type_sub_title;
            $ottSlider->press_action_slug = $request->press_action_slug;
            $ottSlider->press_action_slug_activity = $request->press_action_slug_activity;
            $ottSlider->is_active = $request->is_active;
            $ottSlider->sorting_order = $request->sorting_order; 
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
            $ottSlider = FrontendCustomSlider::findOrFail($id);

            $data = new FrontendCustomSliderResource($ottSlider);

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
            'slider_type_sub_title' => 'required',
            'press_action_slug' => 'required',
            'press_action_slug_activity' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif', 
        );

        $validator = Validator::make($request->all(), $rules); 
        if ($validator->fails()) {
            return $validator->errors();
            // return response()->json(array($validator->errors()));    
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

            $ottSlider =  FrontendCustomSlider::findOrFail($id); 
            $ottSlider->slider_type_slug = $request->slider_type_slug; 

            if ($request->has('image')) {
                $ottSlider->image = $request->image;
            } 
            $ottSlider->slider_type_title = $request->slider_type_title;
            $ottSlider->slider_type_sub_title = $request->slider_type_sub_title;
            $ottSlider->press_action_slug = $request->press_action_slug;
            $ottSlider->press_action_slug_activity = $request->press_action_slug_activity;
            $ottSlider->is_active = $request->is_active;
            $ottSlider->sorting_order = $request->sorting_order; 
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

        FrontendCustomSlider::findOrFail($id)->delete();

        return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

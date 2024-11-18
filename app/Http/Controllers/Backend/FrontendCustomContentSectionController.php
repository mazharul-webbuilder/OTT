<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\FrontendCustomContentResource;
use App\Http\Resources\Backend\FrontendCustomContentSectionResource;
use App\Http\Resources\Backend\FrontendCustomSliderResource;
use App\Http\Resources\Backend\OttSliderResource;
use App\Http\Resources\Backend\SubCategoryResource;
use App\Models\FrontendCustomContent;
use App\Models\FrontendCustomContentSection;
use App\Models\FrontendCustomSlider;
use App\Models\OttSlider;
use App\Models\SubCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FrontendCustomContentSectionController extends Controller
{
    use Media, ResponseTrait;
    function __construct()
    {
         $this->middleware('permission:frontend-custom-content-section-list|frontend-custom-content-section-create|frontend-custom-content-section-edit|frontend-custom-content-section-delete', ['only' => ['index', 'show']]);
         $this->middleware('permission:frontend-custom-content-section-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:frontend-custom-content-section-edit', ['only' => ['show', 'update']]);
         $this->middleware('permission:frontend-custom-content-section-delete', ['only' => ['destroy']]);
         $this->middleware('permission:frontend-custom-content-section-add-content', ['only' => ['addContent', 'AddCustomContents']]);
         $this->middleware('permission:edit-frontend-custom-content-section-contents', ['only' => ['editCustomContent', 'updateCustomContent']]);
         $this->middleware('permission:frontend-custom-content-section-content-delete', ['only' => ['destroyCustomContent']]);
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
            $data =   FrontendCustomContentSection::orderBy('order','asc')->paginate($per_page);
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
            'content_type_slug' => 'required|numeric|unique:frontend_custom_content_sections',
            'content_type_title' => 'required',
            'order' => 'integer'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationError("Validation Error", null, $validator->getMessageBag());
        }

        try {
            $customContent = new FrontendCustomContentSection();
            $customContent->content_type_slug = $request->content_type_slug;
            $customContent->content_type_title = $request->content_type_title;
            $customContent->more_info_slug = $request->more_info_slug;
            $customContent->is_available_on_single_page = $request->is_available_on_single_page;
            $customContent->is_featured_section = $request->is_featured_section;
            $customContent->order = $request->order;
            $customContent->save();

            return $this->successResponse('Data store successfully', $customContent);
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
            $customContent = FrontendCustomContentSection::findOrFail($id);

            $data = new FrontendCustomContentSectionResource($customContent);

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
            'content_type_slug' => 'required|numeric',
            'content_type_title' => 'required',
        );


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationError("Validation Error", null, $validator->getMessageBag());
        }

        try {
            $customContent =  FrontendCustomContentSection::findOrFail($id);
            if ($customContent->content_type_slug != $request->content_type_slug) {
                $is_exists = FrontendCustomContentSection::where('content_type_slug', $request->content_type_slug)->exists();
                if ($is_exists) {
                    return $this->errorResponse("Content type slug already taken", null, null);
                }
            }
            $customContent->content_type_slug = $request->content_type_slug;
            $customContent->content_type_title = $request->content_type_title;
            $customContent->more_info_slug = $request->more_info_slug;
            $customContent->is_available_on_single_page = $request->is_available_on_single_page;
            $customContent->is_featured_section = $request->is_featured_section;
            $customContent->order = $request->order;
            $customContent->update();

            return $this->successResponse('Data updated successfully', $customContent);
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

            FrontendCustomContentSection::findOrFail($id)->delete();

            return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

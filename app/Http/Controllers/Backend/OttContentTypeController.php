<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\FrontendCustomContentResource;
use App\Http\Resources\Backend\FrontendCustomContentSectionResource;
use App\Http\Resources\Backend\FrontendCustomSliderResource;
use App\Http\Resources\Backend\OttContentTypeResource;
use App\Http\Resources\Backend\OttSliderResource;
use App\Http\Resources\Backend\SubCategoryResource;
use App\Models\FrontendCustomContent;
use App\Models\FrontendCustomContentSection;
use App\Models\FrontendCustomSlider;
use App\Models\OttContentType;
use App\Models\OttSlider;
use App\Models\SubCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class OttContentTypeController extends Controller
{
    use Media, ResponseTrait;
    public function __construct()
    {
        $this->middleware('permission:ott-content-type-list|ott-content-type-create|ott-content-type-edit|ott-content-type-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:ott-content-type-create', ['only' => ['store']]);
        $this->middleware('permission:ott-content-type-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:ott-content-type-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        try {
            $data =  OttContentTypeResource::collection(OttContentType::latest()->paginate(10));

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
            'title' => 'required|string'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null);
        }

        try {
            $type = new OttContentType();
            $type->title = $request->title;
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
            $customContent = OttContentType::findOrFail($id);

            $data = new OttContentTypeResource($customContent);

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
            'title' => 'required|string'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null);
        }

        try {
            $type =  OttContentType::findOrFail($id);
            $type->title = $request->title;
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
        OttContentType::findOrFail($id)->delete();
        return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\OttSliderResource;
use App\Http\Resources\Backend\SubCategoryResource;
use App\Models\OttSlider;
use App\Models\SubCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OttSliderController extends Controller
{
    use Media, ResponseTrait;
    function __construct()
    {
        //  $this->middleware('permission:ott-slider-list|ott-slider-create|ott-slider-edit|ott-slider-delete', ['only' => ['index', 'show']]);
        //  $this->middleware('permission:ott-slider-create', ['only' => ['create', 'store']]);
        //  $this->middleware('permission:ott-slider-edit', ['only' => ['show', 'update']]);
        //  $this->middleware('permission:ott-slider-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
             $per_page = $request->filled('per_page') ? (int) $request->input('per_page') : 10;

            $data = OttSlider::with('rootCategory')->latest()->paginate($per_page);

            return $this->successResponse('Data fetched Successfully', $data);
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
            'image' => 'mimes:jpeg,png,jpg,gif,webp',
            'root_category_id' => 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null);
        }
        if ($file = $request->file('image')) {
            $path = $request->path;

            $fileData = $this->uploads($file, $path);

            $request->image = $fileData['fileName'];
        }
        if ($file2 = $request->file('landscape_image')) {
            $path2 = $request->path;

            $fileData2 = $this->uploads($file2, $path2);

            $request->landscape_image = $fileData2['fileName'];
        }
        try {

            $ottSlider = new OttSlider();
            $ottSlider->title = $request->title;
            $ottSlider->slug = Str::slug($request->title);
            $ottSlider->image = $request->image;
            $ottSlider->landscape_image = $request->landscape_image;
            $ottSlider->order = $request->order;
            $ottSlider->description = $request->description;
            $ottSlider->bottom_title = $request->bottom_title;
            $ottSlider->content_url = $request->content_url;
            $ottSlider->is_home = $request->is_home;
            $ottSlider->status = $request->status;
            $ottSlider->root_category_id = $request->root_category_id;
            $ottSlider->save();

            return $this->successResponse('Data store successfully', $ottSlider);
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
            $ottSlider = OttSlider::findOrFail($id);

            $data = new OttSliderResource($ottSlider);

            return $this->successResponse('Data Fetch successfully', $data);
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
            'title' => 'required',
            'root_category_id' => 'required|numeric',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), null);
        }

        if ($file = $request->file('image')) {
            $path = $request->path;
            $fileData = $this->uploads($file, $path);
            $request->image = $fileData['fileName'];
        }

        if ($file2 = $request->file('landscape_image')) {
            $path2 = $request->path;

            $fileData2 = $this->uploads($file2, $path2);

            $request->landscape_image = $fileData2['fileName'];
        }

        try {

            $ottSlider = OttSlider::findOrFail($id);
            // dd($ottSlider);
            $ottSlider->title = $request->title;
            $ottSlider->slug = Str::slug($request->title);

            if ($request->has('image')) {
                $ottSlider->image = $request->image;
            }
            if ($request->has('landscape_image')) {
                $ottSlider->landscape_image = $request->landscape_image;
            }

            $ottSlider->order = $request->order;
            $ottSlider->content_url = $request->content_url;
            $ottSlider->is_home = $request->is_home;
            $ottSlider->status = $request->status;
            $ottSlider->root_category_id = $request->root_category_id;
            $ottSlider->description = $request->description;
            $ottSlider->bottom_title = $request->bottom_title;

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

            OttSlider::findOrFail($id)->delete();

            return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

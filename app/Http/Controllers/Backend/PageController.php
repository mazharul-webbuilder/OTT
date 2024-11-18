<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\OttSliderResource;
use App\Http\Resources\Backend\PageResource;
use App\Http\Resources\Backend\SubCategoryResource;
use App\Models\OttSlider;
use App\Models\Page;
use App\Models\SubCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    use Media, ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = $request->filled('per_page') ? (int) $request->input('per_page') : 10;

            $data =  Page::latest()->paginate($per_page);

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
            'slug' => ['required', Rule::unique('pages', 'slug')],
            'short_description' => 'nullable',
            'content' => 'required',
            'thumbnail' => 'nullable|mimes:jpeg,png,jpg,gif,webp',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationError('Validation Error', null, $validator->errors());
        }
        if ($file = $request->file('thumbnail')) {
            $path = $request->path;
            $fileData = $this->uploads($file, $path);
            $request->thumbnail = $fileData['fileName'];
        }
        try {

            $page = new Page();
            $page->title = $request->title;
            $page->slug = Str::slug($request->slug);
            $page->thumbnail = $request->thumbnail;
            $page->short_description = $request->short_description;
            $page->content = $request->content;
            $page->is_published = $request->is_published;
            $page->version_number = $request->version_number;
            $page->save();

            return $this->successResponse('Data store successfully', $page);
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
            $page = Page::findOrFail($id);

            $data = new PageResource($page);

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
            'short_description' => 'nullable',
            'content' => 'required',
            'slug' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationError('Validation Error', null, $validator->errors());
        }
        if ($file = $request->file('thumbnail')) {
            $path = $request->path;
            $fileData = $this->uploads($file, $path);
            $request->thumbnail = $fileData['fileName'];
        }
        try {

            $page = Page::findOrFail($id);
            $page->title = $request->title;
            $page->slug = Str::slug($request->slug);

            if ($request->has('thumbnail')) {
                $page->thumbnail = $request->thumbnail;
            }

            $page->short_description = $request->short_description;
            $page->content = $request->content;
            $page->is_published = $request->is_published;
            $page->version_number = $request->version_number;
            $page->update();

            return $this->successResponse('Data store successfully', $page);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /*
     * Remove the specified resource from storage.
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Page::findOrFail($id)->delete();
            return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;


use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\SubCategoryResource;
use App\Http\Resources\Backend\SubSubCategoryResource;
use App\Models\OttContent;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubSubCategoryController extends Controller
{
    use Media, ResponseTrait;

    function __construct()
    {
         $this->middleware('permission:sub-sub-category-list|sub-sub-category-create|sub-sub-category-edit|sub-sub-category-delete', ['only' => ['index', 'show']]);
         $this->middleware('permission:sub-sub-category-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:sub-sub-category-edit', ['only' => ['show', 'update']]);
         $this->middleware('permission:sub-sub-category-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = $request->filled('per_page') ? (int) $request->input('per_page') : 10;

            $data = SubSubCategory::with(['subCategory','rootCategory'])->where('status', 'Published')->orderBy('order','asc')->paginate($per_page);

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
            'title' => 'required|unique:sub_sub_categories|max:255',
            'image' => 'mimes:jpeg,png,jpg,gif,webp',
            'root_category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
            'slug' => 'required|unique:sub_sub_categories',
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

            $subSubCategory = new SubSubCategory();
            $subSubCategory->title = $request->title;
            $subSubCategory->slug = $request->slug;
            $subSubCategory->image = $request->image;
            $subSubCategory->order = $request->order;
            $subSubCategory->seo_title = $request->seo_title;
            $subSubCategory->seo_description = $request->seo_description;
            $subSubCategory->status = $request->status;
            $subSubCategory->root_category_id = $request->root_category_id;
            $subSubCategory->sub_category_id = $request->sub_category_id;
            $subSubCategory->save();

            return $this->successResponse('Data store successfully', $subSubCategory);
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
            $subCategory = SubSubCategory::findOrFail($id);

            $data = new SubSubCategoryResource($subCategory);

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
            'root_category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
            'image' => 'mimes:jpeg,png,jpg,gif',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
            // return response()->json(array($validator->errors()));
        }

        $subSubCategory = SubSubCategory::findOrFail($id);

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
        }else{
            $request->image = $subSubCategory->image;
        }
        try {

            $subSubCategory->title = $request->title;
            $subSubCategory->slug = $request->slug;
            $subSubCategory->image = $request->image;
            $subSubCategory->order = $request->order;
            $subSubCategory->seo_title = $request->seo_title;
            $subSubCategory->seo_description = $request->seo_description;
            $subSubCategory->status = $request->status;
            $subSubCategory->root_category_id = $request->root_category_id;
            $subSubCategory->sub_category_id = $request->sub_category_id;
            $subSubCategory->update();

            return $this->successResponse('Data store successfully', $subSubCategory);
        } catch (Exception $e) {
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
            SubSubCategory::findOrFail($id)->delete();

            return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function filterSubCategory($id): JsonResponse
    {
        try {
            $sub_categories = SubCategory::where('root_category_id', $id)->get();
            return $this->successResponse('Data Fetched successfully', $sub_categories);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    public function filterSubCategoryWithContent($id)
    {
        try {

            $sub_categories = SubCategory::where('root_category_id', $id)->get();
            $contents = OttContent::where('status', 'Published')->where('root_category_id', $id)->get();

            $data['sub_categories'] = $sub_categories;
            $data['contents'] = $contents;
            return $this->successResponse('Data Fetched successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

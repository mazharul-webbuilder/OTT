<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\SubCategoryResource;
use App\Models\SubCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    use Media, ResponseTrait;
    function __construct()
    {
         $this->middleware('permission:sub-category-list|sub-category-create|sub-category-edit|sub-category-delete', ['only' => ['index', 'show']]);
         $this->middleware('permission:sub-category-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:sub-category-edit', ['only' => ['show', 'update']]);
         $this->middleware('permission:sub-category-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            // $subCategories = SubCategory::where('status','Published')->paginate(3);
            if ($request->has('per_page')) {
                $per_page = $request->per_page;
             }else{
                $per_page = 10;
             }

            $data =  SubCategory::with('rootCategory')->latest()->paginate($per_page);

            return $this->successResponse('Data fetched Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }


    public function subCategoriesList(Request $request)
    {
        try {

            $data =  SubCategoryResource::collection(SubCategory::with('rootCategory')->where('status', 'Published')->latest()->get());

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
            'title' => 'required|unique:sub_categories|max:255',
            'image' => 'mimes:jpeg,png,jpg,gif,webp',
            'root_category_id' => 'required|numeric',
            'slug' => 'required|unique:sub_categories|max:255',
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

            $subCategory = new SubCategory();
            $subCategory->title = $request->title;
            $subCategory->slug = $request->slug;
            $subCategory->image = $request->image;
            $subCategory->order = $request->order;
            $subCategory->seo_title = $request->seo_title;
            $subCategory->seo_description = $request->seo_description;
            $subCategory->status = $request->status;
            $subCategory->root_category_id = $request->root_category_id;
            $subCategory->save();

            return $this->successResponse('Data store successfully', $subCategory);
        } catch (Exception $e) {
            return $this->errorResponse("Something went wrong", null, null);
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
            $subCategory = SubCategory::findOrFail($id);

            $data = new SubCategoryResource($subCategory);

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
    public function update(Request $request)
    {
        $rules = array(
            'title' => 'required',
            'root_category_id' => 'required|numeric',
            'id' => 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
            // return response()->json(array($validator->errors()));
        }
        $id = $request->id;
        $subCategory = SubCategory::findOrFail($id);
        if(empty($subCategory)){
            return $this->errorResponse("Content Not Found",[]);
        }

        if ($file = $request->file('image')) {
            $path = $request->path;
            $fileData = $this->uploads($file, $path);
            $request->image = $fileData['fileName'];
        }else{
            $request->image = $subCategory->image;
        }
        try {

            $subCategory->title = $request->title!=null?$request->title:$subCategory->title;
            $subCategory->slug = $request->slug!=null?$request->slug:$subCategory->slug;
            $subCategory->image = $request->image!=null?$request->image:$subCategory->image;
            $subCategory->order = $request->order!=null?$request->order:$subCategory->order;
            $subCategory->seo_title = $request->seo_title!=null?$request->seo_title:$subCategory->seo_title;
            $subCategory->seo_description = $request->seo_description!=null?$request->seo_description:$subCategory->seo_description;
            $subCategory->status = $request->status!=null?$request->status:$subCategory->status;
            $subCategory->root_category_id = $request->root_category_id;
            $subCategory->update();

            return $this->successResponse('Data update successfully', $subCategory);
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
            subCategory::findOrFail($id)->delete();

            return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helper\Media;
use App\Http\Resources\Backend\RootCategoryResource as BackendRootCategoryResource;
use App\Http\Resources\Backend\RootCategoryResource;
use App\Models\RootCategory;
use App\Repositories\RootCategoryRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RootCategoryController extends Controller
{
    use Media, ResponseTrait;

    public $rootCategory;

    public function __construct(RootCategoryRepository $rootCategory)
    {
        $this->rootCategory = $rootCategory;
         $this->middleware('permission:root-category-list|root-category-create|root-category-edit|root-category-delete', ['only' => ['index', 'show']]);
         $this->middleware('permission:root-category-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:root-category-edit', ['only' => ['show', 'update']]);
         $this->middleware('permission:root-category-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $per_page = $request->filled('per_page') ? (int) $request->input('per_page') : 10;

            $data = $this->rootCategory->getAll($per_page);
            return $this->successResponse('Data fetched Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }


    public function rootCategoriesList()
    {

        try {
            $data = $this->rootCategory->getCategories();
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
            'title' => 'required|unique:root_categories|max:255',
            'image' => 'mimes:jpeg,png,jpg,gif,webp',
            'slug' => 'required|unique:root_categories'
        );

        $validator = Validator::make($request->all(), $rules);
        // $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationError('',null,$validator->errors());
        }
        try {

            if ($file = $request->file('image')) {
                $path = $request->path;
                $fileData = $this->uploads($file, $path);
                $request->image = $fileData['fileName'];
            }
            $rootCategory = new RootCategory();
            $rootCategory->title = $request->title;
            $rootCategory->slug =  $request->slug;
            $rootCategory->image = $request->image;
            $rootCategory->order = $request->order;
            $rootCategory->seo_title = $request->seo_title;
            $rootCategory->seo_description = $request->seo_description;
            $rootCategory->is_fixed = $request->is_fixed;
            $rootCategory->is_live_stream = $request->is_live_stream?? 0;
            $rootCategory->status = $request->status;
            $rootCategory->save();

            return $this->successResponse('Data store successfully', $rootCategory);
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
            $rootCategory = RootCategory::findOrFail($id);

            $data = new RootCategoryResource($rootCategory);

            return $this->successResponse('Data Fetch successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $requesteyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vb3R0YXBpLnRlc3QvYXBpL3YxL2FkbWluL2xvZ2luIiwiaWF0IjoxNzE1MDU3MDkzLCJleHAiOjE3MTc2NDkwOTMsIm5iZiI6MTcxNTA1NzA5MywianRpIjoidndaZElSVUVJREFoQVM0OSIsInN1YiI6IjEiLCJwcnYiOiJkZjg4M2RiOTdiZDA1ZWY4ZmY4NTA4MmQ2ODZjNDVlODMyZTU5M2E5In0.akIsOp-y0D7xawyo8KQ5_3xZeVnmoCwzusZ8pes1Wo0a
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = array(
            'title' => 'required',
            'slug' => 'required',
            'id'=>'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationError('',null,$validator->errors());
        }
        $id = $request->id;
        $rootCategory = RootCategory::findOrFail($id);
        if(empty($rootCategory)){
            return $this->errorResponse("Content Not Found",[]);
        }

        if ($file = $request->file('image')) {
            $path = $request->path;
            $fileData = $this->uploads($file, $path);
            $request->image = $fileData['fileName'];
        }else{
            $request->image = $rootCategory->image;
        }

        try {
            $rootCategory->title = $request->title!=null?$request->title:$rootCategory->title;
            $rootCategory->slug =  $request->slug!=null?$request->slug:$rootCategory->slug;
            $rootCategory->image = $request->image!=null?$request->image:$rootCategory->image;
            $rootCategory->order = $request->order!=null?$request->order:$rootCategory->order;
            $rootCategory->seo_title = $request->seo_title!=null?$request->seo_title:$rootCategory->seo_title;
            $rootCategory->seo_description = $request->seo_description!=null?$request->seo_description:$rootCategory->seo_description;
            $rootCategory->is_fixed = $request->is_fixed!=null?$request->is_fixed:$rootCategory->is_fixed;
            $rootCategory->is_live_stream = $request->is_live_stream?? 0;
            $rootCategory->status = $request->status!=null?$request->status:$rootCategory->status;
            $rootCategory->update();

            return $this->successResponse('Data Updated successfully', $rootCategory);
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
            RootCategory::findOrFail($id)->delete();

            return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

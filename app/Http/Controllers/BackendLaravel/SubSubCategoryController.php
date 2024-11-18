<?php

namespace App\Http\Controllers\BackendLaravel;


use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Models\OttContent;
use App\Models\RootCategory;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Traits\ResponseTrait;
use Exception;
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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $root_categories = RootCategory::orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        $sub_sub_categories = SubSubCategory::with('rootCategory', 'subCategory')->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        return view('admin.pages.sub_sub_category.create', compact('sub_sub_categories'))->with('root_categories', $root_categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $rules = array(
            'title' => 'required',
            'file' => 'mimes:jpeg,png,jpg,gif',
            'root_category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('sub-sub-category.create')
                ->withErrors($validator)
                ->withInput();
        }

        if ($file = $request->file('file')) {

            $path = "SubSubCategory/" . $request->slug . "/Images";
            $fileData = $this->uploads($file, $path);
            // dd($fileData);
            // $media = Gravatar::create([
            //            'media_name' => $fileData['fileName'],
            //            'media_type' => $fileData['fileType'],
            //            'media_path' => $fileData['filePath'],
            //            'media_size' => $fileData['fileSize']
            //         ]);
            $request['image'] = $fileData['fileName'];
        }
        try {

            $subSubCategory =  SubSubCategory::create($request->except(['token', 'file']));
            return redirect()->route('sub-sub-category.create')->with('message', 'Data Inserted Successfully');
        } catch (Exception $e) {
            return redirect()->route('sub-sub-category.create')->with('error', 'Something Went Wrong');
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
        $root_categories = RootCategory::orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        $sub_sub_category = SubSubCategory::where('id', $id)->first();
        $selected_sub_categories = SubCategory::where('root_category_id', $sub_sub_category->root_category_id)->get();

        return view('admin.pages.sub_sub_category.edit', compact('root_categories', 'selected_sub_categories'))->with('data', $sub_sub_category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return   $request->all();
        $subCategory = SubSubCategory::findOrFail($id);
        $rules = array(
            'title' => 'required',
            'root_category_id' => 'required|numeric',
            'sub_category_id' => 'required|numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('sub-sub-category.show', $id)
                ->withErrors($validator)
                ->withInput();
        }
        if ($file = $request->file('file')) {
            $path = "SubSubCategory/" . $request->slug . "/Images";

            $fileData = $this->uploads($file, $path);
            // dd($fileData);
            // $media = Gravatar::create([
            //            'media_name' => $fileData['fileName'],
            //            'media_type' => $fileData['fileType'],
            //            'media_path' => $fileData['fileName'],
            //            'media_size' => $fileData['fileSize']
            //         ]);
            $request->image = $fileData['fileName'];
        } else {
            $request->image = $subCategory->image;
        }
        // return $request->all();
        try {


            $subCategory->title = $request->title;
            $subCategory->slug = $request->slug;
            $subCategory->image = $request->image;
            $subCategory->order = $request->order;
            $subCategory->seo_title = $request->seo_title;
            $subCategory->seo_description = $request->seo_description;
            $subCategory->root_category_id = $request->root_category_id;
            $subCategory->sub_category_id = $request->sub_category_id;
            $subCategory->status = $request->status;
            $subCategory->update();

            return redirect()->route('sub-sub-category.show', $id)->with('message', 'Data Updated Successfully');
        } catch (Exception $e) {
            return redirect()->route('sub-sub-category.show', $id)->with('error', 'Something Went Wrong');
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
            SubSubCategory::find($id)->delete();
            return redirect()->route('sub-sub-category.create')->with('message', 'Data Deleted Successfully');
        } catch (Exception $e) {
            return $e;
        }
    }


    //filter sub_category by root_category_id 
    public function filterSubCategory($id)
    {
        $sub_categories = SubCategory::where('root_category_id', $id)->get();
        return response()->json(['sub_categories' => $sub_categories, 'success' => 1], 200);
    }
    public function filterSubCategoryWithContent($id)
    {
        $sub_categories = SubCategory::where('root_category_id', $id)->get();
        $contents = OttContent::where('status', 'Published')->where('root_category_id', $id)->get();
        // dd($contents);
        return response()->json(['sub_categories' => $sub_categories, 'contents' => $contents, 'success' => 1], 200);
    }
}

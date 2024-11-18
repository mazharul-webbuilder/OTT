<?php

namespace App\Http\Controllers\BackendLaravel;


use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Models\RootCategory;
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
        $sub_categories = SubCategory::with('rootCategory')->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        return view('admin.pages.sub_category.create', compact('sub_categories'))->with('root_categories', $root_categories);
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
            'file' => 'mimes:jpeg,png,jpg,gif'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('sub-category.create')
                ->withErrors($validator)
                ->withInput();
        }

        if ($file = $request->file('file')) {


            $path = "SubCategory/" . $request->slug . "/Images";
            $fileData = $this->uploads($file, $path);
            // dd($fileData);
            // $media = Gravatar::create([
            //            'media_name' => $fileData['fileName'],
            //            'media_type' => $fileData['fileType'],
            //            'media_path' => $fileData['fileName'],
            //            'media_size' => $fileData['fileSize']
            //         ]);
            $request['image'] = $fileData['fileName'];
        }
        try {

            $rootCategory =  SubCategory::create($request->except(['token', 'file']));
            return redirect()->route('sub-category.create')->with('message', 'Data Inserted Successfully');
        } catch (Exception $e) {
            return redirect()->route('sub-category.create')->with('error', 'Something Went Wrong');
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
        return view('admin.pages.sub_category.edit', compact('root_categories'))->with('data', SubCategory::where('id', $id)->first());
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
        $subCategory = SubCategory::findOrFail($id);
        $rules = array(
            'title' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
            // return response()->json(array($validator->errors()));
        }
        if ($file = $request->file('file')) {
            $path = "SubCategory/" . $request->slug . "/Images";
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
            $subCategory->status = $request->status;
            $subCategory->update();

            return redirect()->route('sub-category.show', $id)->with('message', 'Data Updated Successfully');
        } catch (Exception $e) {
            return redirect()->route('sub-category.show', $id)->with('error', 'Something Went Wrong');
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
            SubCategory::find($id)->delete();
            return redirect()->route('subcategory-create')->with('message', 'Data Deleted Successfully');
        } catch (Exception $e) {
            return $e;
        }
    }
}

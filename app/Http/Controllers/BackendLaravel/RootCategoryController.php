<?php

namespace App\Http\Controllers\BackendLaravel;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Models\RootCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RootCategoryController extends Controller
{

    use Media, ResponseTrait;
    function __construct()
    {
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
        return view('admin.pages.root_category.create')->with('root_categories', $root_categories);
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
            return redirect()->route('root-category.create')
                ->withErrors($validator)
                ->withInput();
        }
        if ($file = $request->file('file')) {


            $path = "RootCategory/" . $request->slug . "/Images";
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

            if ($request['is_fixed'] == "on") {
                $request['is_fixed'] = 1;
            }

            // $rootCategory = new RootCategory();
            // $rootCategory->title = $request->title;
            // $rootCategory->slug = Str::slug($request->title);
            // $rootCategory->image = $request->image;
            // $rootCategory->order = $request->order;
            // $rootCategory->seo_title = $request->seo_title;
            // $rootCategory->seo_description = $request->seo_description;
            // // dd($rootCategory);
            // $rootCategory->save();

            $rootCategory =  RootCategory::create($request->except(['token', 'file']));

            return redirect()->route('root-category.create')->with('message', 'Data Inserted Successfully');
        } catch (Exception $e) {
            return redirect()->route('root-category.create')->with('error', 'Something Went Wrong');
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
        return view('admin.pages.root_category.edit')->with('data', RootCategory::where('id', $id)->first());
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
        return   $request->all();
        $rootCategory = RootCategory::findOrFail($id);
        $rules = array(
            'title' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
            // return response()->json(array($validator->errors()));
        }
        if ($file = $request->file('file')) {
            $path = "RootCategory/" . $request->slug . "/Images";
            $fileData = $this->uploads($file, $path);
            // dd($fileData);
            // $media = Gravatar::create([
            //            'media_name' => $fileData['fileName'],
            //            'media_type' => $fileData['fileType'],
            //            'media_path' => $fileData['filePath'],
            //            'media_size' => $fileData['fileSize']
            //         ]);
            $request->image = $fileData['fileName'];
        } else {
            $request->image = $rootCategory->image;
        }
        // return $request->all();
        try {


            $rootCategory->title = $request->title;
            $rootCategory->slug = $request->slug;
            $rootCategory->image = $request->image;
            $rootCategory->order = $request->order;
            $rootCategory->status = $request->status;
            $rootCategory->is_fixed = $request->is_fixed == "on" ? 1 : 0;
            $rootCategory->seo_title = $request->seo_title;
            $rootCategory->seo_description = $request->seo_description;
            $rootCategory->update();

            return redirect()->route('root-category.show', $id)->with('message', 'Data Updated Successfully');
        } catch (Exception $e) {
            return redirect()->route('root-category.show', $id)->with('error', 'Something Went Wrong');
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
            RootCategory::find($id)->delete();
            return redirect()->route('root-category.create')->with('message', 'Data Deleted Successfully');
        } catch (Exception $e) {
            return $e;
        }
    }
}

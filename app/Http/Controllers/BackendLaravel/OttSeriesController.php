<?php

namespace App\Http\Controllers\BackendLaravel;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Models\OttSeries;
use App\Models\RootCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OttSeriesController extends Controller
{

    use Media, ResponseTrait;
    function __construct()
    {
        // $this->middleware('permission:ott-series-list|ott-series-create|ott-series-edit|ott-series-delete', ['only' => ['index', 'store']]);
        // $this->middleware('permission:ott-series-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:ott-series-edit', ['only' => ['show', 'update']]);
        // $this->middleware('permission:ott-series-delete', ['only' => ['destroy']]);
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
        $series =  OttSeries::orderBy('id', 'desc')->get();
        return view('admin.pages.ott_series.create', compact('root_categories', 'series'))->with('rootCategory', 'subCategory', 'subSubCategory');
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
            'title' => 'required',
            'root_category_id' => 'required',
            'status' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $ottSeries =  OttSeries::create($request->except(['token']));

            return redirect()->route('ott-series.create')->with('message', 'Data Inserted Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
        return view('admin.pages.ott_series.edit', compact('root_categories'))->with('data', OttSeries::where('id', $id)->first());
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
        $rootCategory = OttSeries::findOrFail($id);
        $rules = array(
            'title' => 'required',
            'root_category_id' => 'required',
            'status' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
            // return response()->json(array($validator->errors()));
        }

        try {
            $rootCategory->title = $request->title;
            $rootCategory->slug = $request->slug;
            $rootCategory->root_category_id = $request->root_category_id;
            $rootCategory->sub_category_id = $request->sub_category_id;
            $rootCategory->sub_sub_category_id = $request->sub_sub_category_id;
            $rootCategory->status = $request->status;
            $rootCategory->update();

            return redirect()->back()->with('message', 'Data Updated Successfully');
        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
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
            OttSeries::find($id)->delete();
            return back()->with('message', 'Data Deleted Successfully');
        } catch (Exception $e) {
            return $e;
        }
    }
}

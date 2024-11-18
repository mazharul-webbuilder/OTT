<?php

namespace App\Http\Controllers\BackendLaravel;


use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Models\OttSlider;
use App\Models\RootCategory;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OttSliderController extends Controller
{
    use Media, ResponseTrait;
    function __construct()
    {
        $this->middleware('permission:ott-slider-list|ott-slider-create|ott-slider-edit|ott-slider-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:ott-slider-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:ott-slider-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:ott-slider-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ott_sliders = OttSlider::with('rootCategory')->orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        // $paths = Storage::disk('spaces')->url($ott_sliders->image); 
        // dd($paths);
        return view('admin.pages.ott_slider.index', compact('ott_sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $root_categories = RootCategory::orderBy('order', 'asc')->orderBy('id', 'asc')->get();
        return view('admin.pages.ott_slider.create')->with('root_categories', $root_categories);
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
            'file' => 'required|mimes:jpeg,png,jpg,gif',
            'root_category_id' => 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('ott-slider.index')
                ->withErrors($validator)
                ->withInput();
        }

        if ($file = $request->file('file')) {
            // dd($request->path);
            $path = "/OTT/Images";
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

            if ($request['is_home'] == "on") {
                $request['is_home'] = 1;
            }

            $ottSlider =  OttSlider::create($request->except(['token', 'file']));
            return redirect()->route('ott-slider.index')->with('message', 'Data Inserted Successfully');
        } catch (Exception $e) {
            return redirect()->route('ott-slider.index')->with('error', $e->getMessage());
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
        $ott_slider = OttSlider::where('id', $id)->first();
        return view('admin.pages.ott_slider.edit', compact('root_categories'))->with('data', $ott_slider);
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
        $ottSlider = OttSlider::findOrFail($id);
        $rules = array(
            'title' => 'required',
            'file' => 'mimes:jpeg,png,jpg,gif',
            'root_category_id' => 'required|numeric'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('ott-slider.show', $id)
                ->withErrors($validator)
                ->withInput();
        }
        if ($file = $request->file('file')) {
            $path = $request->path;
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
            $request->image = $ottSlider->image;
        }
        // return $request->all();
        try {

            if ($request['is_home'] == "on") {
                $request['is_home'] = 1;
            }
            $ottSlider->title = $request->title;
            $ottSlider->slug = $request->slug;
            $ottSlider->image = $request->image;
            $ottSlider->order = $request->order;
            $ottSlider->description = $request->description;
            $ottSlider->bottom_title = $request->bottom_title;
            $ottSlider->root_category_id = $request->root_category_id;
            $ottSlider->content_url = $request->content_url;
            $ottSlider->status = $request->status;
            $ottSlider->is_home = $request->is_home;
            $ottSlider->update();

            return redirect()->route('ott-slider.show', $id)->with('message', 'Data Updated Successfully');
        } catch (Exception $e) {
            return redirect()->route('ott-slider.show', $id)->with('error', 'Something Went Wrong');
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
            OttSlider::find($id)->delete();
            return redirect()->route('ott-slider.index')->with('message', 'Data Deleted Successfully');
        } catch (Exception $e) {
            return $e;
        }
    }
}

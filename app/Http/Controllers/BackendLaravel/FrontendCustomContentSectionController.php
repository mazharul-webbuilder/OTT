<?php

namespace App\Http\Controllers\BackendLaravel;


use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\FrontendCustomContentResource;
use App\Http\Resources\Backend\FrontendCustomContentSectionResource;
use App\Http\Resources\Backend\FrontendCustomSliderResource;
use App\Http\Resources\Backend\OttSliderResource;
use App\Http\Resources\Backend\SubCategoryResource;
use App\Models\FrontendCustomContent;
use App\Models\FrontendCustomContentSection;
use App\Models\FrontendCustomSlider;
use App\Models\OttSlider;
use App\Models\RootCategory;
use App\Models\SubCategory;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FrontendCustomContentSectionController extends Controller
{
    use Media, ResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:frontend-custom-content-section-list|frontend-custom-content-section-create|frontend-custom-content-section-edit|frontend-custom-content-section-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:frontend-custom-content-section-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:frontend-custom-content-section-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:frontend-custom-content-section-delete', ['only' => ['destroy']]);
        $this->middleware('permission:frontend-custom-content-section-add-content', ['only' => ['addContent', 'AddCustomContents']]);
        $this->middleware('permission:edit-frontend-custom-content-section-contents', ['only' => ['editCustomContent', 'updateCustomContent']]);
        $this->middleware('permission:frontend-custom-content-section-content-delete', ['only' => ['destroyCustomContent']]);
    }
    public function index()
    {
        try {

            $data =  FrontendCustomContentSectionResource::collection(FrontendCustomContentSection::latest()->paginate(10));
            return $this->successResponse('Data fetched Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $custom_sections = FrontendCustomContentSection::orderBy('id', 'asc')->get();
        return view('admin.pages.custom_content_section.create', compact('custom_sections'));
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
            'content_type_slug' => 'required|numeric|unique:frontend_custom_content_sections',
            'content_type_title' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            if ($request['is_available_on_single_page'] == "on") {
                $request['is_available_on_single_page'] = 1;
            } else {
                $request['is_available_on_single_page'] = 0;
            }

            if ($request['is_featured_section'] == "on") {
                $request['is_featured_section'] = 1;
            } else {
                $request['is_featured_section'] = 0;
            }
            $customContent = new FrontendCustomContentSection();
            $customContent->content_type_slug = $request->content_type_slug;
            $customContent->content_type_title = $request->content_type_title;
            $customContent->more_info_slug = $request->more_info_slug;
            $customContent->is_available_on_single_page = $request->is_available_on_single_page;
            $customContent->is_featured_section = $request->is_featured_section;

            $customContent->save();

            return redirect()->route('frontend-custom-content-section.create')->with('message', 'Data Inserted Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something Went Wrong');
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
            $customContent = FrontendCustomContentSection::findOrFail($id);
            return view('admin.pages.custom_content_section.edit')->with('data', $customContent);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something Went Wrong');
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
            'content_type_slug' => 'required|numeric',
            'content_type_title' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null);
        }
        if ($request['is_available_on_single_page'] == "on") {
            $request['is_available_on_single_page'] = 1;
        } else {
            $request['is_available_on_single_page'] = 0;
        }


        if ($request['is_featured_section'] == "on") {
            $request['is_featured_section'] = 1;
        } else {
            $request['is_featured_section'] = 0;
        }

        try {

            $customContent =  FrontendCustomContentSection::findOrFail($id);
            if ($customContent->content_type_slug != $request->content_type_slug) {
                $is_exists = FrontendCustomContentSection::where('content_type_slug', $request->content_type_slug)->exists();
                if ($is_exists) {
                    return $this->errorResponse("Content type slug already taken", null, null);
                }
            }
            $customContent->content_type_slug = $request->content_type_slug;
            $customContent->content_type_title = $request->content_type_title;
            $customContent->more_info_slug = $request->more_info_slug;
            $customContent->is_available_on_single_page = $request->is_available_on_single_page;
            $customContent->is_featured_section = $request->is_featured_section;
            $customContent->update();

            return back()->with('message', 'Data Updated Successfully');
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
        // dd($id);
        try {
            FrontendCustomContentSection::findOrFail($id)->delete();
            return back()->with('message', 'Data Deleted Successfully');
        } catch (Exception $e) {
            return $e;
        }
    }

    public function addContent($id)
    {
        $data = FrontendCustomContentSection::findOrFail($id);
        $root_categories = RootCategory::all();
        $custom_contents = FrontendCustomContent::where('frontend_custom_content_type_id', $id)->with('ottContent')->get();
        return view('admin.pages.custom_content_section.add_custom_contents', compact('data', 'root_categories', 'custom_contents'));
    }

    public function AddCustomContents(Request $request)
    {
        // return $request->all();
        $data = [];
        foreach ($request->content_id as $content_id) {
            $data[] = [
                'content_id' => $content_id,
                'publish_date' => Carbon::now(),
                'is_active' => 1,
                'frontend_custom_content_type_id' => $request->content_type_id
            ];
        }
        // dd($data);
        foreach ($data as $item) {
            $content_exist = FrontendCustomContent::where('frontend_custom_content_type_id', $request->content_type_id)->where('content_id', $item['content_id'])->first();
            if ($content_exist == null) {
                FrontendCustomContent::insert($item);
            }
            // FrontendCustomContent::updateOrCreate(['frontend_custom_content_type_id' => $request->content_type_id], $item);
        }
        return back();
    }


    /**
     * @param int id
     * return Response blade
     */

    public function editCustomContent($id)
    {
        $data = FrontendCustomContent::findOrFail($id);
        return view('admin.pages.custom_content_section.edit_custom_contents', compact('data'));
    }


    /**
     * @param int id
     * return Response blade
     */

    public function updateCustomContent(Request $reqeust)
    {
        // dd($reqeust->all());
        if ($reqeust['is_active'] == "on") {
            $reqeust['is_active'] = 1;
        } else {
            $reqeust['is_active'] = 0;
        }
        $data = FrontendCustomContent::where('id', $reqeust->custom_content_id)->update($reqeust->except(['_token', 'custom_content_id']));
        return back()->with('message', 'Data updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCustomContent($id)
    {
        // dd($id);
        try {
            FrontendCustomContent::findOrFail($id)->delete();
            return back()->with('message', 'Data Deleted Successfully');
        } catch (Exception $e) {
            return $e;
        }
    }
}

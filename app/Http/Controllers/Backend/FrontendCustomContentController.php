<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\FrontendCustomContentResource;
use App\Http\Resources\Backend\FrontendCustomSliderResource;
use App\Http\Resources\Backend\OttSliderResource;
use App\Http\Resources\Backend\SubCategoryResource;
use App\Models\FrontendCustomContent;
use App\Models\FrontendCustomSlider;
use App\Models\OttSlider;
use App\Models\SubCategory;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FrontendCustomContentController extends Controller
{
    use Media, ResponseTrait;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        try {
            if ($request->has('per_page')) {
                $per_page = $request->per_page;
            } else {
                $per_page = 10;
            }

            $data =   FrontendCustomContent::latest()->paginate($per_page);
            return $this->successResponse('Data fetched Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
    public function contentsByFrontentSectionID(Request $request, $frontend_custom_content_type_id)
    {

        try {
            if ($request->has('per_page')) {
                $per_page = $request->per_page;
            } else {
                $per_page = 10;
            }

            $data =   FrontendCustomContent::where('frontend_custom_content_type_id', $frontend_custom_content_type_id)->where('is_active', 1)->with('ottContent')->latest()->paginate($per_page);
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
            // 'content_id' => 'required|numeric',
            'frontend_custom_content_type_id' => 'required|numeric',
            'sorting_position' => 'numeric',
            'is_upcoming' => 'numeric',
            'is_active' => 'numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null);
        }

        try {

            $data = [];
            foreach ($request->content_id as $content_id) {
                $data[] = [
                    'content_id' => $content_id,
                    'publish_date' => $request->publish_date,
                    'is_active' => $request->is_active,
                    'frontend_custom_content_type_id' => $request->frontend_custom_content_type_id,
                    'is_upcoming' => $request->is_upcoming,
                    'sorting_position' => $request->sorting_position,
                ];
            }
            // dd($data);
            foreach ($data as $item) {
                $content_exist = FrontendCustomContent::where('frontend_custom_content_type_id', $request->frontend_custom_content_type_id)->where('content_id', $item['content_id'])->first();
                if ($content_exist == null) {
                    FrontendCustomContent::insert($item);
                }
                // FrontendCustomContent::updateOrCreate(['frontend_custom_content_type_id' => $request->content_type_id], $item);
            }
            return $this->successResponse('Data store successfully', $data);
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
            $customContent = FrontendCustomContent::findOrFail($id);

            $data = new FrontendCustomContentResource($customContent);

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
            'content_id' => 'required|numeric',
            'frontend_custom_content_type_id' => 'required|numeric',
            'sorting_position' => 'numeric',
            'is_upcoming' => 'numeric',
            'is_active' => 'numeric',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null);
        }

        try {
            $data = [];
            foreach ($request->content_id as $content_id) {
                $data[] = [
                    'content_id' => $content_id,
                    'publish_date' => $request->publish_date,
                    'is_active' => $request->is_active,
                    'frontend_custom_content_type_id' => $request->frontend_custom_content_type_id,
                    'is_upcoming' => $request->is_upcoming,
                    'sorting_position' => $request->sorting_position,
                ];
            }
        
            foreach ($data as $item) {
                $content_exist = FrontendCustomContent::where('frontend_custom_content_type_id', $request->frontend_custom_content_type_id)->where('content_id', $item['content_id'])->first();
                if ($content_exist == null) {
                    FrontendCustomContent::insert($item);
                }
                
            }
            return $this->successResponse('Data store successfully', $data);
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
            // dd(FrontendCustomContent::findOrFail($id));
            FrontendCustomContent::findOrFail($id)->delete();

            return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
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
        try {
            foreach ($data as $item) {
                $content_exist = FrontendCustomContent::where('frontend_custom_content_type_id', $request->content_type_id)->where('content_id', $item['content_id'])->first();
                if ($content_exist == null) {
                    FrontendCustomContent::insert($item);
                }
                // FrontendCustomContent::updateOrCreate(['frontend_custom_content_type_id' => $request->content_type_id], $item);
            }
            return $this->successResponse('Data Inserted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
    public function updateCustomContent(Request $reqeust)
    {
        // dd($reqeust->all());
        if ($reqeust['is_active'] == "on") {
            $reqeust['is_active'] = 1;
        } else {
            $reqeust['is_active'] = 0;
        }
        try {
            $data = FrontendCustomContent::where('id', $reqeust->custom_content_id)->update($reqeust->except(['_token', 'custom_content_id']));
            return $this->successResponse('Data Inserted successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
    public function destroyCustomContent($id)
    {
        // dd($id);
        try {
            FrontendCustomContent::findOrFail($id)->delete();
            return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

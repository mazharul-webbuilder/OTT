<?php

namespace App\Http\Controllers\Backend;

use App\Helper\Media;
use App\Http\Controllers\Controller;
use App\Models\OttSeries;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OttSeriesController extends Controller
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
     *
     *
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

            $data = OttSeries::withCount('seasons')->latest()->paginate($per_page);
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
            'title' => 'required',
            'root_category_id' => 'required|numeric',
            'slug' => 'unique:ott_series,slug',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->validationError("Validation Error", null, $validator->getMessageBag());
        }
        try {

            $ottSeries = new OttSeries();
            $ottSeries->title = $request->title;
            $ottSeries->slug = Str::slug($request->title);
            $ottSeries->status = $request->status;
            $ottSeries->root_category_id = $request->root_category_id;
            $ottSeries->sub_category_id = $request->sub_category_id;
            $ottSeries->sub_sub_category_id = $request->sub_sub_category_id;
            $ottSeries->save();

            return $this->successResponse('Data store successfully', $ottSeries);
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
            $ottSeries = OttSeries::findOrFail($id);

            $data = $ottSeries;

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

        try {

            $ottSeries = OttSeries::findOrFail($id);
            $rules = array(
                'title' => 'required',
                'root_category_id' => 'required|numeric',
                'slug' => [
                    Rule::unique('ott_series', 'slug')->ignore($ottSeries->id),
                ],
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->validationError("Validation Error", null, $validator->getMessageBag());
            }

            $ottSeries->title = $request->title;
            $ottSeries->slug = Str::slug($request->title);
            $ottSeries->status = $request->status;
            $ottSeries->root_category_id = $request->root_category_id;
            $ottSeries->sub_category_id = $request->sub_category_id;
            $ottSeries->sub_sub_category_id = $request->sub_sub_category_id;
            $ottSeries->update();

            return $this->successResponse('Data updated successfully', $ottSeries);
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
        try {

            OttSeries::findOrFail($id)->delete();

            return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    //Get series
    public function getSeries()
    {
        try {
            $data = OttSeries::select('id', 'title')->get();
            return $this->successResponse('Data fetched Successfully', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

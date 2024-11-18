<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Helper\Media;
use App\Http\Resources\Backend\SubscriptionSourceFormatResource;
use App\Models\SubscriptionSourceFormat;
use App\Traits\ResponseTrait;
use Exception; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubscriptionSourceFormatController extends Controller
{
    use Media, ResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        try {  
            $data =  SubscriptionSourceFormatResource::collection(SubscriptionSourceFormat::latest()->paginate(10)); 
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
            'subscription_plan_id' => 'required|numeric',
            'source_format' => 'required|in:144p,240p,360p,720p,1080p,1440p'  
        ); 

        $validator = Validator::make($request->all(), $rules); 
        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null); 
        }

        try { 
            $format = new SubscriptionSourceFormat(); 
            $format->subscription_plan_id = $request->subscription_plan_id;
            $format->source_format = $request->source_format; 
            $format->save(); 

            return $this->successResponse('Data store successfully', $format);
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
            $customContent = SubscriptionSourceFormat::findOrFail($id);

            $data = new SubscriptionSourceFormatResource($customContent);

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
            'subscription_plan_id' => 'required|numeric',
            'source_format' => 'required|in:144p,240p,360p,720p,1080p,1440p'  
        ); 

        $validator = Validator::make($request->all(), $rules); 
        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null); 
        }

        try { 
            $format =  SubscriptionSourceFormat::findOrFail($id); 
            $format->subscription_plan_id = $request->subscription_plan_id;
            $format->source_format = $request->source_format; 
            $format->update(); 

            return $this->successResponse('Data updated successfully', $format);
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

        SubscriptionSourceFormat::findOrFail($id)->delete();

        return $this->successResponse('Data Deleted successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }
}

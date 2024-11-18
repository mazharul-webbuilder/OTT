<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\OttContent;
use App\Models\OttContentReview;
use App\Models\OttContentReviewReaction;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OttContentReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use ResponseTrait;
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
        //
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
            'content_id' => 'required',
            'review_star' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse("Validation Error", $validator->errors(), null);
        }
        $content_id = OttContent::where('uuid', $request->content_id)->pluck('id')->first();
        $is_exist = OttContentReview::where('content_id', $content_id)->where('user_id', Auth::guard('api')->user()->id)->first();
        if (isset($is_exist)) {
            return $this->successResponse('Already review for this content added By this user', null);
        }
        $data = [
            'user_id' => Auth::guard('api')->user()->id,
            'content_id' => $content_id,
            'comment' => $request->comment,
            'review_star' => $request->review_star
        ];

        try {
            OttContentReview::create($data);
            return $this->successResponse('SuccessFully added', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OttContentReview  $ottContentReview
     * @return \Illuminate\Http\Response
     */
    public function show(OttContentReview $ottContentReview)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OttContentReview  $ottContentReview
     * @return \Illuminate\Http\Response
     */
    public function edit(OttContentReview $ottContentReview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OttContentReview  $ottContentReview
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OttContentReview $ottContentReview)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OttContentReview  $ottContentReview
     * @return \Illuminate\Http\Response
     */
    public function destroy($device, $id)
    {
        try {
            OttContentReview::where('id', $id)->delete();
            return $this->successResponse('Deleted Successfully', null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null);
        }
    }

    public function reviewReaction($app, Request $request)
    {
        try {
            $user_id = Auth::guard('api')->user()->id;
            $reaction  =  $request->reaction_type;
            $review_id = $request->review_id;

            $is_exist_rection = OttContentReviewReaction::where('user_id', $user_id)->where('ott_content_review_id', $review_id)->first();

            if (!empty($is_exist_rection)) {
                if ($is_exist_rection->is_helpful && $reaction == "is_helpful") {
                    return $this->successResponse("already reacted same reaction on this review", null, null);
                } elseif ($is_exist_rection->inappropriate && $reaction == "inappropriate") {
                    return $this->successResponse("already reacted same reaction on this review", null, null);
                } elseif ($is_exist_rection->is_helpful && $reaction == "inappropriate") {

                    $data = [
                        'user_id' => $user_id,
                        'ott_content_review_id' => $review_id,
                        'is_helpful' => false,
                        'inappropriate' => true,
                    ];
                    OttContentReviewReaction::where('id', $is_exist_rection->id)->update($data);
                    return $this->successResponse("Successfully Updated", $data, null);
                } elseif ($is_exist_rection->inappropriate && $reaction == "is_helpful") {
                    
                    $data = [
                        'user_id' => $user_id,
                        'ott_content_review_id' => $review_id,
                        'is_helpful' => true,
                        'inappropriate' => false,
                    ];
                    OttContentReviewReaction::where('id', $is_exist_rection->id)->update($data);
                    return $this->successResponse("Successfully Updated", $data, null);
                }
            } else {
                $data = [
                    'user_id' => $user_id,
                    'ott_content_review_id' => $review_id,
                    'is_helpful' => strtolower($reaction) == 'is_helpful',
                    'inappropriate' => strtolower($reaction) == 'inappropriate',
                ];
            }


            $reaction = OttContentReviewReaction::create($data);

            return $this->successResponse('Successfully Created', $data, null);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), null, null);
        }
    }

    // public function reviewReaction2($device, Request $request)
    // {
    //     try {
    //         $user_id = Auth::guard('api')->user()->id;
    //         $reaction  =  $request->reaction_type;
    //         $review_id = $request->review_id;

    //         $is_exist_rection = OttContentReviewReaction::where('user_id', $user_id)->where('ott_content_review_id', $review_id)->first();

    //         if (!$is_exist_rection) {

    //             $data = [
    //                 'user_id' => $user_id,
    //                 'ott_content_review_id' => $review_id,
    //                 'is_helpful' => $reaction == 1,
    //                 'inappropriate' => $reaction == 0,
    //             ];

    //             $reaction = OttContentReviewReaction::create($data);

    //             return $this->successResponse('Successfully Created', $data, null);
    //         }

    //         $existing_reaction_type = $is_exist_rection->is_helpful ?? $is_exist_rection->inappropriate;
    //         if ($reaction == $existing_reaction_type) {
    //             return $this->successResponse("already reacted same reaction on this review", null, null);
    //         }

    //         $data = [
    //             'user_id' => $user_id,
    //             'ott_content_review_id' => $review_id,
    //             'is_helpful' => $existing_reaction_type == $reaction,
    //             'inappropriate' => $existing_reaction_type == $reaction,
    //         ];
    //         OttContentReviewReaction::where('id', $is_exist_rection->id)->update($data);
    //         return $this->successResponse("Successfully Updated", $data, null);
    //     } catch (Exception $e) {
    //         return $this->errorResponse($e->getMessage(), null, null);
    //     }
    // }
}

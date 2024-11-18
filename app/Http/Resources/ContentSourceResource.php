<?php

namespace App\Http\Resources;

use App\Models\ContentSource;
use App\Models\OttContent;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionSourceFormat;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\ContentSourceFormatCheckerTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ContentSourceResource extends JsonResource
{
    use ContentSourceFormatCheckerTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // dd($this->source_format);
        if (Auth::guard('api')->check()) {
            $user =  Auth::guard('api')->user();

            $user_subscription_plan_id = $user->subscriptionPlans->where('end_date', '>=', Carbon::now())->where('is_active', 1)->pluck('subscription_plan_id')->first();
            $source_formats = SubscriptionSourceFormat::where('subscription_plan_id', $user_subscription_plan_id)->pluck('source_format')->toArray();

            if (in_array($this->source_format, $source_formats)) {

                return  [
                    'id' => $this->id,
                    'ott_content_id' => $this->ott_content_id,
                    'content_source' => $this->content_source,
                    'fps' => $this->fps,
                    'source_format' => $this->source_format,
                ];
            } else {

                $ottContent = OttContent::find($this->ott_content_id);
                if ($ottContent->access == config("constants.OTTCONTENTFREE")) {
                    return  [
                        'id' => $this->id,
                        'ott_content_id' => $this->ott_content_id,
                        'content_source' => $this->content_source,
                        'fps' => $this->fps,
                        'source_format' => $this->source_format,
                        'source_type' => $this->source_type,
                    ];
                }
            }
        } else {
            return  [
                'id' => $this->id,
                'ott_content_id' => $this->ott_content_id,
                'content_source' => $this->content_source,
                'fps' => $this->fps,
                'source_format' => $this->source_format,
                'source_type' => $this->source_type,

            ];
        }

        // dd(22);

        // $sourceFormats = ContentSource::wherein() 

    }
}

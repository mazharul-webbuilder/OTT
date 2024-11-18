<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'plan_name' => $this->plan_name,
            'plan_slug' => $this->plan_slug,
            'regular_price' => $this->regular_price,
            'is_renewable' => $this->is_renewable,
            'is_discounted' => $this->is_discounted,
            'discount_price' => $this->discount_price,
            'discount_rate' => $this->discount_rate,
            'discount_type' => $this->discount_type,
            'discount_expiry_date' => $this->discount_expiry_date,
            'number_of_allowed_device' => $this->number_of_allowed_device,
            'number_of_allowed_stream' => $this->number_of_allowed_stream,
            'duration' => $this->duration,
            'till_date' => date('Y-M-d', strtotime('+' . $this->duration . 'days'))
        ];
    }
}

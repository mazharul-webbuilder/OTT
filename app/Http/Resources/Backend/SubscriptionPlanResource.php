<?php

namespace App\Http\Resources\Backend;

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
            'discount_type' => $this->discount_type,
            'description' => $this->description,
            'is_discounted' => $this->is_discounted,
            'discount_price' => $this->discount_price,
            'discount_rate' => $this->discount_rate,
            'number_of_allowed_device' => $this->number_of_allowed_device, 
            'number_of_allowed_stream' => $this->number_of_allowed_stream, 
            'discount_expiry_date' => $this->discount_expiry_date, 
            'duration' => $this->duration, 
            'regular_price' => $this->regular_price, 
            'status' => $this->status, 
            'is_renewable' => $this->is_renewable, 
        ];
    }
}

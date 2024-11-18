<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class FrontendCustomSliderResource extends JsonResource
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
            'slider_type_slug' => $this->slider_type_slug, 
            'slider_type_title' => $this->slider_type_title, 
            'image' => $this->image, 
            'slider_type_sub_title' => $this->slider_type_sub_title, 
            'press_action_slug' => $this->press_action_slug, 
            'press_action_slug_activity' => $this->press_action_slug_activity, 
            'is_active' => $this->is_active, 
            'sorting_order' => $this->sorting_order, 
        ];
    }
}

<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class FrontendCustomContentSectionSliderResource extends JsonResource
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
            'title' => $this->title, 
            'frontend_custom_content_type_id' => $this->frontend_custom_content_type_id, 
            'image' => $this->image,  
            'content_url' => $this->content_url,  
            'status' => $this->status,  
            'order' => $this->order,  
        ];
    }
}

<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class FrontendCustomContentSectionResource extends JsonResource
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
            'content_type_slug' => $this->content_type_slug, 
            'content_type_title' => $this->content_type_title, 
            'more_info_slug' => $this->more_info_slug,  
            'is_featured_section' => $this->is_featured_section,  
            'is_available_on_single_page' => $this->is_available_on_single_page,  
            'order' => $this->order,  
        ];
    }
}

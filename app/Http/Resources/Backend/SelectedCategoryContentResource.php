<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class SelectedCategoryContentResource extends JsonResource
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
            'root_category_id' => $this->root_category_id, 
            'ott_content_id' => $this->ott_content_id, 
            'is_featured' => $this->is_featured, 
        ];
        
        
    }
}

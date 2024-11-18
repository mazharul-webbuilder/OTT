<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
class OttSliderResource extends JsonResource
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
            'slug' => $this->slug,
            'image' => $this->image,
            'landscape_image' => $this->landscape_image,
            'content_url' => $this->content_url,
            'description' => $this->description,
            'bottom_title' => $this->bottom_title,
            'root_category_id' => $this->root_category_id,
            'root_category_title' => $this->rootCategory->title,
            'status' => $this->status,
            'is_home' => $this->is_home,
            'status' => $this->status,
            'order' => $this->order,

        ];
    }
}

<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'title'=>$this->title,
            'slug'=>$this->slug,
            'short_description'=>$this->short_description,
            'content'=>$this->content,
            'thumbnail'=>$this->thumbnail,
            'is_published'=>$this->is_published,
            'version_number'=>$this->version_number, 
        ];
    }
}

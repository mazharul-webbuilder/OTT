<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class RootCategoryResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
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
            'order' => $this->order,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'status' => $this->status,
            // 'subCategories' => SubCategoryResource::collection($this->subCategories),
            // 'categorySliders' => $this->categorySliders,
            // 'selected_category_items' =>  SelectedCategoryContentResource::collection($this->selectCategoryContentents),
        ];
    }
}
<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OttSeriesResource extends JsonResource
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
            'title'=> $this->title,
            'slug'=> $this->slug,
            // 'seasons' => $this->seasons,
            // 'contents'=>OttContentResource::collection($this->ottContents),
        ];
    }
}

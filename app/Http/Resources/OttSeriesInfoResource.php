<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OttSeriesInfoResource extends JsonResource
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
            'series_title'=>$this->title,
            'season_info'=>$this->seasons?? null, 
        ];
    }
}

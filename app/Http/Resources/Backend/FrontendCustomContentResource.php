<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class FrontendCustomContentResource extends JsonResource
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
            'content' => $this->ottContent,
            'is_active' => $this->is_active,
            'publish_date' => $this->publish_date,
            'is_upcoming' => $this->is_upcoming,
            'sorting_position' => $this->sorting_position,
            'frontend_custom_content_type_id' => $this->frontend_custom_content_type_id
        ];
    }
}

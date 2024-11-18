<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OttDownloadableContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ott_content_id' => $this->ott_content_id,
            'expire_in_days' => $this->expire_in_days,
            'available_marketplace_ids' => $this->available_marketplace_ids,
            'downloadable_qualities' => $this->downloadable_qualities,
            'available_from' => $this->created_at,
            'last_modified' => $this->updated_at,
        ];
    }
}

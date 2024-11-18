<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentOwnerResource extends JsonResource
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
            'title' => $this->title,
            'abbreviation' => $this->abbreviation,
            'country' => $this->country,
            'number_of_contents' => $this->number_of_contents,
            'description' => $this->description,
        ];
    }
}

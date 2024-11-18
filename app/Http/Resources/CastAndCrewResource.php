<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CastAndCrewResource extends JsonResource
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
            'name' => $this->name,
            'about' => $this->about,
            'dob' => $this->dob,
            'image' => $this->image,
            'nationality' => $this->nationality,
            'upcomming' => $this->upcomming,
            'previous' => $this->previous,
        ];
    }
}

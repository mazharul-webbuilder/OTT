<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'code' => $this->code,
            'currency_name' => $this->currency_name,
            'currency_symbol' => $this->currency_symbol,
            'region' => $this->region,
            'language' => $this->language,
            'population' => $this->population,
        ];
    }
}

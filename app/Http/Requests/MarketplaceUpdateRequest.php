<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MarketplaceUpdateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $marketplaceId = $this->route('marketplace'); // this is name of route parameter

        return [
            'name' => ['required', 'string', Rule::unique('marketplaces', 'name')->ignore($marketplaceId)],
            'slug' => ['required', 'string', Rule::unique('marketplaces', 'slug')->ignore($marketplaceId)],
            'status' => ['nullable', Rule::in([0, 1])],
            'icon' => ['nullable','image', 'mimes:jpeg,png,jpg,gif,svg|max:2048']
        ];
    }
}

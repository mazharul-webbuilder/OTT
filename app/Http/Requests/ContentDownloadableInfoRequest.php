<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentDownloadableInfoRequest extends FormRequest
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
        return [
            'is_downloadable' => ['required', 'bool'],
            'expire_in_days' => ['required_if:is_downloadable,1', 'integer', 'min:1'],
            'available_marketplace_ids' => ['required_if:is_downloadable,1','json'],
//            'available_marketplace_ids.*' => [Rule::exists('marketplaces', 'id'), 'distinct'],
            'downloadable_qualities' => ['required_if:is_downloadable,1', 'json'],
//            'downloadable_qualities.*' => ['distinct', Rule::in([144, 240, 360, 480, 520, 720, 1080])]
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentCoutryRestrictictedRequest extends FormRequest
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
        'country_ids' => [
            Rule::requiredIf(function () {
                return $this->input('no_restriction') != '1';
            }),
            'json',
        ],
        'no_restriction' => ['nullable', Rule::in(['1'])],
    ];
    }
}

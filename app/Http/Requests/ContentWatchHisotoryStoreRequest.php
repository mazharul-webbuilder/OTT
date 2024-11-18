<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContentWatchHisotoryStoreRequest extends FormRequest
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
            'user_id' => ['nullable', Rule::exists('users', 'id')],
            'ott_content_id' => ['required', Rule::exists('ott_contents', 'id')],
            'ott_content_type' => ['required', 'string'],
            'watched_at' => ['nullable'],
            'watched_duration' => ['required', 'numeric', 'gt:0'],
        ];
    }
}

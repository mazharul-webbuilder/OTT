<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WatchHistoryTrackRequest extends FormRequest
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
            'history' => ['required', 'array'],
            'history.*.ott_content_id' => ['required', Rule::exists('ott_contents', 'id')],
            'history.*.ott_content_type' => ['required', Rule::in(['series', 'single'])],
            'history.*.watched_at' => ['nullable', 'date_format:Y-m-d'],
            'history.*.watched_duration' => ['nullable', 'integer', 'min:0']

        ];
    }
}

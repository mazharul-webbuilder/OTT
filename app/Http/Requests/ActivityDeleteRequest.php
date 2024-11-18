<?php

namespace App\Http\Requests;

use App\Rules\ActivityDeleteRule;
use Illuminate\Foundation\Http\FormRequest;

class ActivityDeleteRequest extends FormRequest
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
            'activities_ids' => ['bail','required', 'json', 'min:3', new ActivityDeleteRule()]
        ];
    }

    /**
     * Validation Error message modified
    */
    public function messages(): array
    {
        return [
          'activities_ids.min' => 'No activity id passed'
        ];
    }
}

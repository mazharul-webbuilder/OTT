<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginWithGoogleRequest extends FormRequest
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
            'google_id' => ['required',],
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'dob' => ['nullable', 'date_format:Y-m-d'],
            'gender' => ['nullable', Rule::in(values: ['male', 'female', 'other'])],
            'device_name' => ['required'],
            'deviceuniqueid' => ['required'],
        ];
    }
}

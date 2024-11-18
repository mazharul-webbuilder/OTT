<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailVerifyUserRegistrationRequest extends FormRequest
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
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'name' => ['required', 'min:3', 'max:54'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'dob' => ['nullable', 'date_format:Y-m-d', 'before:today', 'after:1900-01-01'],
            'password' => ['required', 'min:6', 'confirmed'],
            'is_newsletter_subscriber' => ['nullable', Rule::in([1,0])],
            'device_name' => ['required'],
            'deviceuniqueid' => ['required'],
        ];
    }

    public function messages(): array
    {
       return [
           'email.exists' => 'Email is not verified or invalid',
       ];
    }
}

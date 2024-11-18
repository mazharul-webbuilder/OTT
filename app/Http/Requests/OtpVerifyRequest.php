<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OtpVerifyRequest extends FormRequest
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
            'phone' => ['required', 'regex:/^\+?[0-9]{11,15}$/', Rule::exists('users', 'phone')],
            'otp' => ['required', 'min:6'],
            'device_name' => ['required'],
            'deviceuniqueid' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'phone.exists' => 'Phone number not found',
            'phone.regex' => 'Invalid phone number'
        ];
    }
}

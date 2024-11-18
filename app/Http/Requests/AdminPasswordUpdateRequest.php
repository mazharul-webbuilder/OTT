<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class AdminPasswordUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            'current_password' => ['required', 'current_password'],
            'password' => ['nullable', 'string', 'confirmed', 'min:6']
        ];
    }

    public function messages()
    {
        return [
          'current_password' => 'Current password is incorrect'
        ];
    }

//    /**
//     * Will call automatically by Laravel itself
//    */
//    public function withValidator($validator): void
//    {
//        $validator->after(function ($validator) {
//            $user = $this->user();
//            if (!Hash::check($this->input('current_password'), $user->password)) {
//                $validator->errors()->add('current_password', 'The provided current password is incorrect.');
//            }
//        });
//    }
}

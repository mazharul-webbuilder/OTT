<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CustomValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Your validation logic here
        return $value === 'valid';
    }

    public function message()
    {
        return 'The :attribute is invalid.';
    }
}

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ActivityDeleteRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * $attribute holds the name of the input key
     * $value holds the actual value pass by key
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $activityIds = json_decode($value);

        if (!DB::table('activity_log')->whereIn('id', $activityIds)->exists())
        {
            $fail("One or more Invalid activity id passed");
        }
    }
}

<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class HumanName implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match("/^[\p{L}- ]+$/u", $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute validation error message.';
    }
}

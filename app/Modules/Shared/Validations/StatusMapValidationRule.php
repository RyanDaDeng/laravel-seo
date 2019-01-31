<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 24/1/19
 * Time: 9:44 AM
 */

namespace App\Modules\Shared;


use Illuminate\Contracts\Validation\Rule;

class StatusMapValidationRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return strtoupper($value) === $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be 1 or 2.';
    }

}
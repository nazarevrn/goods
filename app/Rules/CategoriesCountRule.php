<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CategoriesCountRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * If number of categories between 2 and 10 validation passes.
     *
     * @param  string  $attribute
     * @param  array  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $arrayCount = count($value);
        if ($arrayCount >=2 && $arrayCount <= 10) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Number of categories is not valid. Supports from 2 to 10 categories for each good.';
    }
}

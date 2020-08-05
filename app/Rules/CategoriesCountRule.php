<?php

namespace App\Rules;

use App\Categories;
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
     * Check count of categories (between 2 and 10)
     * and all categories ids has in DB
     *
     * @param  string  $attribute
     * @param  array  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $arrayCount = count($value);
        $checkCount = false;
        if ($arrayCount >=2 && $arrayCount <= 10) {
            $checkCount = true;
        }

        //need check that all category's ids valid on DB
        if ($checkCount === true) {
            $categories = Categories::whereIn('id', $value)->get();
            if (count($categories) === $arrayCount) {
                return true;
            }
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
        return 'Number of categories is not valid. Check their number (2..10 supports) and make sure that all categories are active.';
    }
}

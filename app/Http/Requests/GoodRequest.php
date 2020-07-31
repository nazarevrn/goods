<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //FIXME
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*
         * TODO auto validation using service container
         * https://laravel.com/docs/7.x/validation#quick-writing-the-validation-logic
        */
        //TODO Add number categories validation
        return [
            'name' => 'string',
            'price' => 'numeric|between:0.01,999999.99',
            'is_published' => 'boolean',
        ];
    }
}

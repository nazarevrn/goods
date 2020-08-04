<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class SearchRequest extends FormRequest
{
    public function authorize()
    {
        //FIXME
        return true;
    }
    public function rules()
    {
        return [
            'id' => 'integer',
            'name' => 'string',
            'category_id' => 'integer',
            'categoryName' => 'string',
            'price_from' => 'numeric|between:0.01,999999.99',
            'price_to' => 'numeric|between:0.01,999999.99',
            //TODO не работает нормально
            'is_published' => 'boolean',
            'is_active' => 'boolean'
        ];
    }
}

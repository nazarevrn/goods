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
        ];
    }
}

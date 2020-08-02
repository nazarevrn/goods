<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        //TODO Add number categories validation
        $rules = [
            'name' => 'required|string',
            'price' => 'required|numeric|between:0.01,999999.99',
            'is_published' => 'required|boolean',
        ];

        switch ($this->getMethod())
        {
            case 'POST':
                return $rules;
            case 'PUT' || 'PATCH':
                return [
                        //должен существовать
                        'id' => 'integer|exists:goods,id',
                        'name' => [
                            //должен быть уникальным, за исключением себя же
                            Rule::unique('goods')->ignore($this->name, 'name')
                        ]
                    ];
            case 'DELETE':
                return [
                    'id' => 'required|integer|exists:goods,id'
                ];
        }
    }
}

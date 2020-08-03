<?php

namespace App\Http\Requests;

use App\Rules\CategoriesDeleteRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoriesRequest extends FormRequest
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
        ];

        switch ($this->getMethod())
        {
            case 'POST':
                return $rules;
            case 'PUT' || 'PATCH':
                return [
                    //должна существовать
                    'id' => 'integer|exists:categories,id',
                    'name' => [
                        //должно быть уникальным, за исключением себя же
                        Rule::unique('categories')->ignore($this->name, 'name')
                    ]
                ];
            case 'DELETE':
                return [
                    'id' => ['required|integer|exists:categories,id',
                        new CategoriesDeleteRule()]
                ];
        }
    }
}

<?php

namespace App\Http\Requests;

use App\Categories;
use App\Rules\CategoriesCountRule;
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
        $rules = [
            'name' => 'required|string',
            'price' => 'required|numeric|between:0.01,999999.99',
            'is_published' => 'required|boolean',
        ];

        $categoriesRules = [
            'categories' => [
                'required',
                //array|between|2,10 не работает, пришлось выкручиваться
                new CategoriesCountRule(),
                //TODO сделать проверку одним запросом
            'categories.*' => 'exists:categories,id']
        ];




        switch ($this->getMethod())
        {

            case 'POST':
                return $rules + $categoriesRules;
            case 'PUT' || 'PATCH':
                return [
                        //должен существовать
                        'id' => 'integer|exists:goods,id',
                        'name' => [
                            //должно быть уникальным, за исключением себя же
                            Rule::unique('goods')->ignore($this->name, 'name')
                        ],
                    ] + $categoriesRules;
            case 'DELETE':
                return [
                    'id' => 'required|integer|exists:goods,id'
                ];
        }
    }

}

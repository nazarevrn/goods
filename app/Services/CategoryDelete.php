<?php

namespace App\Services;

use App\Rules\CategoriesDeleteRule;
use Illuminate\Support\Facades\Validator;

class CategoryDelete
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function make()
    {
        Validator::make(['id' => $this->id], ['id' => ['integer', 'exists:categories,id',
            new CategoriesDeleteRule()]])->validate();
    }
}

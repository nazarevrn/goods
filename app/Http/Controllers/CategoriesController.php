<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Http\Requests\CategoriesRequest;
use App\Rules\CategoriesDeleteRule;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Categories::with('goods')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CategoriesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriesRequest $request)
    {
        return Categories::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        return Categories::findOrFail($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CategoriesRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoriesRequest $request, string $id)
    {
        $category = Categories::findOrFail($id);
        $category->fill($request->except(['id']));
        $category->save();
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\CategoriesRequest  $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoriesRequest $request, string $id)
    {
        $category = Categories::findOrFail($id);
        $category->fill(['id' => $id]);
        //почему-то для метода DELETE не хочет работать определенный в rules() код
        $validator = Validator::make(['id' => $id], ['id' => ['integer', 'exists:categories,id',
                        new CategoriesDeleteRule()]])->validate();
        if($category->delete()) {
            return response(null, 204);
        }
    }
}

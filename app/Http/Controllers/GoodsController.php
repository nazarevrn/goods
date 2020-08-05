<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Goods;
use App\Http\Requests\GoodRequest;
use App\Http\Requests\SearchRequest;
use App\Services\SearchGood;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Goods::with('categories')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\GoodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GoodRequest $request)
    {
        $good = new Goods();
        $good->fill($request->except('id'));
        $good->save();
        $good->updateCategories($request->categories);
        $createdGood = $this->getGoodModel($good->id);
        return response($createdGood , 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        return $this->getGoodModel($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\GoodRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GoodRequest $request, string $id)
    {
        $good = $this->getGoodModel($id);
        $good->fill($request->except('id'));
        $good->updateCategories($request->categories);
        $good->save();
        return response()->json($good);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $good = $this->getGoodModel($id);
        $good->updateCategories();
        if($good->delete()) {
            return response(null, 204);
        }
    }

    /**
     * Return good model
     * @param string $id
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return Goods
     */
    private function getGoodModel(string $id)
    {
        return Goods::with('categories')->findOrFail($id);
    }

    /**
     * Search good model by different conditions
     * @param SearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchGood(SearchRequest $request)
    {
        $searchService = new SearchGood($request);
        return response()->json($searchService->make());
    }

}

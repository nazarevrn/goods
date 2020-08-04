<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Goods;
use App\Http\Requests\GoodRequest;
use App\Http\Requests\SearchRequest;
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

    public function searchGood(SearchRequest $request)
    {
        //по-хорошему, надо это вынести в отдельный слой, но не успеваю. прошу понять и простить.
        $result = [];
        $whereConditions = [];

        $categoryId = $request->get('category_id');
        if(!is_null($categoryId)) {
            $result = Goods::whereHas('categories', function (Builder $query) use ($categoryId) {
                $query->where('id', '=', $categoryId);
            });
        }

        $categoryName = $request->get('categoryName');
        if(!is_null($categoryName)) {
            //до $categoryName были заданы другие параметры
            if (!empty($result)) {
                $result->whereHas('categories', function (Builder $query) use ($categoryName) {
                    $query->where('name', 'LIKE', "%{$categoryName}%");
                });
            } else {
                $result = Goods::whereHas('categories', function (Builder $query) use ($categoryName) {
                    $query->where('name', 'LIKE', "%{$categoryName}%");
                });
            }
        }

        $goodName = $request->get('name');
        if (!is_null($goodName)) {
            $whereConditions[] = ['goods.name', 'like', "%{$goodName}%"];
        }

        $priceFrom = $request->get('price_from');
        if (!is_null($priceFrom)) {
            $whereConditions[] = ['goods.price', '>=', $priceFrom];
        }

        $priceTo = $request->get('price_to');
        if (!is_null($priceTo)) {
            $whereConditions[] = ['goods.price', '<=', $priceTo];
        }

        $isPublished = $request->get('is_published');
        if (!is_null($isPublished)) {
            $whereConditions[] = ['goods.is_published', '=', $isPublished];
        }



        $isActive = $request->get('is_active');
        if (!is_null($isActive)) {
            if ($isActive === '0') {
                //нужны помеченные на удаление
                if (!empty($result)) {
                    $result->whereNotNull('goods.deleted_at');
                } else {
                    //нужны только удалённые - другие фильтры не применены
                    $result = Goods::onlyTrashed();
                }
            }
        }


        if (!empty($whereConditions)) {
            if (!empty($result)) {
                $result->where([$whereConditions]);
            } else {
                //по категориям поиска не было
                $result = Goods::where($whereConditions);
            }
        }

        $result = $result->get();
        return response()->json($result);
    }

}

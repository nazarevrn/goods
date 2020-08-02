<?php

namespace App\Http\Controllers;

use App\Goods;
use App\Http\Requests\GoodRequest;

class GoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Goods::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\GoodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GoodRequest $request)
    {
        return Goods::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        return Goods::findOrFail($id);
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

        $good = Goods::findOrFail($id);
        $good->fill($request->except(['id']));
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
        $good = Goods::findOrFail($id);

        if($good->delete()) {
            return response(null, 204);
        }
    }
}

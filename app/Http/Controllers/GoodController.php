<?php

namespace App\Http\Controllers;

use App\Good;
use App\Http\Requests\GoodRequest;

class GoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Good::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\GoodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GoodRequest $request)
    {
        $good = Good::create($request->validated());
        return $good;
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $good = Good::findOrFail($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\GoodRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GoodRequest $request, $id)
    {

        $good = Good::findOrFail($id);
        $good->fill($request->except(['id']));
        $good->save();
        return response()->json($good);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\GoodRequest $request
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodRequest $request, $id)
    {
        $good = Good::findOrFail($id);

        if($good->delete()) {
            return response(null, 204);
        }
    }
}

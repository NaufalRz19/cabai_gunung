<?php

namespace App\Http\Controllers;

use App\Models\Chilli;
use App\Http\Requests\StoreChilliRequest;
use App\Http\Requests\UpdateChilliRequest;
use App\Models\ChilliPrice;

class ChilliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.chilli.index', [
            'chillis' => Chilli::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.chilli.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreChilliRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChilliRequest $request)
    {
        Chilli::create($request->validated());
        return redirect()->route('chillis.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chilli  $chilli
     * @return \Illuminate\Http\Response
     */
    public function show(Chilli $chilli)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chilli  $chilli
     * @return \Illuminate\Http\Response
     */
    public function edit(Chilli $chilli)
    {
        return view('admin.chilli.edit', [
            'chilli' => $chilli
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateChilliRequest  $request
     * @param  \App\Models\Chilli  $chilli
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChilliRequest $request, Chilli $chilli)
    {
        $chilli->update($request->validated());
        return redirect()->route('chillis.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chilli  $chilli
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chilli $chilli)
    {
        $chilli->delete();
        return redirect()->route('chillis.index');
    }

    public function getPrice(Chilli $chilli)
    {
        $latestPrice = ChilliPrice::where('chilli_id', $chilli->id)->latest()->first();
        $price = 0;
        if($latestPrice){
            $price = $latestPrice->price+$chilli->fee;
        }
        return response()->json($price);
    }
}

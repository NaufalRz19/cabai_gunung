<?php

namespace App\Http\Controllers;

use App\Models\ChilliPrice;
use App\Http\Requests\StoreChilliPriceRequest;
use App\Http\Requests\UpdateChilliPriceRequest;
use App\Models\Chilli;
use Illuminate\Http\Request;

class ChilliPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return ChilliPrice::with('chilli')->get()->groupBy('created_at');
        return view('admin.chilli_price.index', [
            'chilliPrices' => ChilliPrice::whereHas('chilli')->with('chilli')->get()->groupBy('created_at')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.chilli_price.create', [
            'chillis' => Chilli::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreChilliPriceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChilliPriceRequest $request)
    {
        $input = $request->validated();
        foreach($input['chilli_id'] as $index => $value){
            ChilliPrice::create([
                'chilli_id' => $value,
                'price' => $input['price'][$index]
            ]);
        }
        return redirect()->route('chilli-price.index')->with('success', 'Chilli prices created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChilliPrice  $chilliPrice
     * @return \Illuminate\Http\Response
     */
    public function show(ChilliPrice $chilliPrice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChilliPrice  $chilliPrice
     * @return \Illuminate\Http\Response
     */
    public function edit($chilli_price)
    {
        $chilliPrices = ChilliPrice::whereDate('created_at', $chilli_price)->get();
        return view('admin.chilli_price.edit', [
            'date' => $chilli_price,
            'chillis' => Chilli::all(),
            'chilliPrices' => $chilliPrices
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateChilliPriceRequest  $request
     * @param  \App\Models\ChilliPrice  $chilliPrice
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateChilliPriceRequest $request, $chilli_price)
    {
        $input = $request->validated();
        ChilliPrice::whereDate('created_at', $chilli_price)->delete();
        foreach($input['chilli_id'] as $index => $value){
            ChilliPrice::create([
                'chilli_id' => $value,
                'price' => $input['price'][$index]
            ]);
        }
        return redirect()->route('chilli-price.index')->with('success', 'Chilli prices updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChilliPrice  $chilliPrice
     * @return \Illuminate\Http\Response
     */
    public function destroy($chilli_price)
    {
        ChilliPrice::whereDate('created_at', $chilli_price)->delete();
        return redirect()->route('chilli-price.index')->with('success', 'Chilli prices deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ProofOfPurchase;
use App\Http\Requests\StoreProofOfPurchaseRequest;
use App\Http\Requests\UpdateProofOfPurchaseRequest;

class ProofOfPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProofOfPurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProofOfPurchaseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProofOfPurchase  $proofOfPurchase
     * @return \Illuminate\Http\Response
     */
    public function show(ProofOfPurchase $proofOfPurchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProofOfPurchase  $proofOfPurchase
     * @return \Illuminate\Http\Response
     */
    public function edit(ProofOfPurchase $proofOfPurchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProofOfPurchaseRequest  $request
     * @param  \App\Models\ProofOfPurchase  $proofOfPurchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProofOfPurchaseRequest $request, ProofOfPurchase $proofOfPurchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProofOfPurchase  $proofOfPurchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProofOfPurchase $proofOfPurchase)
    {
        //
    }
}

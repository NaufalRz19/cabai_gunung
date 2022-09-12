<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\ChilliPrice;
use App\Models\PurchaseDetail;
use App\Models\User;
use App\Traits\HasImage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    use HasImage;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.purchase.index', [
            'purchases' => Purchase::with('user', 'purchaseDetail.chilliPrice.chilli')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.purchase.create', [
            'users' => User::where('status', 'petani')->get(),
            'chilliPrices' => ChilliPrice::whereHas('chilli')->with('chilli')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {

        $input = $request->validated();
        DB::beginTransaction();
        $huruf = 'PMB1234567890';
        $kodeTransaksi = strtoupper(substr($huruf, 0, 7) . date('his'));
        try {
            $purchase = Purchase::create([
                'user_id' => $input['user_id'],
                'purchase_number' => $kodeTransaksi,
                'payment_method' => $input['payment_method']
            ]);
            foreach ($input['chilli_price_id'] as $index => $item) {
                $purchase->purchaseDetail()->create([
                    'chilli_price_id' => $item,
                    'healthy_amount_of_chilies' => $input['healthy_amount_of_chilies'][$index],
                    'number_of_damaged_chilies' => $input['number_of_damaged_chilies'][$index]
                ]);
            }
            if (isset($input['image_url'])) {
                foreach ($input['image_url'] as $index => $item) {
                    $purchase->proofOfPurchase()->create([
                        'image_url' => $this->uploadImage($request->file('image_url')[$index], 'purchase')
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('purchases.index');
        } catch (\Throwable $th) {
            return $th->getMessage();
            DB::rollBack();
            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        return view('admin.purchase.edit', [
            'purchase' => $purchase->load('purchaseDetail', 'proofOfPurchase'),
            'chilliPrices' => ChilliPrice::whereHas('chilli')->with('chilli')->get(),
            'users' => User::where('status', 'petani')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        $input = $request->validated();
        DB::beginTransaction();
        try {
            // $purchase->update([
            //     'user_id' => $input['user_id'],
            //     'purchase_number' => $input['purchase_number'],
            //     'payment_method' => $input['payment_method']
            // ]);
            // PurchaseDetail::where('purchase_id', $purchase->id)->delete();
            // foreach($input['chilli_price_id'] as $index => $item){
            //     $purchase->purchaseDetail()->create([
            //         'chilli_price_id' => $item,
            //         'healthy_amount_of_chilies' => $input['healthy_amount_of_chilies'][$index],
            //         'number_of_damaged_chilies' => $input['number_of_damaged_chilies'][$index]
            //     ]);
            // }
            if (isset($input['image_url'])) {
                foreach ($input['image_url'] as $index => $item) {
                    $purchase->proofOfPurchase()->create([
                        'image_url' => $this->uploadImage($request->file('image_url')[$index], 'purchase')
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('purchases.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            // return $th->getMessage();
            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            foreach ($purchase->proofOfPurchase as $item) {
                $item->delete();
            }
            foreach ($purchase->purchaseDetail as $item) {
                $item->delete();
            }
            $purchase->delete();
            DB::commit();
            return redirect()->route('purchases.index');
        } catch (\Throwable $th) {
            return $th->getMessage();
            DB::rollBack();
            return back()->withErrors($th->getMessage());
        }
    }

    public function getDetailJson(Purchase $purchase)
    {
        return response()->json($purchase->load('user', 'purchaseDetail.chilliPrice.chilli', 'proofOfPurchase'));
    }

    public function printPurchase(Purchase $purchase)
    {
        $pdf = Pdf::loadView('admin.purchase.print', [
            'purchase' => $purchase->load('user', 'purchaseDetail.chilliPrice.chilli')
        ])->setPaper('a4', 'potrait');
        return $pdf->download('purchase.pdf');
    }

    public function printPurchaseByDate(Request $request)
    {
        $date = $request->date;
        $pdf = Pdf::loadView('admin.purchase.print-date', [
            'purchases' => Purchase::with('user', 'purchaseDetail.chilliPrice.chilli')->whereDate('created_at', $date)->get()
        ])->setPaper('a4', 'potrait');
        return $pdf->download('purchase-' . $date . '.pdf');
    }
}
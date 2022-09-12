<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Chilli;
use App\Models\ChilliPrice;
use App\Models\Location;
use App\Models\SaleDetail;
use App\Models\User;
use App\Traits\HasImage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    use HasImage;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.sale.index', [
            'sales' => Sale::whereHas('saleDetail.chilli')->with('saleDetail', 'user')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sale.create', [
            'users' => User::where('status', 'retail')->get(),
            'chillis' => Chilli::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSaleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSaleRequest $request)
    {

        $input = $request->validated();
        DB::beginTransaction();
        $huruf = 'PNJ1234567890';
        $kodeTransaksi = strtoupper(substr($huruf, 0, 7) . date('his'));
        try {
            $salesTotal = 0;
            $sale = Sale::create([
                'user_id' => $input['user_id'],
                'sales_number' => $kodeTransaksi,
                'is_success' => $input['is_success']
            ]);
            foreach ($input['chilli_id'] as $index => $item) {
                $salesTotal += (($input['selling_price'][$index] + 2500) * $input['total'][$index]);
                $sale->saleDetail()->create([
                    'chilli_id' => $item,
                    'selling_price' => $input['selling_price'][$index],
                    'total' => $input['total'][$index]
                ]);
            }
            // foreach ($input['image_url'] as $index => $item) {
            //     $sale->proofOfSale()->create([
            //         'image_url' => $this->uploadImage($request->file('image_url')[$index], 'sale')
            //     ]);
            // }
            $insertLocation = Location::create([
                'user_id' => $input['user_id'],
                'latitude' => $input['lat'],
                'longitude' => $input['long'],
            ]);

            if ($insertLocation) {
                $sale->update([
                    'sales_total' => $salesTotal
                ]);
                DB::commit();
                return redirect()->route('sales.index');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function edit(Sale $sale)
    {
        return view('admin.sale.edit', [
            'users' => User::where('status', 'retail')->get(),
            'chillis' => Chilli::all(),
            'sale' => $sale
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSaleRequest  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        $input = $request->validated();
        DB::beginTransaction();
        try {
            // $salesTotal = 0;
            $sale->update([
                // 'user_id' => $input['user_id'],
                // 'sales_number' => $input['sales_number'],
                'is_success' => $input['is_success']
            ]);
            // SaleDetail::where('sale_id', $sale->id)->delete();
            // foreach($input['chilli_id'] as $index => $item){
            //     $salesTotal += ($input['selling_price'][$index]*$input['total'][$index]);
            //     $sale->saleDetail()->create([
            //         'chilli_id' => $item,
            //         'selling_price' => $input['selling_price'][$index],
            //         'total' => $input['total'][$index]
            //     ]);
            // }
            if (isset($input['image_url'])) {
                foreach ($input['image_url'] as $index => $item) {
                    $sale->proofOfSale()->create([
                        'image_url' => $this->uploadImage($request->file('image_url')[$index], 'sale')
                    ]);
                }
            }
            // $sale->update([
            //     'sales_total' => $salesTotal
            // ]);
            DB::commit();
            return redirect()->route('sales.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        DB::beginTransaction();
        try {
            foreach ($sale->proofOfSale as $item) {
                $item->delete();
            }
            foreach ($sale->saleDetail as $item) {
                $item->delete();
            }
            $sale->delete();
            DB::commit();
            return redirect()->route('sales.index');
        } catch (\Throwable $th) {
            return $th->getMessage();
            DB::rollBack();
            return back()->withErrors($th->getMessage());
        }
    }

    public function getDetailJson(Sale $sale)
    {
        return response()->json($sale->load('user', 'saleDetail.chilli.chilliPrice', 'proofOfSale'));
    }

    public function printSale(Sale $sale)
    {
        $pdf = Pdf::loadView('admin.sale.print', [
            'sale' => $sale->load('user', 'saleDetail.chilli.chilliPrice')
        ])->setPaper('a4', 'potrait');
        return $pdf->download('sale.pdf');
    }

    public function printSaleByDate(Request $request)
    {
        $date = $request->date;
        $pdf = Pdf::loadView('admin.sale.print-date', [
            'sales' => Sale::with('user', 'saleDetail.chilli.chilliPrice')->whereDate('created_at', $date)->get()
        ])->setPaper('a4', 'potrait');
        return $pdf->download('sale-' . $date . '.pdf');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\SaleDetail;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $sehat_tahun = array();
        for($i = 0; $i<12; $i++){
            $sehat_tahun[$i] = PurchaseDetail::whereMonth('created_at', $i+1)->whereYear('created_at', date('Y'))->sum('healthy_amount_of_chilies');
        }
        $rusak_tahun = array();
        for($i = 0; $i<12; $i++){
            $rusak_tahun[$i] = PurchaseDetail::whereMonth('created_at', $i+1)->whereYear('created_at', date('Y'))->sum('number_of_damaged_chilies');
        }
        $laba = 0;
        foreach(SaleDetail::whereDate('created_at', now()->format('Y-m-d'))->get() as $index => $val){
            $laba += ($val->chilli->fee*$val->total);
        };

        $laba_tahun = array();
        for($i = 0; $i<12; $i++){
            $total = 0;
            $sales = SaleDetail::whereMonth('created_at', $i+1)->whereYear('created_at', date('Y'))->get();
            foreach ($sales as $sale) $total += $sale->chilli->fee * $sale->total;
            array_push($laba_tahun, $total);
        }
        return view('admin.dashboard', [
            'cabai_sehat' => PurchaseDetail::whereDate('created_at', now()->format('Y-m-d'))->sum('healthy_amount_of_chilies'),
            'cabai_rusak' => PurchaseDetail::whereDate('created_at', now()->format('Y-m-d'))->sum('number_of_damaged_chilies'),
            'laba' => $laba,
            'sehat_tahun' => $sehat_tahun,
            'rusak_tahun' => $rusak_tahun,
            'laba_tahun' => $laba_tahun,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\ChilliPrice;
use App\Models\Location;
use App\Models\ProofOfPurchase;
use App\Models\ProofOfSale;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\HasImage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client as OClient;

class ApiController
{
    use HasImage;
    public function loginApi(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        $user = User::where('email', $request->email)->where('status', '!=', 'admin')->first();
        if ($user) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $token = $user->createToken('Token Name')->accessToken;
                return response()->json(['success' => 1, 'message' => 'Anda Berhail Login', 'token' => $token]);
            } else {
                return response()->json(['success' => 0, 'message' => 'Anda Gagal Login1']);
            }
        } else {
            return response()->json(['success' => 0, 'message' => 'Anda Gagal Login']);
        }

        // cek user berdasarkan email
        // $user = User::where('email', $request->email)->where('status', '!=', 'admin')->first();
        // if ($user) {
        //     // cek apakah password dari input sudah sesuai dengan password di data user
        //     if (Hash::check($request->password, $user->password)) {

        //         // jika password benar, maka cari data oauth client yang password client nya bernilai true
        //         $oClient = OClient::where('password_client', 1)->first();

        //         // inisiasi guzzle client dengan parameter base_uri
        //         $client = new Client(['base_uri' => request()->root()]);
        //         try {
        //             // request token ke server
        //             $response = $client->request('POST', '/oauth/token', [
        //                 'form_params' => [
        //                     'grant_type' => 'password',
        //                     'client_id' => $oClient->id,
        //                     'client_secret' => $oClient->secret,
        //                     'username' => $request->email,
        //                     'password' => $request->password,
        //                     'scope' => '',
        //                 ]
        //             ]);
        //             return response()->json(json_decode($response->getBody()->getContents()), $response->getStatusCode());
        //         } catch (\Throwable $th) {
        //             return response()->json($th->getMessage(), $th->getCode());
        //         }
        //     } else {
        //         return response()->json('Password salah', 401);
        //     }
        // }
        return response()->json('User tidak ditemukan', 404);
    }
    public function getSaleKurir()
    {
        $sale = Sale::where('is_success', 0)->first();
        $getLacation = Location::where('user_id', $sale->user_id)->orderBy('created_at', 'desc')->get();

        $koor = [
            'lat' => $getLacation[0]->latitude,
            'long' => $getLacation[0]->longitude
        ];
        return response()->json(['success' => 1, 'message' => 'berhasil', 'koor' => $koor]);
    }

    public function apiLogout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();
        return response()->json('Logout berhasil', 200);
    }

    public function showApi(): JsonResponse
    {
        return response()->json(['success' => 1, 'message' => 'berhasil', 'user' => auth()->user()], 200);
    }

    public function updateApi(Request $request)
    {

        $user = User::find(auth()->user()->id);
        // return $user;
        if ($request->pass == $request->con_pass) {
            if (Hash::check($request->pass_old, $user->password)) {
                $user->password = $request->pass;
                if ($user->save()) {
                    return response()->json(['success' => 1, 'message' => 'berhasil'], 200);
                }
            } else {
                return response()->json(['success' => 0, 'message' => 'Password yang ada masukan tidak cocok1']);
            }
        } else {
            return response()->json(['success' => 0, 'message' => 'Password yang ada masukan tidak cocok']);
        }
    }

    public function homeApi()
    {
        $laba = 0;
        $pendapatanBulan = [];
        $cabaiSehat = [];
        if (auth()->user()->status == 'petani') {
            // $purchases = Purchase::where('user_id', auth()->user()->id)->whereDate('created_at', now())->get();
            // foreach ($purchases as $purchase) {
            //     foreach ($purchase->purchaseDetail as $detail) {
            //         $laba += ($detail->chilliPrice->price * $detail->healthy_amount_of_chilies);
            //     }
            // }
            for ($i = 0; $i < 12; $i++) {
                $purchases = Purchase::with('detailPurchase')->whereMonth('created_at', $i + 1)->whereYear('created_at', date('Y'))->where('user_id', auth()->user()->id)->get();
                $aa[] = $purchases;
                $pendapatan = 0;
                $cabai = 0;
                $finalTotal = 0.0;
                if (!$purchases->isEmpty()) {
                    $totalAllItem = 0;
                    foreach ($purchases as $a) {
                        foreach ($a->detailPurchase as $item) {
                            $cabai += $item->healthy_amount_of_chilies;

                            $getCabeRusak = $item->number_of_damaged_chilies;
                            $getCabeSehat = $item->healthy_amount_of_chilies;
                            $totalSehat = $getCabeSehat - $getCabeRusak;

                            $getChiliPrice = $item->chilliPrice->price;

                            $total = $getChiliPrice * $totalSehat;
                        }
                        $totalAllItem += $total;
                        $finalTotal = round($totalAllItem / 1000) * 1000;
                        $bulat = $finalTotal - $totalAllItem;
                    }
                }
                // foreach ($purchases as $purchase) {
                //     foreach ($purchase->purchaseDetail as $detail) {
                //         $pendapatan += ($detail->chilliPrice->price * $detail->healthy_amount_of_chilies);
                //         $cabai += $detail->healthy_amount_of_chilies;
                //     }
                // }

                $pendapatanBulan[] = $finalTotal;
                $cabaiSehat[] = $cabai;
            }
            // return $arr;
            // return $aa;
        } else if (auth()->user()->status == 'retail') {
            $laba += Sale::where('user_id', auth()->user()->id)->whereDate('created_at', now())->where('is_success', 1)->sum('sales_total');
            $packingPrice = 2500;
            $totalAll = 0;

            for ($i = 0; $i < 12; $i++) {
                $pendapatan = Sale::whereMonth('created_at', $i + 1)->whereYear('created_at', date('Y'))->where('user_id', auth()->user()->id)->get();
                // $arrrr[] = $pendapatan;
                $finalTotal = 0.0;
                $cabai = 0;
                if (!$pendapatan->isEmpty()) {

                    foreach ($pendapatan as $pen) {
                        foreach ($pen->saleDetail as $item) {
                            $cabai += $item->total;
                            $totalCabe = $item->total;
                            // $getPriceToday = ChilliPrice::whereDate('created_at', date('Y-m-d'))->get();
                            $getPriceToday = $item->chilli->chilliPrice[0]->price;
                            // return $getPriceToday;
                            $total = $totalCabe * ($getPriceToday + $item->chilli->fee + $packingPrice);

                            $totalAll += $total;
                        }


                        $finalTotal = round($totalAll / 1000) * 1000;
                    }
                }

                $pendapatanBulan[] = $finalTotal;
                $cabaiSehat[] = $cabai;
                // $pendapatan = Sale::whereMonth('created_at', $i + 1)->whereYear('created_at', date('Y'))->where('user_id', auth()->user()->id)->sum('sales_total');
                // $pendapatanBulan[$i] = $pendapatan;
                // $sales = Sale::whereMonth('created_at', $i + 1)->whereYear('created_at', date('Y'))->where('user_id', auth()->user()->id)->get();
                // $cabai = 0;
                // foreach ($sales as $sale) {
                //     foreach ($sale->saleDetail as $item) {
                //         $cabai += $item->total;
                //     }
                // }


            }
            // return $arrrr;
        }
        return response()->json([
            'user' => auth()->user(),
            'pendapatan' => $laba,
            'pendapatanPerbulan' => $pendapatanBulan,
            'hasilCabaiSehat' => $cabaiSehat
        ]);
    }

    public function locationApi(): JsonResponse
    {
        return response()->json(Location::where('user_id', auth()->user()->id)->latest()->first(), 200);
    }

    public function locationApiUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => ['required'],
            'longitude' => ['required']
        ]);
        $location = new Location;
        $location->latitude = $request->latitude;
        $location->longitude = $request->longitude;
        $location->user_id = auth()->user()->id;
        $location->save();
        return response()->json($location, 200);
    }

    public function hargaCabaiApiDate(Request $request): JsonResponse
    {
        $date = date('Y-m-d', strtotime($request->date));
        // return $date;
        $getDataChili = ChilliPrice::select('created_at')->whereDate('created_at', $date)->get();
        // return $getDataChili;
        $arr = array();
        foreach ($getDataChili as $item) {
            if (!in_array($item->created_at, $arr)) {
                $arr[] = $item->created_at;
            }
        }
        return response()->json(['success' => 1, 'message' => 1, 'price' => $arr]);
        // return response()->json(ChilliPrice::with('chilli')->get()->groupBy('created_at'), 200);
    }
    public function orderdate(Request $req)
    {
        $date = date('Y-m-d', strtotime($req->date));

        $getOrder = Purchase::whereDate('created_at', $date)->first();
        $getChili = $getOrder->detailPurchase;
        $arrr = array();
        $totalAllItem = 0;
        foreach ($getChili as $item) {
            $getCabeRusak = $item->number_of_damaged_chilies;
            $getCabeSehat = $item->healthy_amount_of_chilies;
            $totalSehat = $getCabeSehat - $getCabeRusak;

            $getChiliPrice = $item->chilliPrice->price;

            $total = $getChiliPrice * $totalSehat;
            $totalAllItem += $total;
            $arr[] = [
                'name' => $item->chilliPrice->chilli->type_of_chilli,
                'rusak' => $getCabeRusak,
                'sehat' => $getCabeSehat,
                'total_sehat' => $totalSehat,
                'harga' => $getChiliPrice,
                'total' => round($total)
            ];
            $finalTotal = round($totalAllItem / 1000) * 1000;
            $bulat = $finalTotal - $totalAllItem;
        }
        return response()->json(['success' => 1, 'message' => 'berhasil', 'trans' => $arr, 'totalItem' => $totalAllItem, 'bulat' => $bulat, 'totalAll' => $finalTotal, 'code' => $getOrder->purchase_number, 'date' => $getOrder->created_at]);
    }
    public function saleDate(Request $req)
    {
        $date = date('Y-m-d', strtotime($req->date));
        // return $date;
        $getDataPurchase = Sale::select('created_at')->where('user_id', Auth::user()->id)->whereDate('created_at', $date)->get();
        // return $getDataPurchase;
        // return $getDataPurchase;
        $arr = array();
        foreach ($getDataPurchase as $item) {
            if (!in_array($item->created_at, $arr)) {
                $arr[] = $item->created_at;
            }
        }
        return response()->json(['success' => 1, 'message' => 1, 'price' => $arr]);
    }
    public function uploadTransaction(Request $req)
    {
        $fotoSertif = $this->uploadImage($req->file('image'), 'sale');
        $getSaleId = Sale::where([['id', $req->id]])->first()->id;
        $insertProof = ProofOfSale::create([
            'sale_id' => $getSaleId,
            'image_url' => $fotoSertif,
        ]);
        if ($insertProof) {
            return response()->json(['success' => 1, 'message' => 'berhasil']);
        } else {
            return response()->json(['success' => 0, 'message' => 'gagal']);
        }
    }
    public function getComissionToday(Request $req)
    {
        if (Auth::user()->status == "petani") {
            $name = Auth::user()->name;
            $getOrder = Purchase::whereDate('created_at', date('Y-m-d'))->first();
            if ($getOrder) {
                $getChili = $getOrder->detailPurchase;
                $arrr = array();
                $totalAllItem = 0;
                foreach ($getChili as $item) {
                    $getCabeRusak = $item->number_of_damaged_chilies;
                    $getCabeSehat = $item->healthy_amount_of_chilies;
                    $totalSehat = $getCabeSehat - $getCabeRusak;

                    $getChiliPrice = $item->chilliPrice->price;

                    $total = $getChiliPrice * $totalSehat;
                    $totalAllItem += $total;

                    $finalTotal = round($totalAllItem / 1000) * 1000;
                    $bulat = $finalTotal - $totalAllItem;
                }
                return response()->json(['success' => 1, 'message' => 'berhasil', 'totalAll' => $finalTotal, 'name' => $name]);
            } else {
                return response()->json(['success' => 0, 'message' => 'belum ada pendapatan hari ini', 'totalAll' => 0, 'name' => $name]);
            }
        } elseif (Auth::user()->status == "retail") {
            $getSales = Sale::whereDate('created_at', date('Y-m-d'))->first();
            // return $getSales;
            if ($getSales) {
                $getDetail = $getSales->saleDetail;
                // return $getDetail;
                $arr = array();
                $totalAll = 0;
                $packingPrice = 2500;
                foreach ($getDetail as $item) {
                    $totalCabe = $item->total;
                    // $getPriceToday = ChilliPrice::whereDate('created_at', date('Y-m-d'))->get();
                    $getPriceToday = $item->chilli->chilliPrice[0]->price;
                    // return $getPriceToday;
                    $kilo = $item->selling_price + $packingPrice;
                    $total = $totalCabe * ($getPriceToday + $item->chilli->fee + $packingPrice);

                    $totalAll += $total;
                    $arr[] = [
                        'name' => $item->chilli->type_of_chilli,
                        'total_cabe' => $totalCabe,
                        'harga' => $getPriceToday,
                        'kilo' => $kilo,
                        'total' => $total

                    ];
                    $finalTotal = round($totalAll / 1000) * 1000;
                    $bulat = $finalTotal - $totalAll;
                }
                // return $arrr;
                return response()->json(['success' => 1, 'message' => 1, 'totalAll' => $finalTotal, 'name' => Auth::user()->name]);
            } else {
                return response()->json(['success' => 0, 'message' => 0, 'totalAll' => 0, 'name' => Auth::user()->name]);
            }
        }
    }
    public function detailPrice(Request $req)
    {
        $date = date('Y-m-d', strtotime($req->date));
        $getDetailPrice = ChilliPrice::with('chilli')->whereDate('created_at', $date)->get();
        if (!$getDetailPrice->isEmpty()) {
            return response()->json(['success' => 1, 'message' => 'berhasil',  'price_detail' => $getDetailPrice]);
        }
    }
    public function getSalaryToday()
    {
    }
    public function purchaseDate(Request $request)
    {
        $date = date('Y-m-d', strtotime($request->date));
        // return $date;
        $getDataPurchase = Purchase::select('created_at')->where('user_id', Auth::user()->id)->whereDate('created_at', $date)->get();
        // return $getDataPurchase;
        // return $getDataPurchase;
        $arr = array();
        foreach ($getDataPurchase as $item) {
            if (!in_array($item->created_at, $arr)) {
                $arr[] = $item->created_at;
            }
        }
        return response()->json(['success' => 1, 'message' => 1, 'price' => $arr]);
    }
    public function hargaCabaiApi(): JsonResponse
    {
        $getDataChili = ChilliPrice::select('created_at')->distinct('created_at')->get();
        $arr = array();
        foreach ($getDataChili as $item) {
            if (!in_array($item->created_at, $arr)) {
                $arr[] = $item->created_at;
            }
        }
        return response()->json(['success' => 1, 'message' => 1, 'price' => $arr]);
        // return response()->json(ChilliPrice::with('chilli')->get()->groupBy('created_at'), 200);
    }
    public function sales()
    {
        if (Auth::user()->status == "retail") {
            $getDataChili = Sale::select('created_at')->where('user_id', Auth::user()->id)->distinct('created_at')->get();
            // return $getDataChili;
            $arr = array();
            foreach ($getDataChili as $item) {
                if (!in_array($item->created_at, $arr)) {
                    $arr[] = $item->created_at;
                }
            }
            return response()->json(['success' => 1, 'message' => 1, 'price' => $arr]);
        }

        // return response()->json(ChilliPrice::with('chilli')->get()->groupBy('created_at'), 200);
    }
    public function salesDate(Request $req)
    {
        $date = date('Y-m-d', strtotime($req->date));
        $getSales = Sale::whereDate('created_at', $date)->first();
        $getDetail = $getSales->saleDetail;
        // return $getDetail;
        $arr = array();
        $totalAll = 0;
        $packingPrice = 2500;
        foreach ($getDetail as $item) {
            $totalCabe = $item->total;
            // $getPriceToday = ChilliPrice::whereDate('created_at', date('Y-m-d'))->get();
            $getPriceToday = $item->chilli->chilliPrice[0]->price;
            // return $getPriceToday;
            $kilo = $item->selling_price + $packingPrice;
            $total = $totalCabe * ($getPriceToday + $item->chilli->fee + $packingPrice);

            $totalAll += $total;
            $arr[] = [
                'name' => $item->chilli->type_of_chilli,
                'total_cabe' => $totalCabe,
                'harga' => $getPriceToday,
                'kilo' => $kilo,
                'total' => $total

            ];
            $finalTotal = round($totalAll / 1000) * 1000;
            $bulat = $finalTotal - $totalAll;
        }
        return response()->json(['success' => 1, 'message' => 1, 'sales' => $arr, 'id_sale' => $getSales->id, 'bulat' => $bulat, 'status' => $getSales->is_success, 'totalItem' => $totalAll, 'totalAll' => $finalTotal, 'date' => $getSales->created_at, 'code' => $getSales->sales_number]);
    }
    public function getPurchase(): JsonResponse
    {
        $getDataChili = Purchase::select('created_at')->where('user_id', Auth::user()->id)->get();
        // return $getDataChili;
        $arr = array();
        foreach ($getDataChili as $item) {
            if (!in_array($item->created_at, $arr)) {
                $arr[] = $item->created_at;
            }
        }
        return response()->json(['success' => 1, 'message' => 1, 'price' => $arr]);
        // return response()->json(ChilliPrice::with('chilli')->get()->groupBy('created_at'), 200);
    }
    public function validasiUser()
    {
        $getUser = Auth::user()->status;
        // return $getUser;
        if ($getUser == "petani") {
            return response()->json(['success' => 1, 'message' => 'Anda Seorang Petani']);
        } else {
            return response()->json(['success' => 2, 'message' => 'Anda Seorang Retail']);
        }
    }

    public function purchase(): JsonResponse
    {
        if (auth()->user()->status == 'retail') {
            return response()->json(Purchase::with('user', 'purchaseDetail.chilliPrice.chilli')->get(), 200);
        }
        return response()->json(Purchase::with('user', 'purchaseDetail.chilliPrice.chilli')->where('user_id', auth()->user()->id)->get(), 200);
    }

    public function sale(): JsonResponse
    {
        if (auth()->user()->status == 'petani') {
            return response()->json(Sale::with('user.location', 'saleDetail.chilli.chilliPrice', 'proofOfSale')->get());
        }
        return response()->json(Sale::with('user.location', 'saleDetail.chilli.chilliPrice', 'proofOfSale')->where('user_id', auth()->user()->id)->get());
    }

    public function saleSuccess(): JsonResponse
    {
        return response()->json(Sale::with('user.location', 'saleDetail.chilli.chilliPrice', 'proofOfSale')->where('user_id', auth()->user()->id)->where('is_success', true)->get());
    }

    public function successSale(Request $request, Sale $sale): JsonResponse
    {
        $sales = $sale->update([
            'is_success' => '1'
        ]);
        if ($sales) {
            return response()->json(['success' => 1, 'message' => 'Data berhasil diselesaikan']);
        } else {
            return response()->json(['success' => 0, 'message' => 'Data gagal di selesaikan']);
        }
    }
}
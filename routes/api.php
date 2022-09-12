<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/login', [ApiController::class, 'loginApi']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [ApiController::class, 'logoutApi']);
    Route::post('/user/update', [ApiController::class, 'updateApi']);
    Route::get('/user', [ApiController::class, 'showApi']);

    Route::get('/home', [ApiController::class, 'homeApi']);
    Route::get('/location', [ApiController::class, 'locationApi']);
    Route::post('/location', [ApiController::class, 'locationApiUpdate']);
    Route::get('/chilli-price', [ApiController::class, 'hargaCabaiApi']);
    Route::post('/chilli-price-date', [ApiController::class, 'hargaCabaiApiDate']);
    Route::post('/purchase-date', [ApiController::class, 'purchaseDate']);
    Route::post('/sale-date', [ApiController::class, 'saleDate']);
    Route::post('/sales-date', [ApiController::class, 'salesDate']);
    Route::get('/validasi-user', [ApiController::class, 'validasiUser']);
    Route::get('/sales', [ApiController::class, 'sales']);
    Route::get('/get-purchase', [ApiController::class, 'getPurchase']);
    Route::post('/order-date', [ApiController::class, 'orderdate']);
    Route::post('/detail-price', [ApiController::class, 'detailPrice']);
    Route::get('/get-salary-today', [ApiController::class, 'getSalaryToday']);
    Route::get('/get-comission-today', [ApiController::class, 'getComissionToday']);
    Route::get('/purchase', [ApiController::class, 'purchase']);
    Route::get('/sale', [ApiController::class, 'sale']);
    Route::get('/get-sale-kurir', [ApiController::class, 'getSaleKurir']);
    Route::get('/sale/success', [ApiController::class, 'saleSuccess']);
    Route::post('/sale/{sale}/success', [ApiController::class, 'successSale']);
    Route::post('/upload-transaction', [ApiController::class, 'uploadTransaction']);
});
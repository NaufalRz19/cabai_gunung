<?php

use App\Http\Controllers\ChilliController;
use App\Http\Controllers\ChilliPriceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseDetailController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', HomeController::class)->name('dashboard');
    Route::resource('users', UserController::class);
    Route::get('user', [UserController::class, 'userByStatus'])->name('user.by_status');
    Route::resource('chillis', ChilliController::class);
    Route::resource('chilli-price', ChilliPriceController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::resource('purchase-details', PurchaseDetailController::class);
    Route::resource('sales', SaleController::class);

    Route::get('/purchase/{purchase}', [PurchaseController::class, 'getDetailJson'])->name('json_purchase');
    Route::get('/purchase/{purchase}/print', [PurchaseController::class, 'printPurchase'])->name('print_purchase');
    Route::post('/purchase/print', [PurchaseController::class, 'printPurchaseByDate'])->name('print_purchase_by_date');

    Route::get('/chilli/{chilli}/get-price', [ChilliController::class, 'getPrice'])->name('chilli.getPrice');
    Route::get('/sale/{sale}', [SaleController::class, 'getDetailJson'])->name('json_sale');
    Route::get('/sale/{sale}/print', [SaleController::class, 'printSale'])->name('print_sale');
    Route::post('/sale/print', [SaleController::class, 'printSaleByDate'])->name('print_sale_by_date');
});
Route::get('/lokasi-kurir', function () {
    return view('admin.lokasi-kurir');
})->name('lokasi_kurir');

require __DIR__ . '/auth.php';

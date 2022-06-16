<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
        
    Route::get('/cortes', [App\Http\Controllers\CashOutController::class, 'index'])->name('cortes');
    Route::get('/cortes/get', [App\Http\Controllers\CashOutController::class, 'get']);
    Route::post('/cortes/store', [App\Http\Controllers\CashOutController::class, 'store']);
    Route::put('/cortes/update', [App\Http\Controllers\CashOutController::class, 'update']);
    Route::put('/cortes/activar', [App\Http\Controllers\CashOutController::class, 'activar']);
    Route::put('/cortes/desactivar', [App\Http\Controllers\CashOutController::class, 'desactivar']);


    
    
    Route::get('/insumos', [App\Http\Controllers\ProductController::class, 'index'])->name('insumos');
    
    Route::get('/insumos/get', [App\Http\Controllers\ProductController::class, 'get']);
    Route::post('/insumos/store', [App\Http\Controllers\ProductController::class, 'store']);
    Route::put('/insumos/update', [App\Http\Controllers\ProductController::class, 'update']);
    Route::put('/insumos/activar', [App\Http\Controllers\ProductController::class, 'activar']);
    Route::put('/insumos/desactivar', [App\Http\Controllers\ProductController::class, 'desactivar']);
    Route::get('/insumos/exportar', [App\Http\Controllers\ProductController::class, 'export']);
    
    
    Route::get('/insumos/inventory/get/{product_id}', [App\Http\Controllers\ProductsInventoryController::class, 'getInventoriesProduct']);
    Route::post('/insumos/inventory/store', [App\Http\Controllers\ProductsInventoryController::class, 'store']);
    
    Route::get('/gastos', [App\Http\Controllers\ExpenseController::class, 'index'])->name('gastos');
    Route::get('/gastos/get', [App\Http\Controllers\ExpenseController::class, 'get']);
    Route::post('/gastos/store', [App\Http\Controllers\ExpenseController::class, 'store']);
});#./Middlware AUTH
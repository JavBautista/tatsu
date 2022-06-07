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

Route::get('/cortes', [App\Http\Controllers\CashOutController::class, 'index'])->name('cortes');
Route::get('/gastos', [App\Http\Controllers\ExpenseController::class, 'index'])->name('gatos');
Route::get('/insumos', [App\Http\Controllers\ProductController::class, 'index'])->name('insumos');

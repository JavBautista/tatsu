<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FixedExpensesController;
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

    Route::get('/gastos-fijos', [FixedExpensesController::class, 'index']);
    Route::get('/gastos-fijos/get', [FixedExpensesController::class, 'get']);
    Route::post('/gastos-fijos/store', [FixedExpensesController::class, 'store']);
    Route::put('/gastos-fijos/update', [FixedExpensesController::class, 'update']);
    Route::put('/gastos-fijos/active', [FixedExpensesController::class, 'activar']);
    Route::put('/gastos-fijos/inactive', [FixedExpensesController::class, 'desactivar']);

    /*PERSONAL*/
    Route::get('/personal', [App\Http\Controllers\EmployeeController::class, 'index'])->name('personal');
    Route::get('/personal/get/all/active', [App\Http\Controllers\EmployeeController::class, 'getAllActive']);
    Route::get('/personal/get', [App\Http\Controllers\EmployeeController::class, 'get']);
    Route::post('/personal/store', [App\Http\Controllers\EmployeeController::class, 'store']);
    Route::put('/personal/update', [App\Http\Controllers\EmployeeController::class, 'update']);
    Route::put('/personal/active', [App\Http\Controllers\EmployeeController::class, 'active']);
    Route::put('/personal/inactive', [App\Http\Controllers\EmployeeController::class, 'inactive']);



    /*TIPOS DE PERSONAL*/
    Route::get('/tipos-personal', [App\Http\Controllers\TypesEmployeeController::class, 'index'])->name('tipos-personal');
    Route::get('/tipos-personal/get', [App\Http\Controllers\TypesEmployeeController::class, 'get']);
    Route::post('/tipos-personal/store', [App\Http\Controllers\TypesEmployeeController::class, 'store']);
    Route::put('/tipos-personal/update', [App\Http\Controllers\TypesEmployeeController::class, 'update']);
    Route::put('/tipos-personal/active', [App\Http\Controllers\TypesEmployeeController::class, 'active']);
    Route::put('/tipos-personal/inactive', [App\Http\Controllers\TypesEmployeeController::class, 'inactive']);

    /*REPORTES*/

    Route::get('/reporte/test-fechas', [App\Http\Controllers\ReportsController::class, 'testFechasCarbon']);

    Route::get('/reporte/corte-semanal', [App\Http\Controllers\ReportsController::class, 'corteSemanal']);
    Route::get('/reporte/corte-mensual', [App\Http\Controllers\ReportsController::class, 'corteMensual']);

    Route::get('/reporte/corte-semanal/get', [App\Http\Controllers\ReportsController::class, 'getCorteSemanal']);
    Route::get('/reporte/corte-mensual/get', [App\Http\Controllers\ReportsController::class, 'getCorteMensual']);

    Route::get('/reporte/facturas', [App\Http\Controllers\ReportsController::class, 'facturas']);
    Route::get('/reporte/facturas/get', [App\Http\Controllers\ReportsController::class, 'getFacturas']);

    /*NOMINA*/
    Route::get('/nomina/captura', [App\Http\Controllers\PayrollController::class, 'index']);

    Route::get('/nomina/get', [App\Http\Controllers\PayrollController::class, 'get']);
    Route::post('/nomina/store', [App\Http\Controllers\PayrollController::class, 'store']);
    Route::put('/nomina/update', [App\Http\Controllers\PayrollController::class, 'update']);
    Route::put('/nomina/disable', [App\Http\Controllers\PayrollController::class, 'disable']);

});#./Middlware AUTH
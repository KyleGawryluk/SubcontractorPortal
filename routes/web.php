<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContractController;

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

Route::get('/', [HomeController::class, 'index']);

Route::post('/login', [HomeController::class, 'login']);
Route::get('/logout', [HomeController::class, 'logout']);

Route::get('/contracts', [ContractController::class, 'getContracts']);
Route::get('/contract/{id}', [ContractController::class, 'getContract']);

Route::post('/invoice', [ContractController::class, 'createInvoice']);


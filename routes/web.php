<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContractController;
use App\Http\Middleware\CheckCookie;
use App\Http\Middleware\UserDetails;

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
Route::get('/change-pw', [HomeController::class, 'passwordChange']);
Route::post('/change-pw', [HomeController::class, 'changePW']);

Route::get('/oauth-login', [HomeController::class, 'oauthLogin']);

Route::controller(ContractController::class)->middleware(['cookie','details'])->group(function () {

	Route::get('/contracts','getContracts');
	Route::get('/contract/{id}','getContract');
	Route::get('/contract/pdf/{id}','printContract');
	Route::post('/contract/accept','acceptContract');
	Route::post('/contract/file','uploadFile');
	Route::get('/contract/file/{id}/{filename}','getFile');

	Route::post('/bill/file','uploadBillFile');
	Route::get('/bill/file/{id}/{filename}','getBillFile');

	Route::post('/invoice','createInvoice');
	Route::get('/invoice/pdf/{id}','printInvoice');

	Route::get('/co/pdf/{id}','printCO');
});

Route::controller(HomeController::class)->middleware(['cookie'])->group(function () {

	Route::get('/mirror','mirror');
	Route::post('/mirror','loginAs');

});





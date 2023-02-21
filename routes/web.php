<?php

use App\Http\Controllers\AuthController;
use Illuminate\Routing\Router;
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

Route::get('/login', [AuthController::class, 'login']);

// Route::get('/',[AuthController::class , 'index'])->middleware('verify.shopify')->name('home');

// Please keep this route last
Route::controller(AuthController::class)->group(function (Router $router) {
    $router->get('/', 'index')->middleware('verify.shopify')->name('home');
    $router->get('/{any}', 'index')->middleware('verify.shopify')->where('any', '(.+)?');
});

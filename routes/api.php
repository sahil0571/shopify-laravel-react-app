<?php

use App\Http\Controllers\API\PlanController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Billing and Recurring Charge API's route */
Route::controller(PlanController::class)->group(function () {

    Route::post('planchange','planChange');
    Route::get('change-plan-db','changePlanDB');
    Route::get('planlist','index');
});

/* Current User data access API*/
Route::get('getUser/{shopName}',[AuthController::class,'getUser']);


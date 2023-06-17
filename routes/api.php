<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('surcharges')->group(function () {
    Route::get('/getAll', ['App\Http\Controllers\Api\SurchargesController', 'getAll']);
    Route::get('/getAllFathers', ['App\Http\Controllers\Api\SurchargesController', 'getAllFathers']);
    Route::post('/updateExcel', ['App\Http\Controllers\Api\SurchargesController', 'updateExcel']);
    Route::post('/joinGroups', ['App\Http\Controllers\Api\SurchargesController', 'joinGroups']);
});


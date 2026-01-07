<?php

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
| Note: Module-specific routes are loaded via their respective
| RouteServiceProviders (e.g., Modules\Users\Providers\RouteServiceProvider)
|
*/

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'cors' => 'enabled']);
});

Route::post('/test-cors', function () {
    return response()->json(['message' => 'CORS is working!']);
});

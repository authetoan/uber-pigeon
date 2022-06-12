<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PigeonController;

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

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'createToken']);
    Route::middleware('auth:sanctum')->delete('/revoke', [AuthController::class, 'revoke']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->post('/register-pigeon-profile', [AuthController::class, 'registerPigeonProfile']);
});

Route::prefix('order')->group(function () {
    Route::middleware('auth:sanctum')->post('/', [PigeonController::class, 'order']);
    Route::middleware('auth:sanctum')->put('/{order}', [PigeonController::class, 'updateOrderStatus']);
});

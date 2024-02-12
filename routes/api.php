<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RedirectController;
use App\Http\Controllers\RedirectLogsController;

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

Route::group(['prefix' => 'redirects'], function () {
    Route::get('/', [RedirectController::class, 'show']);
    Route::get('/{code}', [RedirectController::class, 'show']);
    Route::post('/', [RedirectController::class, 'store']);
    Route::put('/{code}', [RedirectController::class, 'update']);
    Route::delete('/{code}', [RedirectController::class, 'destroy']);
    Route::get('/{code}/stats', [RedirectLogsController::class, 'stats']);
    Route::get('/{code}/logs', [RedirectLogsController::class, 'logs']);
});

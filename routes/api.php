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

// Route::middleware(middleware: 'auth:sanctum')->get(uri: '/user', action: function (Request $request): mixed {
//     return $request->user();
// });

# Hanya '/users' karena di RouteServiceProvider sudah diberi prefix 'api', membuat uri menjadi '/api/users' di unit test.
# All uri here to be accessed needs prefix '/api'.

Route::post('/users', [App\Http\Controllers\Model\UserController::class, 'register']);

Route::post('/users/login', [App\Http\Controllers\Model\UserController::class, 'login']);

Route::middleware(App\Http\Middleware\ApiAuthMiddleware::class)->group(function () {
    Route::get('/users/current', [\App\Http\Controllers\Model\UserController::class, 'get']);
    Route::patch('/users/current', [\App\Http\Controllers\Model\UserController::class, 'update']);
    Route::delete('/users/logout', [\App\Http\Controllers\Model\UserController::class, 'logout']);
});

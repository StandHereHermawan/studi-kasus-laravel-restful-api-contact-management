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

/*
|--------------------------------------------------------------------------
| API Routes from Laravel Vendor Authorization Default
|--------------------------------------------------------------------------
|
| Route::middleware(middleware: 'auth:sanctum')->get(uri: '/user', action: function (Request $request): mixed {
|     return $request->user();
| });
|
*/

/*
|--------------------------------------------------------------------------
| Catatan
|--------------------------------------------------------------------------
|
| Hanya '/users' karena di RouteServiceProvider sudah diberi prefix 'api', membuat uri menjadi '/api/users' di unit test.
| All uri here to be accessed needs prefix '/api'.
|
*/

Route::post('/users', [App\Http\Controllers\Model\UserController::class, 'register']);

Route::post('/users/login', [App\Http\Controllers\Model\UserController::class, 'login']);

Route::middleware(App\Http\Middleware\ApiAuthMiddleware::class)->group(function () {
    # Users API
    Route::get('/users/current', [\App\Http\Controllers\Model\UserController::class, 'get']);
    Route::patch('/users/current', [\App\Http\Controllers\Model\UserController::class, 'update']);
    Route::delete('/users/logout', [\App\Http\Controllers\Model\UserController::class, 'logout']);

    # Contact API
    Route::post('/contacts', [App\Http\Controllers\Model\ContactController::class, 'create']);
    Route::get('/contacts', [App\Http\Controllers\Model\ContactController::class, 'search']);
 /* Route::get('/contacts/{id:[0-9]}', [App\Http\Controllers\Model\ContactController::class, 'get']); */
    Route::get('/contacts/{id}', [App\Http\Controllers\Model\ContactController::class, 'get'])->where('id', '[0-9]+');
    Route::put('/contacts/{id}', [App\Http\Controllers\Model\ContactController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/contacts/{id}', [App\Http\Controllers\Model\ContactController::class, 'delete'])->where('id', '[0-9]+');

    # Address API
    Route::post('/contacts/{idContact}/addresses', [App\Http\Controllers\Model\AddressController::class, 'create'])->where('idContact', '[0-9]+');
});

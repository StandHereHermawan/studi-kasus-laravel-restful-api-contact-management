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
Route::post('/users', [App\Http\Controllers\Model\UserController::class, 'register']);

Route::post('/users/login', [App\Http\Controllers\Model\UserController::class, 'login']);

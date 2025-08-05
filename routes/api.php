<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

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

Route::get('/users', [UserController::class, 'index']); // Show all users
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users-by-role/{role}', [UserController::class, 'getUsersByRole']); // Show users by role


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

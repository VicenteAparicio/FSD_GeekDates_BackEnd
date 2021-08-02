<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\UserController;

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

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

Route::middleware('auth:api')->group(function() {

    // USER ROUTES
    Route::post('updateuser', [UserController::class, 'update']);
    Route::post('deleteuser', [UserController::class, 'destroy']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('searchAll', [UserController::class, 'searchAll']);
    Route::post('userbyname', [UserController::class, 'userByName']);
    // EXCLUSIVE ADMIN ROUTES
    Route::get('allusers', [UserController::class, 'allUsers']);
    Route::get('activeusers', [UserController::class, 'activeUsers']);
    Route::post('userbyid', [UserController::class, 'userById']);
    
});

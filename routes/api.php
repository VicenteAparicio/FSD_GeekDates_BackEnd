<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HobbieController;
use App\Http\Controllers\LoverController;

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
    Route::post('updateinfo', [UserController::class, 'update']);
    Route::post('deleteuser', [UserController::class, 'destroy']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('allplayers', [UserController::class, 'allPlayers']);
    Route::post('userbyname', [UserController::class, 'userByName']);
    Route::post('defaultsearch', [UserController::class, 'defaultSearch']);
    
    // EXCLUSIVE ADMIN ROUTES
    Route::get('allusers', [UserController::class, 'allUsers']);
    Route::get('activeusers', [UserController::class, 'activeUsers']);
    Route::post('userbyid', [UserController::class, 'userById']);

    // HOBBIE ROUTES
    Route::post('fillhobbies', [HobbieController::class, 'store']);
    Route::post('updatehobbies', [HobbieController::class, 'update']);

    // LOVER ROUTES
    Route::post('match', [LoverController::class, 'store']);
    Route::post('unmatch', [LoverController::class, 'destroy']);
    
});

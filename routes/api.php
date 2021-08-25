<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HobbieController;
use App\Http\Controllers\LoverController;
use App\Http\Controllers\MessageController;

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

Route::group(['middleware' => ['cors']], function () {

    Route::post('register', [PassportAuthController::class, 'register']);
    Route::post('login', [PassportAuthController::class, 'login']);

    Route::middleware('auth:api')->group(function() {


        // USER ROUTES
        Route::post('updateinfo', [UserController::class, 'update']);
        Route::post('deleteuser', [UserController::class, 'destroy']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::post('userbyname', [UserController::class, 'userByName']);
        Route::post('defaultsearch', [UserController::class, 'defaultSearch']);
        Route::post('lovermatches', [UserController::class, 'loverMatches']);
        
        // EXCLUSIVE ADMIN ROUTES
        Route::get('allusers', [UserController::class, 'allUsers']);
        Route::get('activeusers', [UserController::class, 'activeUsers']);
        Route::post('userbyid', [UserController::class, 'userById']);

        // HOBBIE ROUTES
        Route::post('hobbies', [HobbieController::class, 'store']);
        Route::post('updatehobbies', [HobbieController::class, 'update']);

        // LOVER ROUTES
        Route::post('match', [LoverController::class, 'store']);
        Route::post('unmatch', [LoverController::class, 'destroy']);

        // MESSAGE ROUTES
        Route::post('newmessage', [MessageController::class, 'store']);
        Route::post('checkmessage', [MessageController::class, 'check']);
        
    });

});
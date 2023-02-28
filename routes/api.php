<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\LeagueController;
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

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::group([
    'middleware' => [
        'auth:sanctum',
    ]
], function () {
    Route::get('/getUsers', [UserController::class, 'getUsers']);
    Route::get('/getLeagues', [LeagueController::class, 'getLeagues']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/league', [LeagueController::class, 'store']);
    Route::post('/joinLeague', [LeagueController::class, 'joinLeague']);
});
<?php

use App\Http\Controllers\Api\ArusKasController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventKasController;
use App\Http\Controllers\Api\PengajarController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\UserGroupsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('logoutall', [AuthController::class, 'logoutall']);

    Route::get('user', [UserController::class, 'index']);
    Route::get('user/{id}', [UserController::class, 'getDataById']);
    Route::get('current/user', [UserController::class, 'getCurrentUser']);
    Route::post('user', [UserController::class, 'create']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::put('user/update/password', [UserController::class, 'updatePassword']);
    Route::delete('user/{id}', [UserController::class, 'delete']);

    Route::get('pengajar', [PengajarController::class, 'index']);
    Route::get('pengajar/{id}', [PengajarController::class, 'getDataById']);
    Route::post('pengajar', [PengajarController::class, 'create']);
    Route::put('pengajar/{id}', [PengajarController::class, 'update']);
    Route::delete('pengajar/{id}', [PengajarController::class, 'delete']);

    Route::get('event-kas', [EventKasController::class, 'index']);
    Route::get('event-kas/{id}', [EventKasController::class, 'getDataById']);
    Route::post('event-kas', [EventKasController::class, 'create']);
    Route::put('event-kas/{id}', [EventKasController::class, 'update']);

    Route::get('group/', [GroupController::class, 'index']);
    Route::get('group/{user_id}', [GroupController::class, 'getDataByUserId']);
    Route::post('group', [GroupController::class, 'create']);
    Route::put('group/{id}', [GroupController::class, 'update']);

    Route::get('user-group/{user_id}', [UserGroupsController::class, 'index']);
    Route::post('user-group', [UserGroupsController::class, 'create']);
    Route::put('user-group/{id}', [UserGroupsController::class, 'update']);

    Route::get('arus-kas/{id}', [ArusKasController::class, 'index']);
    Route::get('arus-kas/{id}/{user_id}', [ArusKasController::class, 'getDataPerUser']);
    Route::get('arus-kas/event/{id}/list', [ArusKasController::class, 'getDataPerEvent']);
    Route::get('arus-kas/event/{id}/list-and-month', [ArusKasController::class, 'getDataPerEventDanBulan']);
    Route::get('arus-kas/list/belum-bayar/{id}', [ArusKasController::class, 'getBelumBayarKas']);
    Route::post('arus-kas', [ArusKasController::class, 'create']);
    Route::put('arus-kas/{id}', [ArusKasController::class, 'update']);
    Route::delete('arus-kas/{id}', [ArusKasController::class, 'delete']);
});

<?php

use App\Http\Controllers\ApiUser;
use App\Http\Controllers\UserAuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//---------------------------------Student-----------------------------------//
//--public

Route::prefix('student')->group(function () {
    Route::post('/login', [ApiUser::class, 'loginPost'])->name('user_login');
    Route::post('isAuthenticated', [ApiUser::class, 'isAuthenticated'])->name('isAuthenticated')->middleware('auth:sanctum');
    Route::get('/logout', [ApiUser::class, 'logout'])->name('user_logout')->middleware('auth:sanctum');
    Route::get('/student', function (Request $request){
        return $request->user();
    })->middleware('auth:sanctum');
});

//--private

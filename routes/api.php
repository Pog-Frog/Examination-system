<?php

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
    Route::post('/signup', [UserAuthController::class, 'signup'])->name('api_login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('api_login');
    Route::get('/logout', [UserAuthController::class, 'logout'])->name('api_logout')->middleware('auth:sanctum');
    Route::get('/student', function (Request $request){
        return $request->user();
    })->middleware('auth:sanctum');
});

//--private

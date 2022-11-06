<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorAuthController;
use App\Http\Controllers\InstructorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::post('/', [HomeController::class, 'contactUs'])->name('contactUs');

//---------------------------------Student-----------------------------------//
//--public
Route::prefix('student')->group(function () {
    Route::get('/login', [UserAuthController::class, 'login'])->name('student_login');
    Route::post('/login', [UserAuthController::class, 'loginPost'])->name('student_login.post');
    Route::get('/register', [UserAuthController::class, 'register'])->name('student_register');
    Route::post('/register', [UserAuthController::class, 'registerPost'])->name('student_register.post');
});

//--private
Route::prefix('student')->group(function () {
    Route::get('/logout', [UserAuthController::class, 'logout'])->name('student_logout');
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('student_dashboard');
});


//---------------------------------Instructor-----------------------------------//

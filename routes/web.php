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
Route::get('/ContactUs', [HomeController::class, 'contactUs'])->name('contactUs');
Route::post('/ContactUs', [HomeController::class, 'contactUsPost'])->name('contactUs.post');

//---------------------------------Student-----------------------------------//
//--public
Route::prefix('student')->group(function () {
    Route::get('/login', [UserAuthController::class, 'login'])->name('student_login');
    Route::post('/login', [UserAuthController::class, 'loginPost'])->name('student_login.post');
    Route::get('/register', [UserAuthController::class, 'register'])->name('student_register');
    Route::post('/register', [UserAuthController::class, 'registerPost'])->name('student_register.post');
    Route::get('/forgot-password', [UserAuthController::class, 'forgotPassword'])->name('student_forgot_password');
    Route::post('/forgot-password', [UserAuthController::class, 'forgotPasswordPost'])->name('student_forgot_password.post');
    Route::get('/reset-password/{token}', [UserAuthController::class, 'resetPassword'])->name('student_password.reset');
    Route::post('/reset-password', [UserAuthController::class, 'resetPasswordPost'])->name('student_password.reset.post');
    Route::get('/verify-email/{email}', [UserAuthController::class, 'verifyEmail'])->name('student_verify_email');
    Route::post('/resend-verification-email', [UserAuthController::class, 'resendVerificationEmail'])->name('student_resend_verification_email')->middleware('throttle:6,1');
    Route::get('/verify-email/{token}/{email}', [UserAuthController::class, 'verifyEmailGet'])->name('student_verify_email.get');
    Route::post('/verify-email/', [UserAuthController::class, 'verifyEmailPost'])->name('student_verify_email.post');
});

//--private
Route::prefix('student')->group(function () {
    Route::get('/logout', [UserAuthController::class, 'logout'])->name('student_logout');
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('student_dashboard');
});


//---------------------------------Instructor-----------------------------------//
//--public
Route::prefix('instructor')->group(function () {
    Route::get('/login', [InstructorAuthController::class, 'login'])->name('instructor_login');
    Route::post('/login', [InstructorAuthController::class, 'loginPost'])->name('instructor_login.post');
    Route::get('/register', [InstructorAuthController::class, 'register'])->name('instructor_register');
    Route::post('/register', [InstructorAuthController::class, 'registerPost'])->name('instructor_register.post');
    Route::get('/forgot-password', [InstructorAuthController::class, 'forgotPassword'])->name('instructor_forgot_password');
    Route::post('/forgot-password', [InstructorAuthController::class, 'forgotPasswordPost'])->name('instructor_forgot_password.post');
    Route::get('/reset-password/{token}', [InstructorAuthController::class, 'resetPassword'])->name('instructor_password.reset');
    Route::post('/reset-password', [InstructorAuthController::class, 'resetPasswordPost'])->name('instructor_password.reset.post');
    Route::get('/verify-email/{email}', [InstructorAuthController::class, 'verifyEmail'])->name('instructor_verify_email');
    Route::post('/resend-verification-email', [InstructorAuthController::class, 'resendVerificationEmail'])->name('instructor_resend_verification_email')->middleware('throttle:6,1');
    Route::get('/verify-email/{token}/{email}', [InstructorAuthController::class, 'verifyEmailGet'])->name('instructor_verify_email.get');
    Route::post('/verify-email/', [InstructorAuthController::class, 'verifyEmailPost'])->name('instructor_verify_email.post');
});

//--private
Route::prefix('instructor')->group(function () {
    Route::get('/logout', [InstructorAuthController::class, 'logout'])->name('instructor_logout');
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('instructor_dashboard');
});
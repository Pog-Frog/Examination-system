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
    Route::prefix('/profile')->group(function () {
        Route::get('/', [UserController::class, 'profile'])->name('student_profile');
        Route::get('/edit', [UserController::class, 'profileEdit'])->name('student_profile.edit');
        Route::post('/edit', [UserController::class, 'profileEditPost'])->name('student_profile.edit.post');
        Route::post('/delete', [UserController::class, 'profileDelete'])->name('student_profile.delete');
    });
    Route::prefix('/classrooms')->group(function () {
        Route::post('/join', [UserController::class, 'classroomJoin'])->name('student_classroom.join');
        Route::get('/{slug}/leave', [UserController::class, 'classroomLeave'])->name('student_classroom.leave');
        Route::prefix('/{slug}')->group(function () {
            Route::get('/', [UserController::class, 'classroomShow'])->name('student_classroom.show');
            Route::post('/announce', [UserController::class, 'classroomAnnounce'])->name('student_classroom.announce');
            Route::get('/get-user/{role}/{id}', [UserController::class, 'getUser'])->name('student_get_user');
            Route::prefix('/students')->group(function () {
                Route::get('/', [UserController::class, 'classroomStudents'])->name('student_classrooms.students');
            });
        });
    });
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
    Route::get('/dashboard', [InstructorController::class, 'classrooms'])->name('instructor_dashboard');
    Route::prefix('/profile')->group(function () {
        Route::get('/', [InstructorController::class, 'profile'])->name('instructor_profile');
        Route::get('/edit', [InstructorController::class, 'profileEdit'])->name('instructor_profile.edit');
        Route::post('/edit', [InstructorController::class, 'profileEditPost'])->name('instructor_profile.edit.post');
        Route::post('/delete', [InstructorController::class, 'profileDelete'])->name('instructor_profile.delete');
    });
    Route::prefix('/classrooms')->group(function () {
        Route::get('/create', [InstructorController::class, 'classroomCreate'])->name('instructor_classrooms.create');
        Route::post('/create', [InstructorController::class, 'classroomCreatePost'])->name('instructor_classrooms.create.post');
        Route::get('/edit/{slug}', [InstructorController::class, 'classroomEdit'])->name('instructor_classrooms.edit');
        Route::post('/edit/{slug}', [InstructorController::class, 'classroomEditPost'])->name('instructor_classrooms.edit.post');
        Route::post('/delete/{slug}', [InstructorController::class, 'classroomDelete'])->name('instructor_classrooms.delete');
        Route::post('/regenerate-code/{slug}', [InstructorController::class, 'classroomCodeRegenerate'])->name('instructor_classrooms.regenerate_code');
        Route::prefix('/{slug}')->group(function () {
            Route::get('/', [InstructorController::class, 'classroomShow'])->name('instructor_classrooms.show');
            Route::post('/announce', [InstructorController::class, 'classroomAnnounce'])->name('instructor_classrooms.announce');
            Route::get('/get-user/{role}/{id}', [InstructorController::class, 'getUser'])->name('instructor_get_user');
            Route::prefix('/students')->group(function () {
                Route::get('/', [InstructorController::class, 'classroomStudents'])->name('instructor_classrooms.students');
                Route::post('/delete', [InstructorController::class, 'classroomStudentsDelete'])->name('instructor_classrooms.students.delete');
                Route::get('/{student_slug}', [InstructorController::class, 'classroomStudentsShow'])->name('instructor_classrooms.students.show');
            });
            Route::prefix('/exams')->group(function () {
                Route::get('/', [InstructorController::class, 'classroomExams'])->name('instructor_classrooms.exams');
                Route::get('/create', [InstructorController::class, 'classroomExamsCreate'])->name('instructor_classrooms.exams.create');
                Route::post('/create', [InstructorController::class, 'classroomExamsCreatePost'])->name('instructor_classrooms.exams.create.post');
                Route::get('/edit/{exam_slug}', [InstructorController::class, 'classroomExamsEdit'])->name('instructor_classrooms.exams.edit');
                Route::post('/edit/{exam_slug}', [InstructorController::class, 'classroomExamsEditPost'])->name('instructor_classrooms.exams.edit.post');
                Route::post('/delete/{exam_slug}', [InstructorController::class, 'classroomExamsDelete'])->name('instructor_classrooms.exams.delete');
                Route::get('/{exam_slug}', [InstructorController::class, 'classroomExamsShow'])->name('instructor_classrooms.exams.show');
                Route::prefix('/{exam_slug}/questions')->group(function () {
                    Route::get('/', [InstructorController::class, 'classroomExamsQuestions'])->name('instructor_classrooms.exams.questions');
                    Route::get('/create', [InstructorController::class, 'classroomExamsQuestionsCreate'])->name('instructor_classrooms.exams.questions.create');
                    Route::post('/create', [InstructorController::class, 'classroomExamsQuestionsCreatePost'])->name('instructor_classrooms.exams.questions.create.post');
                    Route::get('/edit/{question_slug}', [InstructorController::class, 'classroomExamsQuestionsEdit'])->name('instructor_classrooms.exams.questions.edit');
                    Route::post('/edit/{question_slug}', [InstructorController::class, 'classroomExamsQuestionsEditPost'])->name('instructor_classrooms.exams.questions.edit.post');
                    Route::post('/delete/{question_slug}', [InstructorController::class, 'classroomExamsQuestionsDelete'])->name('instructor_classrooms.exams.questions.delete');
                });
            });
        });
    });
    Route::prefix('/questions')->group(function () {
        Route::get('/', [InstructorController::class, 'questions'])->name('instructor_questions'); ## The qusetion bank
        Route::get('/create/{type_name}', [InstructorController::class, 'questionsCreate'])->name('instructor_questions.create');
        Route::post('/create', [InstructorController::class, 'questionsCreatePost'])->name('instructor_questions.create.post');
        Route::get('/edit/{question}', [InstructorController::class, 'questionsEdit'])->name('instructor_questions.edit');
        Route::post('/edit/{question}', [InstructorController::class, 'questionsEditPost'])->name('instructor_questions.edit.post');
        Route::get('/delete/{question}', [InstructorController::class, 'questionsDelete'])->name('instructor_questions.delete');
        Route::get('/{question}', [InstructorController::class, 'questionsShow'])->name('instructor_questions.show');
        Route::get('/{question}/answers', [InstructorController::class, 'questionsAnswers'])->name('instructor_questions.answers');
    });
});

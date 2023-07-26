<?php

namespace App\Http\Controllers;

use App\Models\Email_verfication;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class InstructorAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:user')->except('logout');
        $this->middleware('guest:instructor')->except('logout');
    }

    public function login(Request $request)
    {
        return view('instructor.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|max:12|min:8'
        ]);
        if(!$request->expectsJson()){
            if(Auth::guard('instructor')->attempt($request->only('email', 'password'))) {
                if (Auth::guard('instructor')->user()->email_verified_at == null) {
                    Auth::guard('instructor')->logout();
                    return back()->with('error', 'Please verify your email first');
                }
                return redirect('instructor/dashboard');
            } else {
                return back()->with('error', 'Email or Password is incorrect');
            }
        }else{
            if (Auth::guard('instructor')->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ])) {
                $user = Instructor::where('email', $request->email)->first();
                if($user->email_verified_at == null){
                    return response()->json([
                        'message' => 'Please verify your email first',
                    ], 401);
                }
                return $user->createToken($user->name)->plainTextToken;
            } else {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
        }
    }

    public function logout(Request $request)
    {
        if($request->expectsJson()) {
            $model = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($request->bearerToken());
            if($accessToken != null)
                $accessToken->delete();
            Auth::guard('instructor')->logout();
            return response()->json(['message' => 'Logged out']);
        }else{
            if(!Auth::guard('instructor')->check()){
                return route('instructor_login');
            }
            Auth::guard('instructor')->logout();
            return redirect()->route('instructor_login');
        }
    }

    public function register()
    {
        return view('instructor.register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email|unique:instructors,email',
            'password' => 'required|min:6',
            'phone' => 'required|string',
            'gender' => 'required|string',
            'degree' => 'required|string',
            'institute' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);
        $profileImage = NULL;
        if ($image = $request->file('image')) {
            $destinationPath = 'ProfilePics/instructors/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
        }
        $user = Instructor::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'photo' => $profileImage,
            'institute' => $request->input('institute'),
            'degree' => $request->input('degree'),
            'gender' => $request->input('gender')
        ]);
        $user->sendEmailVerificationNotification();
        if($request->expectsJson()){
            return response()->json(['message' => 'done', 'code' => 200]);
        }else{
            Auth::guard('instructor')->logout();
            return redirect()->route('instructor_verify_email', ['email' => $user->email])->with('success', 'You have been registered successfully');
        }
    }

    public function forgotPassword()
    {
        return view('instructor.forgot_password');
    }

    public function forgotPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:instructors,email'
        ]);

        $status = Password::broker('instructors')->sendResetLink([
            'email' => $request->email
        ]);

        if($request->expectsJson()){
            if($status == Password::RESET_LINK_SENT){
                return response()->json(['message' => 'done', 'code' => 200]);
            }
            else{
                return response()->json(['message' => 'Email not found', 'code' => 400]);
            }
        }else{
            if($status == Password::RESET_LINK_SENT){
                
                return back()->with('success', 'Reset link sent to your email');
    
            }else{
                return back()->with('error', 'Email not found');
            }
        }
    }

    public function resetPassword($token)
    {
        return view('instructor.reset_password', ['token' => $token , 'email' => request()->email]);
    }

    public function resetPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:instructors,email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);
        $status = Password::broker('instructors')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();
                event(new PasswordReset($user));
            }
        );
        if($request->expectsJson()){
            if($status == Password::PASSWORD_RESET){
                return response()->json(['message' => 'done', 'code' => 200]);
            }
            else{
                return response()->json(['message' => 'Token expired', 'code' => 400]);
            }
        }else{
            if($status == Password::PASSWORD_RESET){
                return redirect()->route('instructor_login')->with('success', 'Password reset successfully');
            }else{
                return back()->with('error', 'Token expired');
            }
        }
    }

    public function verifyEmail($email)
    {
        return view('instructor.verify_email_notify', ['email' => $email]);
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:instructors,email'
        ]);
        $user = Instructor::where('email', $request->email)->first();
        if($user){
            if($user->hasVerifiedEmail()){
                if($request->expectsJson()){
                    return response()->json(['message' => 'Email already verified', 'code' => 400]);
                }
                else{
                    return back()->with('error', 'Email already verified');
                }
            }
            else{
                $email_verfication_check = Email_verfication::where('email', $user->email)->first();
                if($email_verfication_check){
                    $model = Sanctum::$personalAccessTokenModel;
                    $accessToken = $model::findToken($email_verfication_check->token);
                    if($accessToken){
                        $accessToken->delete();
                    }
                    Email_verfication::query()->where('email', $user->email)->delete();
                }
                $user->sendEmailVerificationNotification();
                if($request->expectsJson()){
                    return response()->json(['message' => 'Verification link sent to your email', 'code' => 200]);
                }
                else{
                    return back()->with('success', 'Verification link sent to your email');
                }
            }
        }else{
            if($request->expectsJson()){
                return response()->json(['message' => 'Email not found', 'code' => 400]);
            }
            else{
                return back()->with('error', 'Email not found');
            }
        }
    }

    public function verifyEmailGet($token , $email)
    {
        return view('instructor.verify_email_confirm', ['email' => $email, 'token' => $token]);
    }

    public function verifyEmailPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:instructors,email',
            'token' => 'required|string'
        ]);
        $user = Instructor::where('email', $request->email)->first();
        $email_verfication_check = Email_verfication::where('token', '=', $request->token)->first();
        if($email_verfication_check && $user->email == $email_verfication_check->email){
            if($user->hasVerifiedEmail()){
                if($request->expectsJson()){
                    return response()->json(['message' => 'Email already verified', 'code' => 400]);
                }
                else{
                    return redirect()->route('instructor_login')->with('error', 'Email already verified');
                }
            }
            else{
                $user->markEmailAsVerified();
                $model = Sanctum::$personalAccessTokenModel;
                $accessToken = $model::findToken($email_verfication_check->token);
                if($accessToken){
                    $accessToken->delete();
                }
                Email_verfication::query()->where('token', $request->token)->delete();
                if($request->expectsJson()){
                    return response()->json(['message' => 'done', 'code' => 200]);
                }
                else{
                    return redirect()->route('instructor_login')->with('success', 'Email verified successfully');
                }
            }
        }else{
            if($request->expectsJson()){
                return response()->json(['message' => 'Invalid token', 'code' => 400]);
            }
            else{
                return back()->with('error', 'Invalid token');
            }
        }
    }
}

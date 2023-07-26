<?php

namespace App\Http\Controllers;

use App\Models\Email_verfication;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;

class ApiUser extends Controller
{

    public function login(Request $request)
    {
        return view('user.login');
    }

    public function loginPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            $user = User::where('email', $request->email)->first();
            if($user->email_verified_at == null){
                return response()->json([
                    'message' => 'Please verify your email first',
                ], 401);
            }
            return response()->json([
                'message' => 'login successfull',
                'status' => '200',
                'user' => $user,
                'token' => $user->createToken($user->name)->plainTextToken,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Email or Password is incorrect',
                'status' => '401',
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function isAuthenticated(Request $request){
        if(Auth::check()){
            return response()->json([
                'message' => 'User is authenticated',
                'status' => '200',
                'user' => Auth::user(),
                'isAuthenticated' => true,
            ], 200);
        }else{
            return response()->json([
                'message' => 'User is not authenticated',
                'status' => '401',
                'isAuthenticated' => false,
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        if($request->expectsJson()) {
            $model = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($request->bearerToken());
            $accessToken->delete();
            Auth::logout();
            return response()->json(['message' => 'Logged out']);
        }else{
            if(!Auth::check()){
                return route('student_login');
            }
            Auth::logout();
            return redirect()->route('student_login');
        }
    }
}

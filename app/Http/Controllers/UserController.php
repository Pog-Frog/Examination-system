<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('user.dashboard');
    }

    public function profile(Request $request)
    {
        if($request->expectsJson()){
            return response()->json([
                'user' => Auth::user()
            ]);
        }
        return view('user.profile');
    }

    public function profileEdit(Request $request)
    {
        if($request->expectsJson()){
            return response()->json([
                'user' => Auth::user()
            ]);
        }
        return view('user.profile_edit');
    }

    public function profileEditPost(Request $request)
    {   
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required|string',
            'degree' => 'required|string',
            'institute' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->degree = $request->degree;
        $user->institute = $request->institute;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $destinationPath = 'ProfilePics/students/';
            if(File::exists($destinationPath.$user->photo)){
                File::delete($destinationPath.$user->photo);
            }
            $image->move($destinationPath, $profileImage);
            $user->photo = $profileImage;
        }
        $user->save();
        if($request->expectsJson()){
            return response()->json(['message' => 'Profile updated successfully.']);
        }else{
            return redirect()->route('student_profile')->with('success', 'Profile updated successfully.');
        }
    }
}

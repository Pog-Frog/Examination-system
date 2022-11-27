<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class InstructorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:instructor');
    }

    public function dashboard()
    {
        return view('instructor.dashboard');
    }

    public function profile(Request $request)
    {
        if($request->expectsJson()){
            return response()->json([
                'user' => Auth::guard('instructor')->user()
            ]);
        }
        return view('instructor.profile');
    }

    public function profileEdit(Request $request)
    {
        if($request->expectsJson()){
            return response()->json([
                'user' => Auth::guard('instructor')->user()
            ]);
        }
        return view('instructor.profile_edit');
    }

    public function profileEditPost(Request $request){
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required|string',
            'degree' => 'required|string',
            'institute' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        $user = Auth::guard('instructor')->user();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->gender = $request->gender;
        $user->degree = $request->degree;
        $user->institute = $request->institute;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $destinationPath = 'ProfilePics/instructors/';
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
            return redirect()->route('instructor_profile')->with('success', 'Profile updated successfully.');
        }
    }
}

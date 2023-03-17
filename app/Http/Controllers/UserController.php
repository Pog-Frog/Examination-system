<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\Announcement_author;
use App\Models\Announcement_comment;
use App\Models\Classroom;
use App\Models\Classroom_instructor;
use App\Models\Classroom_student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $classrooms = Classroom::join('classroom_students', 'classrooms.id', '=', 'classroom_students.classroom_id')
            ->where('classroom_students.student_id', Auth::user()->id)
            ->select('classrooms.*')
            ->get();
        foreach ($classrooms as $classroom) {
            $classroom->exams_count = $classroom->getExams()->count();
        }
        return view('user.dashboard', compact('classrooms'));
    }

    public function profile(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'user' => Auth::user()
            ]);
        }
        return view('user.profile');
    }

    public function profileEdit(Request $request)
    {
        if ($request->expectsJson()) {
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
            if (File::exists($destinationPath . $user->photo)) {
                File::delete($destinationPath . $user->photo);
            }
            $image->move($destinationPath, $profileImage);
            $user->photo = $profileImage;
        }
        $user->save();
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Profile updated successfully.'])->setStatusCode(200);
        } else {
            return redirect()->route('student_profile')->with('success', 'Profile updated successfully.');
        }
    }

    public function classroomJoin(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $classroom = Classroom::where('code', $request->code)->first();
        if ($classroom) {
            $classroom_student = Classroom_student::where('classroom_id', $classroom->id)->where('student_id', Auth::user()->id)->first();
            if ($classroom_student) {
                return $request->expectsJson() ?
                    response()->json(['message' => 'You are already joined this classroom.'])->setStatusCode(401) :
                    redirect()->route('student_dashboard')->with('error', 'You are already joined this classroom.');
            } else {
                $classroom_student = new Classroom_student();
                $classroom_student->classroom_id = $classroom->id;
                $classroom_student->student_id = Auth::user()->id;
                $classroom_student->date_joined = Carbon::now()->timezone('Africa/Cairo')->format('Y-m-d H:i:s');
                $classroom_student->save();
                return $request->expectsJson() ?
                    response()->json(['message' => 'Classroom joined successfully.'])->setStatusCode(200) :
                    redirect()->route('student_dashboard')->with('success', 'Classroom joined successfully.');
            }
        } else {
            return $request->expectsJson() ?
                response()->json(['message' => 'Classroom not found.'])->setStatusCode(401) :
                redirect()->route('student_dashboard')->with('error', 'Classroom not found.');
        }
    }


    public function classroomLeave($slug, Request $request)
    {
        $classroom = Classroom::where('slug', $slug)->first();
        if ($classroom) {
            if (Classroom_student::where('classroom_id', $classroom->id)->where('student_id', Auth::user()->id)->first()) {
                Classroom_student::where('classroom_id', $classroom->id)->where('student_id', Auth::user()->id)->first()->delete();
                return $request->expectsJson() ? response()->json(['message' => 'Classroom left successfully.'])->setStatusCode(200) :
                    redirect()->route('student_dashboard')->with('success', 'Classroom left successfully.');
            } else {
                return $request->expectsJson() ? response()->json(['message' => 'You are not joined this classroom.'])->setStatusCode(401) :
                    redirect()->route('student_dashboard')->with('error', 'You are not joined this classroom.');
            }
        } else {
            return $request->expectsJson() ? response()->json(['message' => 'Classroom not found.']) :
                redirect()->route('student_dashboard')->with('error', 'Classroom not found.');
        }
    }

    public function classroomShow($slug, Request $request)
    {
        $classroom = Classroom::where('slug', $slug)->first();
        if ($classroom) {
            $classroom_student = Classroom_student::where('classroom_id', $classroom->id)
                ->where('student_id', Auth::user()->id)->first();
            if ($classroom_student) {
                $announcements = $classroom->getAnnouncements();
                $exams = $classroom->getExams()->take(5);
                return $request->expectsJson() ? response()->json([
                    'classroom' => $classroom,
                    'announcements' => $announcements,
                    'exams' => $exams
                ])->setStatusCode(200) : view('classrooms.classroom_home', compact('classroom', 'announcements', 'exams'));
            } else {
                return $request->expectsJson() ? response()->json(['message' => 'You are not joined this classroom.'])->setStatusCode(401)
                    : redirect()->route('student_dashboard')->with('error', 'You are not joined this classroom.');
            }
        } else {
            return $request->expectsJson() ? response()->json(['message' => 'Classroom not found.'])->setStatusCode(401)
                : redirect()->route('student_dashboard')->with('error', 'Classroom not found.');
        }
    }

    public function classroomAnnounce($slug, Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'text' => 'required|string|max:3000',
        ]);
        $classroom = Classroom::findBySlugOrFail($slug);
        if ($classroom) {
            $classroom_student = Classroom_student::where('classroom_id', $classroom->id)
                ->where('student_id', Auth::user()->id)->first();
            if ($classroom_student) {
                $author = Announcement_author::where('author_id', Auth::user()->id)->where('author_role', 'student')->first();
                if (!$author) {
                    $author = new Announcement_author();
                    $author->author_id = Auth::user()->id;
                    $author->author_role = 'student';
                    $author->save();
                }
                $announcement = new Announcement();
                $announcement->title = $request->title;
                $announcement->text = $request->text;
                $announcement->date_created = Carbon::now()->timezone('Africa/Cairo')->format('Y-m-d H:i:s');
                $announcement->announcement_author_id = $author->id;
                $announcement->classroom_id = $classroom->id;
                $success = $announcement->save() && $author->save();
                if ($success) {
                    $status = 200;
                    $message = 'Announcement created successfully.';
                } else {
                    $status = 500;
                    $message = 'Something went wrong.';
                }
                return $request->expectsJson() ? response()->json(['message' => $message])->setStatusCode($status)
                    : redirect()->back()->with('success', $message);

            } else {
                return $request->expectsJson() ? response()->json(['message' => 'You are not joined this classroom.'])->setStatusCode(401)
                    : redirect()->route('student_dashboard')->with('error', 'You are not joined this classroom.');
            }
        } else {
            return $request->expectsJson() ? response()->json(['message' => 'Classroom not found.'])->setStatusCode(401)
                : redirect()->route('student_dashboard')->with('error', 'Classroom not found.');
        }
    }

    public function classroomAnnouncementcomments($slug, $id)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        $announcement = Announcement::getAnnouncement($id, $classroom->id);
        if (!$announcement) {
            $status = 404;
            $message = 'Announcement not found.';
            return response()->json([
                'status' => $status,
                'message' => $message
            ])->setStatusCode($status);
        }
        $comments = $announcement->getComments();
        return view('classrooms.classroom_announcement_comments', compact('classroom', 'announcement', 'comments'));
    }

    public function classroomComment($slug, $id, Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:3000',
        ]);
        $classroom = Classroom::findBySlugOrFail($slug);
        $announcement = Announcement::getAnnouncement($id, $classroom->id);
        if (!$announcement) {
            $status = 404;
            $message = 'Announcement not found.';
            return response()->json([
                'status' => $status,
                'message' => $message
            ])->setStatusCode($status);
        }
        $comment = new Announcement_comment();
        $comment->text = $request->text;
        $comment->date_created = Carbon::now()->timezone('Africa/Cairo')->format('Y-m-d H:i:s');
        $comment->announcement_id = $announcement->id;
        $comment->author_id = Auth::user()->id;
        $comment->author_role = 'student';
        $success = $comment->save();
        if ($success) {
            $status = 200;
            $message = 'Comment created successfully.';
        } else {
            $status = 500;
            $message = 'Something went wrong.';
        }
        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'comment' => $comment
            ])->setStatusCode($status);
        } else {
            return redirect()->back()->with($status == 200 ? 'success' : 'error', $message)->setStatusCode($status);
        }
    }

    public function classroomStudents($slug, Request $request)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        if ($classroom) {
            $classroom_student = Classroom_student::where('classroom_id', $classroom->id)
                ->where('student_id', Auth::user()->id)->first();
            if ($classroom_student) {
                $students = $classroom->getStudents();
                $instructors = $classroom->getInstructors();
                return $request->expectsJson() ? response()->json([
                    'classroom' => $classroom,
                    'students' => $students,
                    'instructors' => $instructors,
                ])->setStatusCode(200) : view('classrooms.classroom_students', compact('classroom', 'students', 'instructors'));
            } else {
                return $request->expectsJson() ? response()->json(['message' => 'You are not joined this classroom.'])->setStatusCode(401)
                    : redirect()->route('student_dashboard')->with('error', 'You are not joined this classroom.');
            }
        } else {
            return $request->expectsJson() ? response()->json(['message' => 'Classroom not found.'])->setStatusCode(401)
                : redirect()->route('student_dashboard')->with('error', 'Classroom not found.');
        }
    }

    public function getUser(Request $request)
    {
        $user = null;
        $role = $request->role;
        if (Auth::user()->id == $request->id && $role == 'student') {
            if ($request->expectsJson()) {
                return response()->json([
                    'user' => Auth::user()
                ]);
            }
            return redirect(route('student_profile'));
        } else {
            $classroom = Classroom::findBySlugOrFail($request->slug);
            if ($request->role == 'student') {
                $user = Classroom_student::getStudent($classroom->id, $request->id);
            } else if ($request->role == 'instructor') {
                $user = Classroom_instructor::getInstructor($classroom->id, $request->id);
            } else if ($request->role == 'admin') {
                $user = Admin::where('id', $request->id)->first();
            }
            if ($request->expectsJson()) {
                return response()->json([
                    'user' => $user,
                    'role' => $role,
                    'classroom' => $classroom
                ])->setStatusCode(200);
            }
            return view('common.user_profile', compact('user', 'role', 'classroom'));
        }
    }
}

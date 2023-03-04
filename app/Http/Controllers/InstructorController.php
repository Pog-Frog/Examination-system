<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\Announcement_author;
use App\Models\Classroom_instructor;
use App\Models\Classroom_student;
use App\Models\Complete_question;
use App\Models\Essay_question;
use App\Models\Exam_option;
use App\Models\Instructor;
use App\Models\Classroom;
use App\Models\Mcq_question;
use App\Models\Question;
use App\Models\Question_type;
use App\Models\T_f_question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

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
        if ($request->expectsJson()) {
            return response()->json([
                'user' => Auth::guard('instructor')->user()
            ]);
        }
        return view('instructor.profile');
    }

    public function profileEdit(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'user' => Auth::guard('instructor')->user()
            ]);
        }
        return view('instructor.profile_edit');
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
            if (File::exists($destinationPath . $user->photo)) {
                File::delete($destinationPath . $user->photo);
            }
            $image->move($destinationPath, $profileImage);
            $user->photo = $profileImage;
        }
        if ($user->save()) {
            $message = 'Congrats! Your profile was updated successfully.';
            $status = 200;
            if ($request->expectsJson()) {
                return response()->json(['message' => $message])->setStatusCode($status);
            } else {
                return redirect()->back()->with('success', $message)->setStatusCode($status);
            }
        } else {
            $message = 'Oops! Something went wrong, please try again.';
            $status = 500;
            if ($request->expectsJson()) {
                return response()->json(['message' => $message])->setStatusCode($status);
            } else {
                return redirect()->back()->with('error', $message)->setStatusCode($status);
            }
        }
    }

    // function to return all the instructor classrooms to the view instructor.classrooms
    public function classrooms(Request $request)
    {
        $classrooms = Classroom::join('classroom_instructors', 'classrooms.id', '=', 'classroom_instructors.classroom_id')
            ->where('classroom_instructors.instructor_id', Auth::guard('instructor')->user()->id)
            ->select('classrooms.*')
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'classrooms' => $classrooms
            ])->setStatusCode(200);
        }
        return view('instructor.dashboard', compact('classrooms'));
    }

    public function classroomCreate(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'user' => Auth::guard('instructor')->user()
            ]);
        }
        return view('classrooms.classroom_create');
    }

    //function to create a new classroom and generate a new code for it
    public function classroomCreatePost(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'info' => 'required|string',
        ]);
        $classroom = new Classroom();
        $classroom->name = $request->name;
        $classroom->info = $request->info;
        $classroom->code = base64_encode(Str::random(8));
        if ($classroom->save()) {
            $classroom_instructor = new Classroom_instructor();
            $classroom_instructor->classroom_id = $classroom->id;
            $classroom_instructor->instructor_id = Auth::guard('instructor')->user()->id;
            $classroom_instructor->date_joined = Carbon::now()->timezone('Africa/Cairo')->format('Y-m-d H:i:s');
            if ($classroom_instructor->save()) {
                $message = 'Congrats! The classroom was created successfully.';
                $status = 200;
                if ($request->expectsJson()) {
                    return response()->json(['message' => $message])->setStatusCode($status);
                } else {
                    return redirect()->back()->with('success', $message)->setStatusCode($status);
                }
            } else {
                $message = 'Oops! Something went wrong, please try again.';
                $status = 500;
                if ($request->expectsJson()) {
                    return response()->json(['message' => $message])->setStatusCode($status);
                } else {
                    return redirect()->back()->with('error', $message)->setStatusCode($status);
                }
            }
        } else {
            $message = 'Oops! Something went wrong, please try again.';
            $status = 500;
            if ($request->expectsJson()) {
                return response()->json(['message' => $message])->setStatusCode($status);
            } else {
                return redirect()->back()->with('error', $message)->setStatusCode($status);
            }
        }
    }

    public function classroomEdit($slug, Request $request)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        if ($request->expectsJson()) {
            return response()->json([
                'classroom' => $classroom
            ])->setStatusCode(200);
        }
        return view('classrooms.classroom_edit', compact('classroom'));
    }

    public function classroomEditPost($slug, Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'info' => 'required|string|max:3000',
        ]);
        $classroom = Classroom::findBySlugOrFail($slug);
        $classroom->name = $request->name;
        $classroom->info = $request->info;
        if ($classroom->save()) {
            $status = 200;
            $message = 'Classroom updated successfully.';
        } else {
            $status = 500;
            $message = 'Something went wrong.';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'classroom' => $classroom
            ])->setStatusCode($status);
        } else {
            return redirect()->back()->with($status == 200 ? 'success' : 'error', $message)->setStatusCode($status);
        }
    }

    // function to regenerate a unique code for a classroom given the classroom id in the request and returns back with the new code
    public function classroomCodeRegenerate($slug, Request $request)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        $classroom->code = base64_encode(Str::random(8));
        if ($classroom->save()) {
            $status = 200;
            $message = 'Classroom code regenerated successfully.';
        } else {
            $status = 500;
            $message = 'Something went wrong.';
        }
        if ($request->expectsJson()) {
            return response()->json(['message' => $message])->setStatusCode($status);
        } else {
            return redirect()->back()->with($status == 200 ? 'success' : 'error', $message)->setStatusCode($status);
        }
    }

    // function to delete a classroom given the classroom id in the request,
    // also delete all the data in other tables associated with that classroom
    public function classroomDelete($slug, Request $request)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        if ($classroom->delete()) {
            $status = 200;
            $message = 'Classroom deleted successfully.';
        } else {
            $status = 500;
            $message = 'Something went wrong.';
        }
        if ($request->expectsJson()) {
            return response()->json(['message' => $message])->setStatusCode($status);
        } else {
            return redirect()->route('instructor_dashboard')->with($status == 200 ? 'success' : 'error', $message);
        }
    }

    public function classroomShow($slug, Request $request)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        if(!$classroom){
            $status = 404;
            $message = 'Classroom not found.';
            return response()->json([
                'status' => $status,
                'message' => $message
            ])->setStatusCode($status);
        }
        $classroom_instructor = Classroom_instructor::where('classroom_id', $classroom->id)
            ->where('instructor_id', Auth::guard('instructor')->user()->id)
            ->first();
        if (!$classroom_instructor) {
            $status = 401;
            $message = 'Unauthorized to view this classroom.';
            return response()->json([
                'status' => $status,
                'message' => $message
            ])->setStatusCode($status);
        }
        $announcements = $classroom->getAnnouncements();
        if ($request->expectsJson()) {
            return response()->json([
                'classroom' => $classroom,
                'announcements' => $announcements
            ])->setStatusCode(200);
        }
        return view('classrooms.classroom_home', compact('classroom', 'announcements'));
    }

    public function classroomAnnounce($slug, Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'text' => 'required|string|max:3000',
        ]);

        $classroom = Classroom::findBySlugOrFail($slug);
        $classroom_instructor = Classroom_instructor::where('classroom_id', $classroom->id)
            ->where('instructor_id', Auth::guard('instructor')->user()->id)
            ->first();
        if (!$classroom_instructor) {
            $status = 401;
            $message = 'Unauthorized to make announcements for this classroom.';
            return response()->json([
                'status' => $status,
                'message' => $message
            ])->setStatusCode($status);
        }

        $author = Announcement_author::where('author_id', Auth::guard('instructor')->user()->id)->where('author_role', 'instructor')->first();
        if (!$author) {
            $author = new Announcement_author();
            $author->author_id = Auth::guard('instructor')->user()->id;
            $author->author_role = 'instructor';
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

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'announcement' => $announcement
            ])->setStatusCode($status);
        } else {
            return redirect()->back()->with($status == 200 ? 'success' : 'error', $message)->setStatusCode($status);
        }
    }

    public function classroomStudents($slug, Request $request)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        $classroom_instructor = Classroom_instructor::where('classroom_id', $classroom->id)
            ->where('instructor_id', Auth::guard('instructor')->user()->id)
            ->first();
        if (!$classroom_instructor) {
            $status = 401;
            $message = 'Unauthorized to view students for this classroom.';
            return response()->json([
                'status' => $status,
                'message' => $message
            ])->setStatusCode($status);
        }
        $students = $classroom->getStudents();
        if ($request->expectsJson()) {
            return response()->json([
                'students' => $students
            ])->setStatusCode(200);
        }
        return view('classrooms.classroom_students', compact('classroom', 'students'));
    }

    public function getUser(Request $request)
    {
        $user = null;
        $role = $request->role;
        if(Auth::guard('instructor')->user()->id == $request->id && $role == 'instructor')
        {
            if ($request->expectsJson()) {
                return response()->json([
                    'user' => Auth::guard('instructor')->user()
                ]);
            }
            return redirect(route('instructor_profile'));
        }
        else
        {
            $classroom = Classroom::findBySlugOrFail($request->slug);
            if($request->role == 'student'){
                $user = Classroom_student::getStudent($classroom->id, $request->id);
            }
            else if($request->role == 'instructor')
            {
                $user = Classroom_instructor::getInstructor($classroom->id, $request->id);
            }
            else if($request->role == 'admin')
            {
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

    public function classroomStudentsDelete($slug, Request $request)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        $classroom_instructor = Classroom_instructor::where('classroom_id', $classroom->id)
            ->where('instructor_id', Auth::guard('instructor')->user()->id)
            ->first();
        if (!$classroom_instructor) {
            $status = 401;
            $message = 'Unauthorized to delete students for this classroom.';
            return response()->json([
                'status' => $status,
                'message' => $message
            ])->setStatusCode($status);
        }
        $student = Classroom_student::where('classroom_id', $classroom->id)
            ->where('student_id', $request->student_id)
            ->first();
        if (!$student) {
            $status = 404;
            $message = 'Student not found.';
            return response()->json([
                'status' => $status,
                'message' => $message
            ])->setStatusCode($status);
        }
        $success = $student->delete();
        if ($success) {
            $status = 200;
            $message = 'Student deleted successfully.';
        } else {
            $status = 500;
            $message = 'Something went wrong.';
        }
        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message
            ])->setStatusCode($status);
        } else {
            return redirect()->back()->with($status == 200 ? 'success' : 'error', $message)->setStatusCode($status);
        }
    }

    public function questions(Request $request)
    {
        $questions = Question::get_all_questions();
        $question_types = Question_type::all();

        if ($request->expectsJson()) {
            return response()->json([
                'questions' => $questions,
                'question_types' => $question_types
            ])->setStatusCode(200);
        }
        return view('questions.questions_home', compact('questions', 'question_types'));
    }

    public function questionsCreate(Request $request)
    {
        $request->validate([
            'question_type' => 'required|exists:question_types,id',
        ]);
        $question_type = Question_type::where('id', $request->question_type)->first();
        $subjects = Question::get_all_subjects();
        $categories = Question::get_all_categories();
        if(!$request->expectsJson()){
            return view('questions.questions_create', compact('subjects', 'categories', 'question_type'));
        }
        else{
            return response()->json([
                'subjects' => $subjects,
                'categories' => $categories,
                'question_type' => $question_type
            ])->setStatusCode(200);
        }
    }

    public function questionsCreatePost( Request $request)
    {
        $request->validate([
            'title' => 'required',
            'question_type' => 'required|exists:question_types,id',
            'subject' => 'required_without:newSubject|exists:questions,subject',
            'newSubject' => 'required_without:subject',
            'category' => 'required_without:newCategory|exists:questions,category',
            'newCategory' => 'required_without:category',
            'grade' => 'required',
            'status' => 'required',
        ]);
        $question_type = Question_type::where('id', $request->question_type)->first();
        if($question_type->type_name == "MCQ") {
            //pass the request that to the function that handles the mcq and forward the response back to the user
            return $this->questionMCQcreate($request);
        }
        elseif($question_type->type_name == "True False")
        {
            //pass the request that to the function that handles the true false and forward the response back to the user
            return $this->questionTrueFalsecreate($request);
        }
        elseif($question_type->type_name == "Fill in the blanks")
        {
            if($request->has('modified_text'))
            {
                return $this->questionFillInTheBlankscreate($request);
            }
            $question_params = $request->all();
            $blanks = explode(' ', $question_params['text']);
            $blanks = array_filter($blanks, function($value) { return preg_match('/\[.*?\]/', $value); });
            $blanks_ids = array_map(function($value) { return str_replace(['[', ']'], '', $value); }, $blanks);
            $question_params['modified_text'] = str_replace($blanks, array_map(function($value) { return $value.':_______________'; }, $blanks), $question_params['text']);
            $question_params['modified_text'] = str_replace(['[', ']'], '', $question_params['modified_text']);
            $question_params['blanks'] = $blanks_ids;
            return redirect()->back()->withInput($question_params);
        }
        elseif($question_type->type_name == "Essay")
        {
            //pass the request that to the function that handles the essay and forward the response back to the user
            return $this->questionEssaycreate($request);
        }
    }

    public function questionMCQcreate($request)
    {
        $request->validate([
            'option.*' => 'required|string',
            'answer' => 'required|string',
        ]);
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'option') !== false) {
                if (empty($value)) {
                    $status = 422;
                    $message = 'Please fill all the options.';
                    if($request->expectsJson()){
                        return response()->json([
                            'status' => $status,
                            'message' => $message
                        ])->setStatusCode($status);
                    }
                    else{
                        return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
                    }
                }
                foreach ($request->all() as $key2 => $value2) {
                    if (strpos($key2, 'option') !== false) {
                        if ($key != $key2 && $value == $value2) {
                            $status = 422;
                            $message = 'Please fill all the options with different values.';
                            if($request->expectsJson()){
                                return response()->json([
                                    'status' => $status,
                                    'message' => $message
                                ])->setStatusCode($status);
                            }
                            else{
                                return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
                            }
                        }
                    }
                }
            }
        }
        $options = [];
        foreach($request->all() as $key => $value){
            if(strpos($key, 'option') !== false){
                $options[] = $value;
            }
        }
        if(count($options) < 1){
            $status = 422;
            $message = 'Please fill at least 1 option other than the correct answer.';
            if($request->expectsJson()){
                return response()->json([
                    'status' => $status,
                    'message' => $message
                ])->setStatusCode($status);
            }
            else{
                return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
            }
        }
        $question = new Question();
        $question->title = $request->title;
        $question->subject = $request->subject ?? $request->newSubject;
        $question->category = $request->category ?? $request->newCategory;
        $question->text = $request->text;
        $question->instructor_id = Auth::guard('instructor')->user()->id;
        $question->type_id = $request->question_type;
        $question->grade = $request->grade;
        $question->status = $request->status;
        if($question->save())
        {
            foreach ($options as $option){
                $question_option = new Mcq_question();
                $question_option->question_id = $question->id;
                $question_option->option = $option;
                $question_option->is_correct = "false";
                if(!$question_option->save()){
                    $status = 500;
                    $message = 'Something went wrong.';
                    if($request->expectsJson()){
                        return response()->json([
                            'status' => $status,
                            'message' => $message
                        ])->setStatusCode($status);
                    }
                    else{
                        return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
                    }
                }
            }
            $question_answer = new Mcq_question();
            $question_answer->question_id = $question->id;
            $question_answer->option = $request->answer;
            $question_answer->is_correct = "true";
            if($question_answer->save()){
                $status = 200;
                $message = 'Question created successfully.';
                if($request->expectsJson()){
                    return response()->json([
                        'status' => $status,
                        'message' => $message
                    ])->setStatusCode($status);
                }
                else{
                    return redirect()->back()->with('success', $message)->setStatusCode($status);
                }
            }
            else{
                $status = 500;
                $message = 'Something went wrong.';
                if($request->expectsJson()){
                    return response()->json([
                        'status' => $status,
                        'message' => $message
                    ])->setStatusCode($status);
                }
                else{
                    return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
                }
            }
        }
        else
        {
            $status = 500;
            $message = 'Something went wrong.';
            if($request->expectsJson()){
                return response()->json([
                    'status' => $status,
                    'message' => $message
                ])->setStatusCode($status);
            }
            else{
                return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
            }
        }
    }

    public function questionTrueFalsecreate(Request $request)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);
        $question = new Question();
        $question->title = $request->title;
        $question->subject = $request->subject ?? $request->newSubject;
        $question->category = $request->category ?? $request->newCategory;
        $question->text = $request->text;
        $question->instructor_id = Auth::guard('instructor')->user()->id;
        $question->type_id = $request->question_type;
        $question->grade = $request->grade;
        $question->status = $request->status;
        if($question->save())
        {
            $question_answer = new T_f_question();
            $question_answer->question_id = $question->id;
            $question_answer->answer = $request->answer;
            if($question_answer->save()){
                $status = 200;
                $message = 'Question created successfully.';
                if($request->expectsJson()){
                    return response()->json([
                        'status' => $status,
                        'message' => $message
                    ])->setStatusCode($status);
                }
                else{
                    return redirect()->back()->with('success', $message)->setStatusCode($status);
                }
            }
            else{
                $status = 500;
                $message = 'Something went wrong.';
                if($request->expectsJson()){
                    return response()->json([
                        'status' => $status,
                        'message' => $message
                    ])->setStatusCode($status);
                }
                else{
                    return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
                }
            }
        }
        else
        {
            $status = 500;
            $message = 'Something went wrong.';
            if($request->expectsJson()){
                return response()->json([
                    'status' => $status,
                    'message' => $message
                ])->setStatusCode($status);
            }
            else{
                return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
            }
        }
    }

    public function questionFillInTheBlankscreate(Request $request)
    {
        $question = new Question();
        $question->title = $request->title;
        $question->subject = $request->subject ?? $request->newSubject;
        $question->category = $request->category ?? $request->newCategory;
        $question->text = $request->modified_text;
        $question->instructor_id = Auth::guard('instructor')->user()->id;
        $question->type_id = $request->question_type;
        $question->grade = $request->grade;
        $question->status = $request->status;
        if($question->save())
        {
            $blanks = $request->only(preg_grep('/^blank/', array_keys($request->all())));
            $grades = $request->only(preg_grep('/^grade_blank/', array_keys($request->all())));
            $blank_case_sensitivity = $request->only(preg_grep('/^status_blank/', array_keys($request->all())));
            $blank_ids = array();
            foreach ($blanks as $key => $value) {
                $blank_ids[] = substr($key, 5);
            }
            $blanks = array_combine($blank_ids, $blanks);
            $grades = array_combine($blank_ids, $grades);
            $blank_case_sensitivity = array_combine($blank_ids, $blank_case_sensitivity);
            foreach($blank_ids as $blank_id){
                $question_answer = new Complete_question();
                $question_answer->question_id = $question->id;
                $question_answer->blank_id = $blank_id;
                $question_answer->blank_answer = $blanks[$blank_id];
                $question_answer->grade = $grades[$blank_id];
                $question_answer->is_case_sensitive = $blank_case_sensitivity[$blank_id];
                if(!$question_answer->save()){
                    $status = 500;
                    $message = 'Something went wrong.';
                    if($request->expectsJson()){
                        return response()->json([
                            'status' => $status,
                            'message' => $message
                        ])->setStatusCode($status);
                    }
                    else{
                        return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
                    }
                }
            }
            if($request->expectsJson()){
                return response()->json([
                    'status' => 200,
                    'message' => 'Question created successfully.'
                ])->setStatusCode(200);
            }
            else{
                return redirect()->back()->with('success', 'Question created successfully.')->setStatusCode(200);
            }
        }
        else
        {
            $status = 500;
            $message = 'Something went wrong.';
            if($request->expectsJson()){
                return response()->json([
                    'status' => $status,
                    'message' => $message
                ])->setStatusCode($status);
            }
            else{
                return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
            }
        }
    }

    public function questionEssaycreate(Request $request)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);
        $question = new Question();
        $question->title = $request->title;
        $question->subject = $request->subject ?? $request->newSubject;
        $question->category = $request->category ?? $request->newCategory;
        $question->text = $request->text;
        $question->instructor_id = Auth::guard('instructor')->user()->id;
        $question->type_id = $request->question_type;
        $question->grade = $request->grade;
        $question->status = $request->status;

        if($question->save())
        {
            $essay_question = new Essay_question();
            $essay_question->question_id = $question->id;
            $essay_question->answer = $request->answer;
            $essay_question->is_case_sensitive = $request->is_case_sensitive;
            if($essay_question->save()){
                $status = 200;
                $message = 'Question created successfully.';
                if($request->expectsJson()){
                    return response()->json([
                        'status' => $status,
                        'message' => $message
                    ])->setStatusCode($status);
                }
                else{
                    return redirect()->back()->with('success', $message)->setStatusCode($status);
                }
            }
            else{
                $status = 500;
                $message = 'Something went wrong.';
                if($request->expectsJson()){
                    return response()->json([
                        'status' => $status,
                        'message' => $message
                    ])->setStatusCode($status);
                }
                else{
                    return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
                }
            }
        }
        else
        {
            $status = 500;
            $message = 'Something went wrong.';
            if($request->expectsJson()){
                return response()->json([
                    'status' => $status,
                    'message' => $message
                ])->setStatusCode($status);
            }
            else{
                return redirect()->back()->withInput()->with('error', $message)->setStatusCode($status);
            }
        }
    }

    public function classroomExams($slug, Request $request)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        $exams = $classroom->getExams();
        if($request->expectsJson()){
            return response()->json([
                'status' => 200,
                'exams' => $exams
            ])->setStatusCode(200);
        }
        else{
            return view('exams.exams_home', compact('classroom', 'exams'));
        }
    }

    public function classroomExamsCreate($slug, Request $request)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        $exam_options = Exam_option::all();
        if($request->expectsJson()){
            return response()->json([
                'status' => 200,
                'classroom' => $classroom,
                'exam_options' => $exam_options
            ])->setStatusCode(200);
        }
        else{
            return view('exams.exams_create', compact('classroom', 'exam_options'));
        }
    }

    public function classroomExamsCreatePost($slug, Request $request)
    {
        if($request->has('exam_options_done'))
        {
            dd($request->all());
        }
        $exam_options_names = Exam_option::all()->pluck('name')->toArray();
        $exam_options_names = array_map(function($value){
            return str_replace(' ', '_', $value);
        }, $exam_options_names);
        $exam_options = array_intersect($exam_options_names, array_keys($request->all()));
        $exam_options = array_keys($exam_options);
        $classroom = Classroom::findBySlugOrFail($request->slug);
        $request_params = $request->all();
        $request_params['options_done'] = true;
        $request_params['exam_options_done'] = $exam_options;
        return back()->withInput($request_params);

    }
}

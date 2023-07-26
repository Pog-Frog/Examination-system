<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Announcement;
use App\Models\Announcement_author;
use App\Models\Announcement_comment;
use App\Models\Cheating_attempts;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Mcq_answer;
use App\Models\T_f_answer;
use App\Models\Complete_answer;
use App\Models\Essay_answer;
use App\Models\Essay_question;
use App\Models\Question;
use App\Models\Exam_question;
use App\Models\Mcq_question;
use App\Models\T_f_question;
use App\Models\Complete_question;
use App\Models\Exam_result;
use App\Models\Classroom_instructor;
use App\Models\Classroom_student;
use App\Models\Student_exam_answer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Http;

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
            $classroom->exams_count = $classroom->getExams()->where('end_date', '>', Carbon::now('Africa/Cairo')->addHour())->count();
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
                $exams = $classroom->getExams();
                $taken_exams_ids = Exam_result::where('classroom_student_id', $classroom_student->id)->pluck('exam_id');
                $takenExams = Exam::whereIn('id', $taken_exams_ids)->get();
                // filter out the taken exams
                $exams = $classroom->getExams()->whereNotIn('id', $takenExams->pluck('id'));
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


    public function showVerifyImagePage(Request $request,$slug,$exam_id)
    {
        if(isset($_COOKIE['imageVerified'])) {
            return redirect()->route('student_classroom.take-exam', ['slug' => $slug,'exam_id' => $exam_id]);
        }
        else{
            $classroom = Classroom::where('slug', $slug)->first();
            $exam = Exam::where('id', $exam_id)->first();
            $current_time = now(new \DateTimeZone('Africa/Cairo'));
            $current_time->modify('+1 hour'); // عشان التوقيت الصيفى
            if (!$classroom || !$exam) {
                return redirect()->route('student_dashboard');
            }
            else if ($current_time < $exam->start_date || $current_time > $exam->end_date){
                    return redirect()->route('student_dashboard');
                }
            else{
                return view('exams.verify_image_student' , compact('classroom','exam'));
            }
        } 
    }

    public function takeExam(Request $request,$slug,$exam_id)
    {
        $exam = Exam::where('id', $exam_id)->first();
        $current_time = $current_time = now(new \DateTimeZone('Africa/Cairo')); 
        $current_time->modify('+1 hour'); // عشان التوقيت الصيفى

        if(!isset($_COOKIE['imageVerified'])) {
            return redirect()->route('student_classroom.verify-image', ['slug' => $slug,'exam_id' => $exam_id]);
        }
        else if ($current_time < $exam->start_date || $current_time > $exam->end_date){
            return redirect()->route('student_dashboard');
        }
        else{
            $classroom = Classroom::findBySlugOrFail($slug);
            $exam = Exam::where('id', $exam_id)->firstOrFail();
        // Get all the MCQ questions for the specified exam and randomize 
            $mcqQuestions = Exam_question::where('exam_id', $exam_id)
                ->whereHas('question', function ($query) {
                    $query->where('type_id', 1); // Filter only MCQ questions
                })
                ->with('question')
                ->get()
                ->shuffle();

            $mcqQuestionsWithOptions = [];
            foreach ($mcqQuestions as $mcqQuestion) {
                $mcqQuestionOptions = Mcq_question::where('question_id', $mcqQuestion->question_id)->get();
                $mcqQuestionsWithOptions[] = [
                    'question' => $mcqQuestion->question,
                    'options' => $mcqQuestionOptions,
                ];
            }

            // Get all the True/False questions for the specified exam and randomize
            $tfQuestions = Exam_question::where('exam_id', $exam_id)
                ->whereHas('question', function ($query) {
                    $query->where('type_id', 2); // Filter only True/False questions
                })
                ->with('question')
                ->get()
                ->shuffle();

            // Get all the Fill the Blank questions for the specified exam and randomize
            $fillQuestions = Exam_question::where('exam_id', $exam_id)
                ->whereHas('question', function ($query) {
                    $query->where('type_id', 3); // Filter only Fill the Blank questions
                })
                ->with('question')
                ->get()
                ->shuffle();

            $fillQuestionsWithBlanks = [];
                foreach ($fillQuestions as $fillQuestion) {
                    $fillQuestionBlanks = Complete_question::where('question_id', $fillQuestion->question_id)->get();
                    $fillQuestionsWithBlanks[] = [
                        'question' => $fillQuestion->question,
                        'blanks' => $fillQuestionBlanks,
                    ];
                }

            // Get all the Essay questions for the specified exam and randomize
            $essayQuestions = Exam_question::where('exam_id', $exam_id)
                ->whereHas('question', function ($query) {
                    $query->where('type_id', 4); // Filter only Essay questions
                })
                ->with('question')
                ->get()
                ->shuffle();

            return view('exams.take_exam_student' , compact('classroom','exam', 'mcqQuestionsWithOptions', 'tfQuestions', 'fillQuestionsWithBlanks', 'essayQuestions'));
        }
    }


    public function submitExam(Request $request,$slug,$exam_id)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        $classroom_student = Classroom_student::where('student_id', Auth::user()->id)->where('classroom_id', $classroom->id)->first();
        $totalMarks = 0;
        // Loop through the submitted form data
        foreach ($request->all() as $inputName => $answer) {
            // Check if the input name is a question ID 
            if (is_numeric($inputName)) {
                $question = Question::where('id', $inputName)->first();
                $exam_question = Exam_question::where('exam_id', $exam_id)->where('question_id', $inputName)->first();
                $student_exam_answer = new Student_exam_answer();
                $student_exam_answer->classroom_student_id = $classroom_student->id;
                $student_exam_answer->exam_question_id = $exam_question->id;
                $student_exam_answer->grade = 0; // default value will be updated below
                $student_exam_answer->save();
                switch ($question->type_id) {
                    case 1:
                        $mcq_answer = new Mcq_answer();
                        $mcq_answer->student_exam_answer_id = $student_exam_answer->id;
                        $mcq_answer->answer = $answer;
                        $mcq_answer->save();
                        //grading
                        if ($answer !== '?')
                        {
                            $mcq_question = Mcq_question::where('question_id', $inputName)->where('option', $answer)->first();
                            if ($mcq_question->is_correct == 'true')
                            {
                                $totalMarks += $question->grade;
                                $student_exam_answer->grade = $question->grade; // update default value
                                $student_exam_answer->save();
                            }
                        }
                        break;
                    case 2:
                        $t_f_answer = new T_f_answer();
                        $t_f_answer->student_exam_answer_id = $student_exam_answer->id;
                        $t_f_answer->answer = $answer;
                        $t_f_answer->save();
                        //grading
                        if ($answer !== '?')
                        {
                            $t_f_question = T_f_question::where('question_id', $inputName)->first();
                            if ($t_f_question->answer == $answer)
                            {
                                $totalMarks += $question->grade;
                                $student_exam_answer->grade = $question->grade; // update default value
                                $student_exam_answer->save();
                            }
                        }
                        break;
                    case 4:
                        $essay_answer = new Essay_answer();
                        $essay_answer->student_exam_answer_id = $student_exam_answer->id;
                        $essay_answer->answer = $answer;
                        $essay_answer->save();
                        // Grading
                        $essay_question = Essay_question::where('question_id', $question->id)->first();
                        if ($answer !== '?') {
                            $gradingResponse = Http::post('http://127.0.0.1:3759/grade', [
                                'student_answer' => $answer,
                                'correct_answer' => $essay_question->answer
                            ]);
                            $responseData = $gradingResponse->json();
                            $percentage = $responseData['grade_percentage'];
                            $grade = round($percentage * $question->grade);
                            $totalMarks += $grade;
                            $student_exam_answer->grade = $grade;
                            $student_exam_answer->save();
                        }
                        break;
                }

            }
            else if (preg_match('/^blank\d+$/', $inputName)) // if fill blank question
            {
                $blankId = intval(substr($inputName, 5)); //id for table Complete_question
                $complete_question = Complete_question::where('id', $blankId)->first();
                $question = Question::where('id', $complete_question->question_id)->first();
                $exam_question = Exam_question::where('exam_id', $exam_id)->where('question_id', $question->id)->first();
                $student_exam_answer = new Student_exam_answer();
                $student_exam_answer->classroom_student_id = $classroom_student->id;
                $student_exam_answer->exam_question_id = $exam_question->id;
                $student_exam_answer->grade = 0; // default value will be updated below
                $student_exam_answer->save();

                $complete_answer = new Complete_answer();
                $complete_answer->student_exam_answer_id = $student_exam_answer->id;
                $complete_answer->blank_id = $blankId;
                $complete_answer->answer = $answer;
                $complete_answer->save();
                //grading
                $complete_question = Complete_question::where('id', $blankId)->where('question_id', $question->id)->first();
                if ($complete_question->is_case_sensitive == 'true')
                {
                    if (strcmp($answer, $complete_question->blank_answer) === 0)
                    {
                        $totalMarks += $complete_question->grade;
                        $student_exam_answer->grade += $complete_question->grade;
                        $student_exam_answer->save();
                    }
                }
                else if ($complete_question->is_case_sensitive == 'false')
                {
                    if (strcasecmp($answer, $complete_question->blank_answer) === 0)
                    {
                        $totalMarks += $complete_question->grade;
                        $student_exam_answer->grade += $complete_question->grade;
                        $student_exam_answer->save();
                    }
                }

            }
            else if($inputName == 'tabSwitches')
            {
                $tabSwitchesCount = $answer . ' Tab Switches';
            }
        }
 
        $exam_result = new Exam_result();
        $exam_result->classroom_student_id = $classroom_student->id;
        $exam_result->exam_id = $exam_id;
        $exam_result->marks = $totalMarks;
        $exam_result->student_attempts = $tabSwitchesCount; 
        $exam_result->save();

        $exam = Exam::where('id', $exam_id)->first();
        $examEndTime = Carbon::parse($exam->end_date);
        $currentTime = Carbon::now()->addHours(3);;
        $remainingTime = $currentTime->diffInMinutes($examEndTime, false);
        if($remainingTime <= 30){
            $essayQuestions = Exam_question::where('exam_id', $exam->id)
                ->whereHas('question', function ($query) {
                    $query->where('type_id', 4); 
                })
                ->get();
    
            foreach ($essayQuestions as $essayQuestion) {
                $studentAnswers = [];
    
                $studentExamAnswers = Student_exam_answer::where('exam_question_id', $essayQuestion->id)->get();
    
                foreach ($studentExamAnswers as $studentExamAnswer) {
                    $essayAnswer = Essay_answer::where('student_exam_answer_id', $studentExamAnswer->id)->first();
                    $studentAnswers[$studentExamAnswer->id] = $essayAnswer->answer;
                }
    
                if (!empty($studentAnswers)) {
                    $apiResponse = Http::post('http://127.0.0.1:3758/plagiarism/predict', [
                        'essays_dict' => $studentAnswers,
                        'cased' => 'False',
                    ]);
    
                    $plagiarismResults = $apiResponse->json()['plagiarism_results'];
    
                    foreach ($plagiarismResults as $result) {
                        foreach ($result as $studentExamAnswerId => $scores) {
                            foreach ($scores as $cheaterExamAnswerId => $plagiarismPercentage) {
                                $cheatingAttempt = new Cheating_attempts();
                                $cheatingAttempt->student_exam_answer_id = $studentExamAnswerId;
                                $cheatingAttempt->cheater_id = $cheaterExamAnswerId;
                                $cheatingAttempt->plagarism_percentage = $plagiarismPercentage;
                                $cheatingAttempt->save();
                            }
                        }
                    }
                }
            }
        }
        return $request->expectsJson() ? response()->json(['message' => 'Exam Submitted Successfully.'])->setStatusCode(200)
        : redirect()->route('student_classroom.showResults', ['slug' => $slug])->with('success', 'Exam Submitted Successfully.');
    }

    public function showResults(Request $request,$slug)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        $classroom_student = Classroom_student::where('student_id', Auth::user()->id)->where('classroom_id', $classroom->id)->first();
        $examResults = Exam_result::where('classroom_student_id', $classroom_student->id)->get();
        $exam_ids = $examResults->pluck('exam_id')->unique()->toArray();
        $exams = Exam::whereIn('id', $exam_ids)->get();
        return view('classrooms.classroom_results', compact('classroom', 'examResults', 'exams'));
    }

    public function viewSubmission(Request $request,$slug,$exam_id)
    {
        $classroom = Classroom::findBySlugOrFail($slug);
        $classroom_student = Classroom_student::where('student_id', Auth::user()->id)->where('classroom_id', $classroom->id)->first();
        $exam = Exam::where('id', $exam_id)->first();
        $examResult = Exam_result::where('classroom_student_id', $classroom_student->id)->where('exam_id', $exam_id)->first();
        if ($examResult)
        {
            // Send all the Mcq questions with the correct answers and student answers 
            $mcqQuestions = Exam_question::where('exam_id', $exam_id)
                ->whereHas('question', function ($query) {
                    $query->where('type_id', 1); // Filter only MCQ questions
                })
                ->with('question')
                ->get();

            $mcqQuestionsWithOptions = [];
            foreach ($mcqQuestions as $mcqQuestion) {
                $mcqQuestionOptions = Mcq_question::where('question_id', $mcqQuestion->question_id)->get();
                $correctOption = Mcq_question::where('question_id', $mcqQuestion->question_id)
                    ->where('is_correct', 'true')
                    ->value('option');
                $student_exam_answer = Student_exam_answer::where('exam_question_id', $mcqQuestion->id)->where('classroom_student_id', $classroom_student->id)->first();
                $studentAnswer = Mcq_answer ::where('student_exam_answer_id', $student_exam_answer->id)->value('answer');
                $Mark = $student_exam_answer->grade;
                $mcqQuestionsWithOptions[] = [
                    'question' => $mcqQuestion->question,
                    'options' => $mcqQuestionOptions,
                    'correct_option' => $correctOption,
                    'student_answer' => $studentAnswer,
                    'grade' => $Mark,
                ];
            }

            // Send all the True/False questions with the correct answers and student answers 
            $tfQuestions = Exam_question::where('exam_id', $exam_id)
                ->whereHas('question', function ($query) {
                    $query->where('type_id', 2); // Filter only True/False questions
                })
                ->with('question')
                ->get();

            $tfQuestionsWithOptions = [];
            foreach ($tfQuestions as $tfQuestion) {
                $tfQuestionOptions = [
                    ['option' => 'true'],
                    ['option' => 'false'],
                ];
                $correctOption = T_f_question::where('question_id', $tfQuestion->question_id)->value('answer');
                $student_exam_answer = Student_exam_answer::where('exam_question_id', $tfQuestion->id)->where('classroom_student_id', $classroom_student->id)->first();
                $studentAnswer = T_f_answer::where('student_exam_answer_id', $student_exam_answer->id)->value('answer');
                $Mark = $student_exam_answer->grade;
                $tfQuestionsWithOptions[] = [
                    'question' => $tfQuestion->question,
                    'options' => $tfQuestionOptions,
                    'correct_option' => $correctOption,
                    'student_answer' => $studentAnswer,
                    'grade' => $Mark,
                ];
            }

            // Send all the Fill blank questions with the correct answers and student answers 
            $fillQuestions = Exam_question::where('exam_id', $exam_id)
                ->whereHas('question', function ($query) {
                    $query->where('type_id', 3); // Filter only Fill the Blank questions
                })
                ->with('question')
                ->get();
        
            $fillQuestionsWithBlanks = [];

            // Array to store ids of retrieved Student_exam_answer records
            $retrievedStudentAnswers = []; 
            
            foreach ($fillQuestions as $fillQuestion) {
                $fillQuestionBlanks = Complete_question::where('question_id', $fillQuestion->question_id)->get();
                $correctAnswers = [];
                $studentAnswers = [];
                $Mark = 0;
                foreach ($fillQuestionBlanks as $blank) {
                    $correctAnswers[$blank->id] = $blank->blank_answer;

                    // Check if a Student_exam_answer object has already been retrieved for this question and student
                    $student_exam_answer = null;
                    $foundObject = null;
                    foreach ($retrievedStudentAnswers as $obj) {
                        if ($obj->exam_question_id == $fillQuestion->id && $obj->classroom_student_id == $classroom_student->id) {
                            $foundObject = $obj;
                            break;
                        }
                    }
                    if ($foundObject !== null) {
                        // Get the next object with the same parameters
                        $student_exam_answer = Student_exam_answer::where('exam_question_id', $fillQuestion->id)
                            ->where('classroom_student_id', $classroom_student->id)
                            ->where('id', '>', $foundObject->id)
                            ->first();
                        $Mark += $student_exam_answer->grade;
                    } else {
                        // Retrieve the Student_exam_answer object
                        $student_exam_answer = Student_exam_answer::where('exam_question_id', $fillQuestion->id)
                            ->where('classroom_student_id', $classroom_student->id)
                            ->first();
                        $Mark += $student_exam_answer->grade;
                        // Add the retrieved object to the array
                        $retrievedStudentAnswers[] = $student_exam_answer;
                    }

                    $completeAnswer = Complete_answer::where('student_exam_answer_id', $student_exam_answer->id)->where('blank_id', $blank->id)->first();
                    $studentAnswer = $completeAnswer->answer;
                    $isCorrect = false;
                    if($blank->is_case_sensitive == 'true')
                    {
                        if (strcmp($studentAnswer, $correctAnswers[$blank->id]) === 0){
                            $isCorrect = true;
                        }
                    }
                    else if($blank->is_case_sensitive == 'false'){
                        if (strcasecmp($studentAnswer, $correctAnswers[$blank->id]) === 0)
                            {
                                $isCorrect = true;
                            }
                    } 
                    $studentAnswers[] = [
                        'blank_id' => $blank->id,
                        'answer' => $studentAnswer,
                        'is_correct' => $isCorrect
                    ]; 
                }
                $fillQuestionsWithBlanks[] = [
                    'question' => $fillQuestion->question,
                    'blanks' => $fillQuestionBlanks,
                    'correct_answers' => $correctAnswers,
                    'student_answers' => $studentAnswers,
                    'grade' => $Mark,
                ];
            }
        

            // Get all the Essay questions for the specified exam 
            $essayQuestions = Exam_question::where('exam_id', $exam_id)
                ->whereHas('question', function ($query) {
                    $query->where('type_id', 4); // Filter only Essay questions
                })
                ->with('question')
                ->get();
                $essayQuestionsWithAnswers = [];
                foreach ($essayQuestions as $essayQuestion) {
                    $student_exam_answer = Student_exam_answer::where('exam_question_id', $essayQuestion->id)
                        ->where('classroom_student_id', $classroom_student->id)
                        ->first();
                    $studentAnswer = Essay_answer::where('student_exam_answer_id', $student_exam_answer->id)
                        ->value('answer');

                    $Mark = $student_exam_answer->grade;
                    $essayQuestionsWithAnswers[] = [
                        'question' => $essayQuestion->question,
                        'student_answer' => $studentAnswer,
                        'grade' => $Mark,
                    ];
                }

            return view('exams.view_submission', compact('classroom', 'exam', 'mcqQuestionsWithOptions', 'tfQuestionsWithOptions', 'fillQuestionsWithBlanks', 'essayQuestionsWithAnswers'));

        }
        else{
            return redirect()->route('student_dashboard');
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

    public function getProfileImage(Request $request)
    {
        $user = Auth::user();
        $image = $user->photo;
        $imageFile = File::get('ProfilePics/students/'.$image);
        $imageBase64 = base64_encode($imageFile);
        $imageBase64 = "data:image/png;base64,".$imageBase64;
        return response()->json([
            'image' => $imageBase64
        ]);
    }
}

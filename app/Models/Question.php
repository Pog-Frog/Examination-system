<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject',
        'category',
        'text',
        'type_id',
        'grade',
        'instructor_id',
        'status',
    ];

    public function question_type()
    {
        return $this->belongsTo(Question_type::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public static function get_all_questions()
    {
        $questions = Question::join('question_types', 'questions.type_id', '=', 'question_types.id')
            ->join('instructors', 'questions.instructor_id', '=', 'instructors.id')
            ->select('questions.*', 'question_types.type_name', 'instructors.name as instructor_name')
            ->orderBy('updated_at', 'desc')
            ->get();
        return $questions;
    }
    public static function get_all_subjects()
    {
        $subjects = Question::select('subject')->distinct()->get();
        $subject_array = [];
        foreach ($subjects as $subject) {
            $subject_array[] = $subject->subject;
        }
        return $subject_array;
    }

    public static function get_all_categories()
    {
        $categories = Question::select('category')->distinct()->get();
        $category_array = [];
        foreach ($categories as $category) {
            $category_array[] = $category->category;
        }
        return $category_array;
    }


}

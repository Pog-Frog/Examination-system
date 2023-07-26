<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class Question extends Model
{
    use HasFactory;
    use Sluggable;

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

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'onUpdate' => function ($model) {
                    return $model->isDirty('title');
                },
                'method' => function ($string, $separator) {
                    return Str::random(10);
                },
                'unique' => true,
                'slugEngineOptions' => [
                    'regexp' => '/([^A-Za-z0-9]|-)+/',
                    'separator' => '-',
                ],
            ]
        ];
    }

    public function question_type()
    {
        return Question_type::where('id', $this->type_id)->first()->type_name;
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public static function get_all_questions($id)
    {
        $questions = Question::join('question_types', 'questions.type_id', '=', 'question_types.id')
            ->join('instructors', 'questions.instructor_id', '=', 'instructors.id')
            ->select('questions.*', 'question_types.type_name', 'instructors.name as instructor_name')
            ->where('classroom_id', $id)
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

    public static function findBySlugOrFail($slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }

    public function getQuestionContext()
    {
        if($this->question_type() == 'MCQ'){
            $context = Mcq_question::where('question_id', $this->id)
                ->get();
            $result = json_decode($context, true);
            usort($result, function ($a, $b) {
                return $a['is_correct'] < $b['is_correct'];
            });
            return $result;
        }elseif($this->question_type() == 'True False'){
            $context = T_f_question::where('question_id', $this->id)
                ->first();
            $result = $context->answer;
            return $result;
        }
        elseif($this->question_type() == 'Fill in the blanks') {
            $context = Complete_question::where('question_id', $this->id)
                ->get();
            $result = json_decode($context, true);
            return $result;
        }
        elseif($this->question_type() == 'Essay') {
            $context = Essay_question::where('question_id', $this->id)
                ->get();
            $result = json_decode($context, true);
            return $result[0];
        }
    }

    /*
    public function getModelAnswers()
    {
        if ($this->question_type() == 'MCQ') {
            $answer = Mcq_question::where('question_id', $this->id)
                ->where('is_correct', 'true')
                ->get();
            $answer = $answer->pluck('option');
            return $answer;
        } elseif ($this->question_type() == 'True False') {
            $answer = T_f_question::where('question_id', $this->id)
                ->get();
            $answer = $answer->pluck('answer');
            return $answer;
        } elseif ($this->question_type() == 'Fill in the blanks') {
            $answer = Complete_question::where('question_id', $this->id)
                ->get();
            $answer = $answer->pluck('blank_id', 'blank_answer', 'is_case_sensitive', 'grade');
            return $answer;
        } elseif($this->question_type() == 'Essay') {
            $answer = Essay_question::where('question_id', $this->id)
                ->get();
            $answer = $answer->pluck('answer', 'is_case_sensitive');
            return $answer;
        }
    }
    */


}

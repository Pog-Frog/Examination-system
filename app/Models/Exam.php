<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class Exam extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'description',
        'max_attempts',
        'duration',
        'total_mark',
        'classroom_instructor_id',
        'publish_status',
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

    public function classroom_instructor()
    {
        return $this->belongsTo(Classroom_instructor::class);
    }

    public static function findBySlugOrFail($slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function getQuestions()
    {
        $questions = Exam_question::where('exam_id', $this->id)
            ->join('questions', 'questions.id', '=', 'exam_questions.question_id')
            ->join('question_types', 'question_types.id', '=', 'questions.type_id')
            ->join('instructors', 'instructors.id', '=', 'questions.instructor_id')
            ->select('exam_questions.*', 'questions.*', 'question_types.type_name', 'instructors.name as instructor_name')
            ->get();
        $questions = $questions->map(function ($question) {
            $question->context = $question->getQuestionContext();
            return $question;
        });
        return $questions;
    }

    public function getExamOptions()
    {
        $options = Exam_option_status::where('exam_id', $this->id)
            ->join('exam_options', 'exam_options.id', '=', 'exam_option_statuses.option_id')
            ->select('exam_option_statuses.*', 'exam_options.*')
            ->get();
        return $options;
    }
}

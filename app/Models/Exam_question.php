<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam_question extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'question_id'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function getQuestionContext()
    {
        return $this->question->getQuestionContext();
    }

    public function getModelAnswers()
    {
        return $this->question->getModelAnswers();
    }
}

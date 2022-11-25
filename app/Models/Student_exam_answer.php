<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_exam_answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_student_id',
        'exam_question_id',
        'grade'
    ];

    public function classroom_student()
    {
        return $this->belongsTo(Classroom_student::class);
    }

    public function exam_question()
    {
        return $this->belongsTo(Exam_question::class);
    }
}

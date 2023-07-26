<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam_result extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_student_id',
        'exam_id',
        'student_attempts',
        'marks',
    ];

    public function classroom_student()
    {
        return $this->belongsTo(Classroom_student::class);
    }
}

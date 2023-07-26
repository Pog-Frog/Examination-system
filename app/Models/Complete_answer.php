<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complete_answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_exam_answer_id',
        'blank_id',
        'answer'
    ];

    public function student_exam_answer()
    {
        return $this->belongsTo(Student_exam_answer::class);
    }
}

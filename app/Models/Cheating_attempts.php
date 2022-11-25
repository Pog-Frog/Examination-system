<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheating_attempts extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_exam_answer_id',
        'cheater_id',
        'plagarism_percentage',
    ];

    public function student_exam_answer()
    {
        return $this->belongsTo(Student_exam_answer::class);
    }

    public function cheater()
    {
        return $this->belongsTo(Student_exam_answer::class);
    }
}

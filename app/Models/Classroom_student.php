<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom_student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'classroom_id',
        'date_joined',
    ];

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public static function getStudent($classroom_id, $student_id)
    {
        $student = Classroom_student::join('users', 'users.id', '=', 'classroom_students.student_id')
            ->where('classroom_students.classroom_id', $classroom_id)
            ->where('classroom_students.student_id', $student_id)
            ->select('users.*', 'classroom_students.date_joined')
            ->first();
        return $student;
    }
}

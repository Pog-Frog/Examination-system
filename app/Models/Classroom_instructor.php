<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom_instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'classroom_id',
        'date_joined',
    ];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public static function getInstructor($classroom_id, $instructor_id)
    {
        $instructor = Classroom_instructor::join('instructors', 'instructors.id', '=', 'classroom_instructors.instructor_id')
            ->where('classroom_instructors.classroom_id', $classroom_id)
            ->where('classroom_instructors.instructor_id', $instructor_id)
            ->select('instructors.*', 'classroom_instructors.date_joined')
            ->first();
        return $instructor;
    }
}

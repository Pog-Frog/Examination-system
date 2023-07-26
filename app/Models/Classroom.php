<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class Classroom extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = [
        'name',
        'code',
        'info',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => function ($model) {
                    return $model->isDirty('name');
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

    public static function findBySlugOrFail($slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function getAnnouncements()
    {
        $announcements =  $this->announcements()->orderBy('date_created', 'desc')->get()->map(function ($announcement) {
            $announcement->announcement_author = $announcement->announcement_author()->get_author();
            return $announcement;
        });
        return $announcements;
    }

    public function getStudents()
    {
        $students = Classroom_student::join('users', 'classroom_students.student_id', '=', 'users.id')
            ->where('classroom_students.classroom_id', $this->id)
            ->select('users.*', 'classroom_students.date_joined')
            ->get();

        return $students;
    }

    public function getInstructors()
    {
        $instructors = Classroom_instructor::join('instructors', 'classroom_instructors.instructor_id', '=', 'instructors.id')
            ->where('classroom_instructors.classroom_id', $this->id)
            ->select('instructors.*', 'classroom_instructors.date_joined')
            ->get();

        return $instructors;
    }

    public function getExams()
    {
        $exams = Exam::where('classroom_id', $this->id)->get();
        return $exams;
    }
    
    public function getInstructor($id)
    {
        $instructor = Classroom_instructor::where('classroom_id', $this->id)->where('instructor_id', $id)->first();
        return $instructor;
    }
}

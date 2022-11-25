<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

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

    public function classroom_instructor()
    {
        return $this->belongsTo(Classroom_instructor::class);
    }
}

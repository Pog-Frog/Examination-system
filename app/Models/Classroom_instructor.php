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
}

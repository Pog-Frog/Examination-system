<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject',
        'category',
        'text',
        'type_id',
        'grade',
        'instructor_id',
        'status',
    ];

    public function question_type()
    {
        return $this->belongsTo(Question_type::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}

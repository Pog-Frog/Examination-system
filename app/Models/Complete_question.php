<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complete_question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'blank_id',
        'blank_answer',
        'is_case_sensitive',
        'grade'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}

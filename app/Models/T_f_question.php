<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T_f_question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'answer'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}

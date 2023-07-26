<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam_option_status extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'option_id',
        'status',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function exam_option()
    {
        return $this->belongsTo(Exam_option::class);
    }
}

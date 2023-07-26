<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question_type extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_name'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}

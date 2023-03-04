<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement_author extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'author_role'
    ];

    public function get_author()
    {
        if($this->author_role == 'instructor')
        {
            return $this->belongsTo(Instructor::class, 'author_id')->first();
        }
        else if($this->author_role == 'student')
        {
            return $this->belongsTo(User::class, 'author_id')->first();
        }
        else
        {
            return null;
        }
    }
}

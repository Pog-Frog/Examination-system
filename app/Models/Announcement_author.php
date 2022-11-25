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
}

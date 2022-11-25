<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement_comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'date_created',
        'announcement_id',
        'author_id',
        'author_role'
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}

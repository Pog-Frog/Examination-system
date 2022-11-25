<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text',
        'date_created',
        'announcement_author_id'
    ];

    public function announcement_author()
    {
        return $this->belongsTo(Announcement_author::class);
    }
}

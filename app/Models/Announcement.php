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
        'announcement_author_id',
        'classroom_id',
    ];

    public function announcement_author()
    {
        return $this->belongsTo(Announcement_author::class)->first();
    }

    public static function getAnnouncement($announcement_id , $classroom_id)
    {
        $announcement = Announcement::where('id', $announcement_id)
            ->where('classroom_id', $classroom_id)
            ->first();
        $announcement->announcement_author = $announcement->announcement_author()->get_author();
        return $announcement;
    }

    public function announcement_comments()
    {
        return $this->hasMany(Announcement_comment::class);
    }

    public function getComments()
    {
        $comments = $this->announcement_comments()->orderBy('date_created', 'desc')->get()->map(function ($comment) {
            $comment->comment_author = $comment->comment_author();
            return $comment;
        });
        return $comments;
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}

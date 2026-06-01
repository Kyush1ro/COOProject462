<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['course_id', 'title', 'content'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function views()
    {
        return $this->belongsToMany(User::class, 'announcement_views', 'announcement_id', 'user_id');
    }
}

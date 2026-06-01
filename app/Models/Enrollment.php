<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
        use HasFactory;

    // links the model to 'enrollments' table to the course model and user model
    protected $fillable = [
        'student_id',
        'course_id',
        
    ];

        // --- RELATIONSHIPS ---
    function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'Academic_ID');
    }

    function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}

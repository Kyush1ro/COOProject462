<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 1. IMPORTANT: Tell Laravel your Primary Key is named 'Academic_ID'
    protected $primaryKey = 'Academic_ID';
    public $incrementing = false;
    // If your Academic_ID is NOT auto-incrementing (e.g. you type it manually),
    // set this to false. If it is auto-incrementing, leave this line out.
    // public $incrementing = false; 

    protected $fillable = [
        'Academic_ID',
        'name',
        'email',
        'password',
        'role', // Ensure this is here
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- HELPER FUNCTIONS ---
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function isInstructor()
    {
        return $this->role === 'instructor';
    }
    public function isStudent()
    {
        return $this->role === 'student';
    }

    // --- RELATIONSHIPS ---

    // For Instructors: The courses they teach
    public function teachingCourses()
    {
        // defined in courses table as instructor_id pointing to Academic_ID
        return $this->hasMany(Course::class, 'instructor_id', 'Academic_ID');
    }

    // For Students: The courses they are enrolled in
    public function enrolledCourses()
    {
        return $this->belongsToMany(
            Course::class,
            'enrollments', // Pivot table name
            'student_id',  // Foreign key on pivot table for User
            'course_id',   // Foreign key on pivot table for Course
            'Academic_ID', // Local key on User table
            'id'           // Local key on Course table
        );
    }

    // For Students: Their submissions
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id', 'Academic_ID');
    }
}

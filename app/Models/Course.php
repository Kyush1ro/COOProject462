<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    // Standard 'id' is used here, so no need to change $primaryKey

    protected $fillable = [
        'title',
        'course_code',
        'description',
        'classroom',
        'course_type',
        'instructor_id',
        'department_id',
        'semester_id',
    ];

    // --- RELATIONSHIPS ---

    // The Instructor who owns this course
    public function instructor()
    {
        // We must specify 'Academic_ID' because it's not 'id'
        return $this->belongsTo(User::class, 'instructor_id', 'Academic_ID');
    }

    // The Students enrolled in this course
    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'enrollments',
            'course_id',
            'student_id',
            'id',
            'Academic_ID'
        );
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function classroom()
    {
        return $this->hasOne(Classroom::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    // Scope to filter by active semester
    public function scopeActive($query)
    {
        return $query->whereHas('semester', function ($q) {
            $q->where('is_active', true);
        });
    }
}

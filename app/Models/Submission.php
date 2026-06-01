<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_path',
        'original_filename',
        'grade',
        'feedback',
        'feedback_file_path',
        'feedback_original_filename',
        'submission_date' // optional if you use created_at
    ];

    // --- RELATIONSHIPS ---

    // The student who uploaded this
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id', 'Academic_ID');
    }

    // The assignment this belongs to
    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
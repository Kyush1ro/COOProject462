<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 
        'title', 
        'file_path',
        'original_filename',
        'file_type',
    ];

    // Relationship: A material belongs to ONE course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
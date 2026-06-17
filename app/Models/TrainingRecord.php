<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'training_name',
        'provider',
        'completion_date',
        'certificate_url',
        'status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
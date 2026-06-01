<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['id', 'name', 'start_date', 'end_date', 'is_active'];
    
    public $incrementing = false; // Because we use manual ID (e.g. 251)
    protected $keyType = 'int';

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Helper to get active semester
    public static function getActive()
    {
        $settingId = \App\Models\Setting::getValue('current_semester');
        if ($settingId) {
            return self::find($settingId);
        }
        return self::where('is_active', true)->first();
    }
}

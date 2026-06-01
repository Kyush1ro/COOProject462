<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'Academic_ID',
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

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPlanner(): bool
    {
        return $this->role === 'planner';
    }

    public function isHR(): bool
    {
        return $this->role === 'hr';
    }

    public function isWarehouse(): bool
    {
        return $this->role === 'warehouse';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
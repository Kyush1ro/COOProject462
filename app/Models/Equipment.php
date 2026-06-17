<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_code',
        'name',
        'type',
        'location',
        'status',
        'description',
    ];

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}
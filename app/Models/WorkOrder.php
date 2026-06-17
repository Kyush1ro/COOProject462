<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_request_id',
        'equipment_id',
        'assigned_to',
        'work_order_number',
        'title',
        'description',
        'status',
        'planned_start_date',
        'planned_end_date',
        'actual_start_date',
        'actual_end_date',
    ];

    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
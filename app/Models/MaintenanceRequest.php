<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'requested_by',
        'title',
        'description',
        'priority',
        'status',
        'requested_date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}
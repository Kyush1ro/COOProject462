<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'Academic_ID',
        'name',
        'email',
        'password',
        'role',
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

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function approvedLeaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'approved_by');
    }

    public function trainingRecords()
    {
        return $this->hasMany(TrainingRecord::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class, 'requested_by');
    }

    public function assignedWorkOrders()
    {
        return $this->hasMany(WorkOrder::class, 'assigned_to');
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'created_by');
    }

    public function purchaseRequisitions()
    {
        return $this->hasMany(PurchaseRequisition::class, 'requested_by');
    }
}
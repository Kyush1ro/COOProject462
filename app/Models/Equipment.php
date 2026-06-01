public function department()
{
    return $this->belongsTo(Department::class);
}

public function maintenanceRequests()
{
    return $this->hasMany(MaintenanceRequest::class);
}

public function workOrders()
{
    return $this->hasMany(WorkOrder::class);
}
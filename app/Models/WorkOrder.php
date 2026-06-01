public function maintenanceRequest()
{
    return $this->belongsTo(MaintenanceRequest::class);
}

public function equipment()
{
    return $this->belongsTo(Equipment::class);
}

public function planner()
{
    return $this->belongsTo(User::class, 'planner_id');
}

public function spareParts()
{
    return $this->belongsToMany(SparePart::class, 'work_order_spare_parts')
                ->withPivot('quantity')
                ->withTimestamps();
}
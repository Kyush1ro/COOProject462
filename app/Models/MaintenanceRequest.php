public function equipment()
{
    return $this->belongsTo(Equipment::class);
}

public function requestedBy()
{
    return $this->belongsTo(User::class, 'requested_by');
}

public function workOrder()
{
    return $this->hasOne(WorkOrder::class);
}
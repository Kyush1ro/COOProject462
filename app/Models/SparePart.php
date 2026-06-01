public function workOrders()
{
    return $this->belongsToMany(WorkOrder::class, 'work_order_spare_parts')
                ->withPivot('quantity')
                ->withTimestamps();
}

public function transactions()
{
    return $this->hasMany(InventoryTransaction::class);
}
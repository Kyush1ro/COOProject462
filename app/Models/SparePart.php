<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_code',
        'name',
        'category',
        'quantity',
        'minimum_quantity',
        'unit',
        'storage_location',
    ];

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function purchaseRequisitions()
    {
        return $this->hasMany(PurchaseRequisition::class);
    }
}
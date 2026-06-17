<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'spare_part_id',
        'created_by',
        'transaction_type',
        'quantity',
        'reference_number',
        'notes',
    ];

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
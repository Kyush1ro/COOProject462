<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'spare_part_id',
        'requested_by',
        'pr_number',
        'item_name',
        'quantity',
        'unit',
        'status',
        'justification',
        'required_date',
    ];

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
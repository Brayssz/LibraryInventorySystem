<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionTransaction extends Model
{
    use HasFactory;

    protected $table = 'division_transactions';
    protected $primaryKey = 'div_transaction_id';
    protected $fillable = [
        'div_inventory_id',
        'transaction_type',
        'sent_to',
        'quantity',
        'approved_by',
        'reference_number',
        'date',
        'time'
    ];

    public function inventory()
    {
        return $this->belongsTo(DivisionInventory::class, 'div_inventory_id', 'div_inventory_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'sent_to', 'school_id');
    }
}

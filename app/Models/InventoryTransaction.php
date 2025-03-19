<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $table = 'inventory_transactions';
    protected $primaryKey = 'transaction_id';
    protected $fillable = ['inventory_id', 'transaction_type', 'quantity', 'approved_by', 'reference_number', 'date', 'time'];

    // Relationship: Each transaction belongs to an inventory record
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'inventory_id');
    }

    // Relationship: Each transaction is approved by a user
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}

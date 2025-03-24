<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'inventory_id', 'quantity', 'transaction_type', 'approved_by', 'reference_id', 'transaction_timestamp',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function referenceCode()
    {
        return $this->belongsTo(ReferenceCode::class, 'reference_id');
    }

    public function borrowTransaction()
    {
        return $this->hasOne(BorrowTransaction::class, 'transaction_id');
    }
}

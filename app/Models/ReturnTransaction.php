<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'return_id';

    protected $fillable = [
        'borrow_id', 
        'quantity', 
        'recorded_by', 
        'return_date', 
    ];

    public function borrowTransaction()
    {
        return $this->belongsTo(BorrowTransaction::class, 'borrow_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}

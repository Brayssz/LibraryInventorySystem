<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowTransaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'borrow_id';

    protected $fillable = [
        'book_id', 'user_id', 'transaction_id', 'borrow_timestamp', 'return_date', 'quantity_lost', 'status',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function returnTransactions()
    {
        return $this->hasMany(ReturnTransaction::class, 'borrow_id');
    }
}

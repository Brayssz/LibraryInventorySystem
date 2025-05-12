<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    use HasFactory;

    protected $primaryKey = 'request_id';

    protected $fillable = [
        'school_id', 'book_id', 'quantity', 'quantity_released', 'status', 'approved_by', 'reference_id', 'remarks', 'status', 'expected_return_date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function referenceCode()
    {
        return $this->belongsTo(ReferenceCode::class, 'reference_id');
    }
}

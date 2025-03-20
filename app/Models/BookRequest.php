<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    use HasFactory;

    protected $table = 'book_requests';
    protected $primaryKey = 'request_id'; // Custom primary key

    protected $fillable = [
        'school_id',
        'book_id',
        'quantity',
        'status',
        'approved_by',
    ];

    /**
     * Relationship: A BookRequest belongs to a School.
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'school_id');
    }

    /**
     * Relationship: A BookRequest belongs to a Book.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    /**
     * Relationship: A BookRequest may be approved by a User.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}

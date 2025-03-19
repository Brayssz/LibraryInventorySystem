<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $primaryKey = 'inventory_id';
    protected $fillable = ['book_id', 'school_id', 'quantity'];

    // Relationship: Each inventory belongs to a school
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id', 'school_id');
    }

    // Relationship: Each inventory belongs to a book
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    // Relationship: An inventory can have many transactions
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'inventory_id', 'inventory_id');
    }
}

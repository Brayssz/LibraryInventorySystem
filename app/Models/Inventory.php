<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $primaryKey = 'inventory_id';

    protected $fillable = [
        'book_id', 'location_id', 'location_type', 'quantity',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function location()
    {
        return $this->belongsTo(School::class, 'location_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'inventory_id');
    }
}

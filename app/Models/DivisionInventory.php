<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionInventory extends Model
{
    use HasFactory;

    protected $table = 'division_inventory';
    protected $primaryKey = 'div_inventory_id';
    protected $fillable = ['book_id', 'quantity'];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    public function transactions()
    {
        return $this->hasMany(DivisionTransaction::class, 'div_inventory_id', 'div_inventory_id');
    }
}

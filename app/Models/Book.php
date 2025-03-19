<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';
    protected $primaryKey = 'book_id';
    protected $fillable = ['title', 'author', 'isbn', 'published_year', 'status'];

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'book_id', 'book_id');
    }
}

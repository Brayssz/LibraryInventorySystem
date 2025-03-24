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

    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'book_id');
    }

    public function borrowTransactions()
    {
        return $this->hasMany(BorrowTransaction::class);
    }
}

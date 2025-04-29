<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Book extends Model
{
    use HasFactory, WithFileUploads;

    protected $table = 'books';
    protected $primaryKey = 'book_id';
    protected $fillable = ['title', 'author', 'published_date', 'status', 'book_photo_path'];

    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'book_id');
    }

    public function divisionInventory()
    {
        return $this->hasMany(Inventory::class, 'book_id')->where('location_type', 'division');
    }

    public function borrowTransactions()
    {
        return $this->hasMany(BorrowTransaction::class);
    }
}

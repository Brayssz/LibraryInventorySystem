<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';
    protected $primaryKey = 'school_id';
    protected $fillable = ['name', 'address', 'phone_number', 'email', 'status', 'password'];

    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'school_id', 'school_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceCode extends Model
{
    use HasFactory;

    protected $primaryKey = 'reference_id';

    protected $fillable = ['reference_code'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'reference_id');
    }
}

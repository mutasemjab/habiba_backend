<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRate extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'client_id', 'rate'];

    // Each ProductRate belongs to one Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Each ProductRate belongs to one User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
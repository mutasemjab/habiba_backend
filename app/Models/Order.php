<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $with = ['orderItems','driver','notifications'];
    function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    function notifications()
    {
        return $this->hasMany(OrderNotification::class);
    }
    function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    function client()
    {
        return $this->belongsTo(Client::class);
    }
}
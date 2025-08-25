<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $guarded = [];
    protected function cart()
    {
        return $this->hasOne(Cart::class);
    }
    protected function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function productRates()
    {
        return $this->hasMany(ProductRate::class);
    }
    public function ratings()
    {
        return $this->hasMany(ProductRate::class);
    }
    public function fcmToken()
    {
        return $this->hasOne(UserFCMTokens::class);
    }
}
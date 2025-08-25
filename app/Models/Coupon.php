<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function users()
    {
        return $this->belongsToMany(Client::class, 'coupon_user')->withTimestamps();
    }

    public function isUsedBy($user)
    {
        return $this->users()->where('client_id', $user->id)->exists();
    }
    public function isExpired()
    {
        // Assuming you have an 'expires_at' column in your coupons table
        return $this->expires_at && $this->expires_at < now();
    }
}
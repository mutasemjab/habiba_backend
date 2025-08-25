<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Driver extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $guarded=[];
    public function fcmToken()
    {
        return $this->hasOne(UserFCMTokens::class,'driver_id');
    }

}

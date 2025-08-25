<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFCMTokens extends Model
{
    use HasFactory;
    protected $guarded =[];
    function client(){
        return $this->belongsTo(Client::class,'id');
    }
    function driver(){
        return $this->belongsTo(Driver::class,'id');
    }
}
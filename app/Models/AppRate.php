<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppRate extends Model
{
    use HasFactory;
    protected $guarded=[];
    
    function client()
    {
        return $this->belongsTo(Client::class);
    }
}
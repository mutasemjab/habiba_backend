<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSlider extends Model
{
     protected $guarded = [];
     
       public function sub_category()
    {
        return $this->belongsTo(SubCategory::class);
    }
}

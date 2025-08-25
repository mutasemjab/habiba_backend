<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Brand extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function getBrandNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->ar_brand_name : $this->attributes['brand_name'];
    }
    public function getEnName()
    {
        return $this->attributes['brand_name']; // Assuming 'brand_name' stores the English name
    }
    protected function casts(): array
    {
        return [
            'categories_id' => 'array',
        ];
    }
}

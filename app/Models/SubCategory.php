<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SubCategory extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function getSubCategoryNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->ar_sub_category_name : $this->attributes['sub_category_name'];
    }
    public function getEnName()
    {
        return $this->attributes['sub_category_name'];
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    protected $casts = [
        'categories_ids' => 'array',
    ];
}

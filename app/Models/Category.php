<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $appends = ['has_subcategory'];

    public function getCategoryNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->ar_category_name : $this->attributes['category_name'];
    }
    public function getEnName()
    {
        return $this->attributes['category_name'];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function sub_categories()
    {
        return $this->hasMany(SubCategory::class);
    }
     public function getHasSubcategoryAttribute()
    {
        return $this->sub_categories()->exists() ? 1 : 2;
    }
}

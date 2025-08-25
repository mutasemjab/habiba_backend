<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

     protected static function booted()
    {
        static::addGlobalScope('active', function ($query) {
            $query->where('product_status', 1);
        });
    }
    
    // public function getProductNameAttribute()
    // {
    //     return app()->getLocale() === 'ar' ? $this->ar_product_name : $this->attributes['product_name'];
    // }
    // public function getEnName()
    // {
    //     return $this->attributes['product_name'];
    // }
    
    public function getProductNameAttribute()
{
    return app()->getLocale() === 'ar' ? $this->ar_product_name : $this->attributes['product_name'];
}

public function getEnglishNameAttribute()
{
    return $this->attributes['product_name'];
}

public function getArabicNameAttribute()
{
    return $this->ar_product_name;
}

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function productRates()
    {
        return $this->hasMany(ProductRate::class);
    }

    // Accessors
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->category_name : null;
    }

    public function getSubCategoryNameAttribute()
    {
        return $this->sub_category ? $this->sub_category->sub_category_name : null;
    }

    public function getBrandNameAttribute()
    {
        return $this->brand ? $this->brand->brand_name : null;
    }

    public function activeOffer()
    {
        return $this->offers()
            ->where('status', '1')
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->first();
    }


    public function getOfferPercentageAttribute()
    {
        $activeOffer = $this->activeOffer();
        return $activeOffer ? $activeOffer->percentage : null; // Fixed typo from 'persentage'
    }

    public function getAverageRateAttribute()
    {
        return $this->productRates()->avg('rate') ?? '0'; // Changed to return numeric type 0
    }

    // Casts
    protected $casts = [
        'gallary' => 'array',
    ];

    // Eager loading related models
    protected $with = ['category', 'sub_category', 'brand', 'productRates'];

    // Custom attributes to always include
    protected $appends = ['average_rate']; // Add average_rate to the model's array and JSON forms
}

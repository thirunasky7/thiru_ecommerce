<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'seller_id',
        'shop_id',
        'price',
        'stock',
        'status',
        'slug',
        'currency',
        'SKU',
        'weight',
        'dimensions',
        'product_type',
        'image_url',
        'vendor_id',
        'service_type_id',
        'brand_id',
        'is_coming_soon',
        'is_food_menu',
        'booking_from_datetime',
        'booking_to_datetime',
        'delivery_to_datetime',
        'product_mode',
        'discount_price',
        'is_featured',
    ];

    protected $casts = [
        'available_from_date' => 'date',
        'available_to_date' => 'date',
        'available_from_time' => 'datetime',
        'available_to_time' => 'datetime',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'status' => 'boolean',
        'is_featured' => 'boolean',
        'is_coming_soon' => 'boolean',
    ];

    public function isPreorder(): bool
    {
        return $this->product_mode === 'preorder';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFood($query)
    {
        return $query->where('is_food_menu', 'yes');
    }

    public function scopeVegetable($query)
    {
        return $query->where('is_food_menu', 'no');
    }

    public function scopePreorder($query)
    {
        return $query->where('product_mode', 'preorder');
    }

    public function scopeRegular($query)
    {
        return $query->where(function ($q) {
            $q->where('product_mode', '!=', 'preorder')
              ->orWhereNull('product_mode');
        });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOfService($query, $serviceTypeId)
    {
        return $query->where('service_type_id', $serviceTypeId);
    }

    public function translation()
    {
        return $this->hasOne(ProductTranslation::class);
    }

    public function weeklyMenus()
    {
        return $this->belongsToMany(WeeklyMenu::class);
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getTranslation($field, $locale = 'en')
    {
        $translation = $this->translations->firstWhere('language_code', $locale);

        return $translation ? $translation->$field : null;
    }

    public function thumbnail()
    {
        return $this->hasOne(ProductImage::class)->where('type', 'thumb');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->approved()->latest();
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function getConvertedPriceAttribute()
    {
        return convert_price($this->price);
    }

    public function getConvertedDiscountPriceAttribute()
    {
        return $this->discount_price ? convert_price($this->discount_price) : null;
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values')
                    ->with('attribute', 'translations');
    }

    public function primaryVariant()
    {
        return $this->hasOne(ProductVariant::class)->where('is_primary', true);
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(Customer::class, 'wishlists');
    }
}
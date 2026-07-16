<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'seller_id',
        'service_type_id',
        'name',
        'slug',
        'logo',
        'cover_image',
        'description',
        'address',
        'city',
        'phone',
        'delivery_time',
        'min_order_amount',
        'delivery_fee',
        'rating',
        'is_featured',
        'is_open',
        'status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_open' => 'boolean',
        'min_order_amount' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($shop) {
            if (empty($shop->slug)) {
                $shop->slug = Str::slug($shop->name);
            }
            // Keep seller_id in sync with vendor_id for legacy code
            if (empty($shop->seller_id) && !empty($shop->vendor_id)) {
                $shop->seller_id = $shop->vendor_id;
            }
            if (empty($shop->vendor_id) && !empty($shop->seller_id)) {
                $shop->vendor_id = $shop->seller_id;
            }
        });
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function seller()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOfService($query, $serviceTypeId)
    {
        return $query->where('service_type_id', $serviceTypeId);
    }
}

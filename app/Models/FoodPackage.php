<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FoodPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'shop_id',
        'name',
        'slug',
        'description',
        'includes',
        'price',
        'compare_price',
        'duration_days',
        'meals_per_day',
        'meal_types',
        'sample_menu',
        'image',
        'badge',
        'is_featured',
        'status',
        'sort_order',
        'max_subscribers',
        'available_from',
        'available_until',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'meal_types' => 'array',
        'sample_menu' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'available_from' => 'date',
        'available_until' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($package) {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->name);
            }
        });
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function isAvailable(): bool
    {
        if (!$this->status) {
            return false;
        }

        $today = Carbon::today();

        if ($this->available_from && $today->lt($this->available_from)) {
            return false;
        }

        if ($this->available_until && $today->gt($this->available_until)) {
            return false;
        }

        return true;
    }

    public function getEndDateFor(Carbon|string $startDate): Carbon
    {
        return Carbon::parse($startDate)->copy()->addDays(max(1, (int) $this->duration_days) - 1);
    }

    public function getSavingsAttribute(): ?float
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (float) ($this->compare_price - $this->price);
        }

        return null;
    }

    public function getMealTypesLabelAttribute(): string
    {
        $types = $this->meal_types ?? [];
        return collect($types)->map(fn ($t) => ucfirst($t))->join(', ');
    }
}

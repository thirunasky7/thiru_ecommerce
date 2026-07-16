<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendor extends Authenticatable
{
    use Notifiable;

    protected $guard = 'vendor';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'business_name',
        'logo',
        'cover_image',
        'description',
        'address',
        'city',
        'state',
        'pincode',
        'commission_rate',
        'is_featured',
        'rating',
        'total_orders',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'is_featured' => 'boolean',
        'commission_rate' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isApproved(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
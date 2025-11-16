<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_email',
        'payment_method',
        'status',
        'subtotal',
        'shipping',
        'tax',
        'total_amount',
        'order_notes',
        'order_date',
        'delivered_date',
        'shipping_method',
        'tracking_number',
        'discount_amount',
        'coupon_code',
        'tax_rate',
        'shipping_address',
        'billing_address',
        'special_instructions',
        'preparation_time',
        'expected_delivery_time',
        'actual_delivery_time',
        'cancellation_reason',
        'refund_amount',
        'refund_reason',
        'payment_status',
        'transaction_id',
        'payment_details',
        'delivery_charge',
        'packaging_charge',
        'tip_amount',
        'loyalty_points_used',
        'loyalty_points_earned',
        'rating',
        'feedback',
        'is_guest_order',
        'ip_address',
        'user_agent',
        'device_type',
        'app_version',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'delivered_date' => 'datetime',
        'expected_delivery_time' => 'datetime',
        'actual_delivery_time' => 'datetime',
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'packaging_charge' => 'decimal:2',
        'tip_amount' => 'decimal:2',
        'is_guest_order' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('order_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('order_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('order_date', now()->month);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_description',
        'product_image',
        'variant_id',
        'variant_name',
        'variant_attributes',
        'quantity',
        'unit_price',
        'total_price',
        'discount_amount',
        'tax_amount',
        'final_price',
        'order_for_date',
        'meal_type',
        'expected_delivery_date',
        'actual_delivery_date',
        'preparation_status',
        'cooking_instructions',
        'special_requests',
        'allergies_notes',
        'spice_level',
        'item_notes',
        'item_rating',
        'item_feedback',
        'is_ready',
        'ready_time',
        'is_delivered',
        'delivered_time',
        'is_cancelled',
        'cancellation_reason',
        'refund_status',
        'refund_amount',
        'replacement_status',
        'replacement_reason',
        'batch_number',
        'expiry_date',
        'weight',
        'volume',
        'dimensions',
        'sku',
        'barcode',
        'category_id',
        'category_name',
        'brand_id',
        'brand_name',
        'nutritional_info',
        'ingredients',
        'item_data',
        'customizations',
        'addons',
        'combo_items',
        'gift_wrap',
        'gift_message',
        'package_type',
        'storage_instructions',
        'heating_instructions',
        'serving_suggestions',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'order_for_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'expiry_date' => 'date',
        'ready_time' => 'datetime',
        'delivered_time' => 'datetime',
        'is_ready' => 'boolean',
        'is_delivered' => 'boolean',
        'is_cancelled' => 'boolean',
        'gift_wrap' => 'boolean',
        'variant_attributes' => 'array',
        'item_data' => 'array',
        'customizations' => 'array',
        'addons' => 'array',
        'combo_items' => 'array',
        'nutritional_info' => 'array',
        'ingredients' => 'array',
        'weight' => 'decimal:3',
        'volume' => 'decimal:3',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // Scopes
    public function scopePreOrder($query)
    {
        return $query->where('meal_type', '!=', 'regular');
    }

    public function scopeRegular($query)
    {
        return $query->where('meal_type', 'regular');
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('order_for_date', $date);
    }

    public function scopeMealType($query, $mealType)
    {
        return $query->where('meal_type', $mealType);
    }

    public function scopeReady($query)
    {
        return $query->where('is_ready', true);
    }

    public function scopePendingPreparation($query)
    {
        return $query->where('is_ready', false)->where('is_cancelled', false);
    }
}
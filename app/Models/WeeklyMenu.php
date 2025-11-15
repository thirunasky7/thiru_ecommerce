<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyMenu extends Model
{
    use HasFactory;

    protected $fillable = ['day', 'meal_type', 'product_ids', 'status'];

    protected $casts = [
        'product_ids' => 'array',
    ];

    // Get products for this menu
    public function products()
{
    return Product::whereIn('id', $this->product_ids ?? []);
}

    // Check if menu is available for ordering
    public function isAvailableForOrdering()
    {
        return $this->status === 'active';
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualOrder extends Model
{
    protected $fillable = ['door_number', 'total_amount', 'status'];

    public function items()
    {
        return $this->hasMany(ManualOrderItem::class);
    }
}

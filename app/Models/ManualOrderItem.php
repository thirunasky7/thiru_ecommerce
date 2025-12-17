<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualOrderItem extends Model
{
    protected $fillable = ['order_id', 'item_name', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(ManualOrder::class);
    }
}

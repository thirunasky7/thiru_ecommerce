<?php
// app/Services/PreorderService.php
namespace App\Services;

use Carbon\Carbon;
use App\Models\Product;

class PreorderService
{
    public static function checkMenuAvailability(Product $product): array
    {
        // Regular items are always available (for "immediate" purchase)
        if (! $product->isPreorder()) {
            return [
                'is_available' => true,
                'message' => '',
                'delivery_date' => Carbon::now()->toDateString()
            ];
        }

        $now = Carbon::now();
        $cutoff = Carbon::now()->setTime(config('preorder.cutoff_hour'), config('preorder.cutoff_minute'), 0);

        if ($now->greaterThan($cutoff)) {
            // After cutoff: earliest available date is day after tomorrow
            $deliveryDate = Carbon::now()->addDays(2)->toDateString();
            return [
                'is_available' => false, // can't fulfil for tomorrow, but we can inform user
                'message' => "Today's pre-order closed. Earliest available: {$deliveryDate}",
                'delivery_date' => $deliveryDate
            ];
        }

        // Before or equal cutoff -> available for tomorrow
        $deliveryDate = Carbon::now()->addDay()->toDateString();
        return [
            'is_available' => true,
            'message' => "Available for delivery on {$deliveryDate}",
            'delivery_date' => $deliveryDate
        ];
    }
}

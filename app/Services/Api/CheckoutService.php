<?php

namespace App\Services\Api;

use App\Models\Order;
use App\Models\OrderDetail as OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Storage;

class CheckoutService
{
    public function store(array $data)
    {
        // If cart is empty
        if (empty($data['cart']) || !is_array($data['cart'])) {
            return [
                'status' => false,
                'message' => 'Cart is empty or invalid!',
            ];
        }

        $cart = $data['cart'];

        // 🔹 Find or create customer
        $customer = Customer::updateOrCreate(
            ['phone' => $data['mobile_number']],
            [
                'name' => $data['name'],
                'email' => $data['email'] ?? $data['name'].'@gmail.com',
                'address' => $data['address'],
            ]
        );

        // 🔹 Calculate total
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // 🔹 Create order
        $order = Order::create([
            'customer_id' => $customer->id,
            'address' => $data['address'],
            'payment_method' => $data['payment_method'],
            'total' => $total,
            'status' => 'pending',
        ]);

        // 🔹 Save order items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // 🔹 Return API response
        return true;
    }


public function myOrders(array $data)
{
    $validator = Validator::make($data, [
        'mobile_number' => 'required|string|min:10|max:15',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // Step 1: Find Customer
    $customer = Customer::where('phone', $data['mobile_number'])->first();

    if (!$customer) {
        return response()->json([
            'success' => false,
            'message' => 'Customer not found.'
        ], 404);
    }

    // Step 2: Fetch Order Items directly
    $send_data['orders'] = Order::where('customer_id', $customer->id)
        ->with(['items.product.translation'])
        ->get()
        ->flatMap(function ($order) {
            return $order->items->map(function ($item) use ($order) {
                $product = $item->product;

                $productName = $product->translation->name
                    ?? $product->name
                    ?? 'Product';
                $productImage = optional($product->thumbnail)->image_url ?? null;
                $productImage = $productImage ? Storage::url($productImage) : null;

                return [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $productName,
                    'product_image' => $productImage,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            });
        })
        ->values(); // reset array keys

    // Step 3: Return only item list
    return $send_data;
}

}

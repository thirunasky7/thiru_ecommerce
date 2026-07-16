<?php

namespace App\Services\Api;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckoutService
{
    public function store(array $data)
    {
        if (empty($data['cart']) || !is_array($data['cart'])) {
            return [
                'status' => false,
                'message' => 'Cart is empty or invalid!',
            ];
        }

        $cart = $data['cart'];
        $email = $data['email'] ?? (Str::slug($data['name'] ?? 'guest') . '@thaiyur.local');

        $customer = Customer::where('phone', $data['mobile_number'])->first();

        if ($customer) {
            $customer->update([
                'name' => $data['name'],
                'address' => $data['address'],
            ]);
        } else {
            $customer = Customer::create([
                'name' => $data['name'],
                'phone' => $data['mobile_number'],
                'email' => $email,
                'address' => $data['address'],
                'password' => Hash::make(Str::random(12)),
                'status' => 'active',
            ]);
        }

        $subtotal = collect($cart)->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'customer_id' => $customer->id,
            'customer_name' => $data['name'],
            'customer_phone' => $data['mobile_number'],
            'customer_address' => $data['address'],
            'customer_email' => $email,
            'payment_method' => $data['payment_method'] ?? 'cod',
            'status' => 'pending',
            'subtotal' => $subtotal,
            'shipping' => $data['shipping'] ?? 0,
            'tax' => $data['tax'] ?? 0,
            'total_amount' => $subtotal + ($data['shipping'] ?? 0) + ($data['tax'] ?? 0),
            'order_date' => now(),
            'is_guest_order' => true,
        ]);

        foreach ($cart as $item) {
            $qty = (int) ($item['quantity'] ?? 1);
            $price = (float) ($item['price'] ?? 0);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'] ?? $item['product_name'] ?? 'Product',
                'quantity' => $qty,
                'unit_price' => $price,
                'total_price' => $price * $qty,
                'order_for_date' => $item['order_for_date'] ?? now()->toDateString(),
                'meal_type' => $item['meal_type'] ?? 'regular',
                'expected_delivery_date' => $item['expected_delivery_date'] ?? now()->addDay()->toDateString(),
                'product_image' => $item['image'] ?? null,
                'item_data' => $item,
            ]);
        }

        return [
            'status' => true,
            'message' => 'Order placed successfully',
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ];
    }

    public function myOrders(array $data)
    {
        $validator = Validator::make($data, [
            'mobile_number' => 'required|string|min:10|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $customer = Customer::where('phone', $data['mobile_number'])->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        $send_data['orders'] = Order::where('customer_id', $customer->id)
            ->with(['items.product.translation', 'items.product.thumbnail'])
            ->latest()
            ->get()
            ->flatMap(function ($order) {
                return $order->items->map(function ($item) use ($order) {
                    $product = $item->product;
                    $productName = $item->product_name
                        ?? optional(optional($product)->translation)->name
                        ?? optional($product)->name
                        ?? 'Product';
                    $productImage = $item->product_image
                        ?? optional(optional($product)->thumbnail)->image_url;
                    $productImage = $productImage ? Storage::url($productImage) : null;

                    return [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'status' => $order->status,
                        'product_id' => $item->product_id,
                        'product_name' => $productName,
                        'product_image' => $productImage,
                        'quantity' => $item->quantity,
                        'price' => $item->unit_price,
                    ];
                });
            })
            ->values();

        return $send_data;
    }
}

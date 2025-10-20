<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $product = \App\Models\Product::with(['translations', 'thumbnail'])->find($item['product_id']);

            $variant = isset($item['variant_id'])
                ? ProductVariant::with('images')->find($item['variant_id'])
                : ProductVariant::where('product_id', $item['product_id'])->where('is_primary', true)->first();

            $subtotal += $item['price'] * $item['quantity'];
        }   
        
        $shipping = null;
        $total = $subtotal + ($shipping ?? 0);

        return view('themes.xylo.checkout', compact('cart', 'subtotal', 'shipping', 'total'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'payment_method' => 'required',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        // Calculate total
        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Save Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'total' => $total,
            'status' => 'pending',
        ]);

        // Save Order Items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'attributes' => json_encode($item['attributes']),
            ]);
        }

        // Clear the session cart
        Session::forget('cart');

        return redirect()->route('thankyou')->with('success', 'Order placed successfully!');
    }
}

<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderDetail as OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Models\Customer;

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
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'address' => 'required|string|max:255',
                'payment_method' => 'required|in:card,paypal,cod,bank_transfer,gpay',
            ]);

            // Get Cart Data
            $cart = Session::get('cart', []);

            if (empty($cart)) {
                return redirect()->back()->with('error', 'Cart is empty!');
            }

            $customer = Customer::updateOrCreate(
                ['phone' => $request->phone],
                [
                    'name' => $request->full_name,
                    'address' => $request->address,
                ]
            );
            $subtotal = collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            $shipping = 0; // you can change dynamically
            $tax = 0;

            $total = $subtotal + $shipping + $tax;
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_id' => $customer->id,
                'address' => $request->address,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'total_amount' => $total
            ]);

            // ? Create Order Items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'attributes' => json_encode($item['attributes'] ?? []),
                ]);
            }

            // ? Clear Cart
            Session::forget('cart');

            // ? Return Success View
            return view('themes.xylo.payment.success', [
                'payment' => $request->payment_method,
                'order' => $order,
                'message' => 'Order placed successfully!'
            ]);
        }

}

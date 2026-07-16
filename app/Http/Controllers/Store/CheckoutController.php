<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.page')->with('error', 'Your cart is empty!');
        }

        $subtotal = 0;
        $cartItems = [];

        foreach ($cart as $cartItemId => $item) {
            $itemSubtotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
            $subtotal += $itemSubtotal;

            $cartItems[] = array_merge($item, [
                'cart_item_id' => $cartItemId,
                'subtotal' => $itemSubtotal,
            ]);
        }

        $shipping = 0;
        $tax = 0;
        $total = $subtotal + $shipping + $tax;

        return view('themes.xylo.checkout', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod',
            'email' => 'nullable|email',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.page')->with('error', 'Your cart is empty!');
        }

        $email = $request->email ?: (Str::slug($request->full_name) . '@thaiyur.local');

        $customer = Customer::where('phone', $request->phone)->first();
        if ($customer) {
            $customer->update([
                'name' => $request->full_name,
                'address' => $request->address,
            ]);
        } else {
            $customer = Customer::create([
                'name' => $request->full_name,
                'phone' => $request->phone,
                'email' => $email,
                'address' => $request->address,
                'password' => Hash::make(Str::random(12)),
                'status' => 'active',
            ]);
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }

        $shipping = 0;
        $tax = 0;
        $total = $subtotal + $shipping + $tax;
        $orderNumber = 'ORD' . date('Ymd') . strtoupper(Str::random(6));

        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'customer_id' => $customer->id,
            'customer_name' => $request->full_name,
            'customer_phone' => $request->phone,
            'customer_email' => $email,
            'customer_address' => $request->address,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total_amount' => $total,
            'order_notes' => $request->notes,
            'order_date' => Carbon::now(),
            'is_guest_order' => !Auth::guard('customer')->check(),
        ]);

        foreach ($cart as $cartItemId => $item) {
            $qty = (int) ($item['quantity'] ?? 1);
            $price = (float) ($item['price'] ?? 0);
            $isPackage = ($item['item_type'] ?? '') === 'package' || !empty($item['food_package_id']);

            OrderItem::create([
                'order_id' => $order->id,
                'item_type' => $isPackage ? 'package' : 'product',
                'product_id' => $item['product_id'] ?? null,
                'food_package_id' => $item['food_package_id'] ?? null,
                'product_name' => $item['name'] ?? 'Item',
                'quantity' => $qty,
                'unit_price' => $price,
                'total_price' => $price * $qty,
                'order_for_date' => $item['order_for_date'] ?? ($item['package_start_date'] ?? now()->toDateString()),
                'package_start_date' => $item['package_start_date'] ?? null,
                'package_end_date' => $item['package_end_date'] ?? null,
                'meal_type' => $isPackage ? 'package' : ($item['meal_type'] ?? 'regular'),
                'expected_delivery_date' => $item['expected_delivery_date'] ?? ($item['package_start_date'] ?? now()->addDay()->toDateString()),
                'product_image' => $item['image'] ?? null,
                'item_data' => [
                    'cart_item_id' => $cartItemId,
                    'original_cart_data' => $item,
                ],
            ]);
        }

        Session::forget('cart');
        Session::put('cart_count', 0);

        return view('themes.xylo.payment.success', [
            'order' => $order,
            'message' => 'Order placed successfully! Your monthly package or items will be prepared as scheduled.',
        ]);
    }
}

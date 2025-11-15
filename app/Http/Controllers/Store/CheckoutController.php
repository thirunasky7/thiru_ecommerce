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
            $product = Product::with(['translations', 'thumbnail'])->find($item['product_id']);
            
            if ($product) {
                $itemSubtotal = $item['price'] * $item['quantity'];
                $subtotal += $itemSubtotal;
                
                $cartItems[] = [
                    'cart_item_id' => $cartItemId,
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'image' => $item['image'],
                    'order_for_date' => $item['order_for_date'],
                    'meal_type' => $item['meal_type'],
                    'expected_delivery_date' => $item['expected_delivery_date'],
                    'display_order_date' => $item['display_order_date'],
                    'product' => $product
                ];
            }
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
        ]);

        // Get Cart Data
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.page')->with('error', 'Your cart is empty!');
        }

        // Create or find customer
        $customer = Customer::updateOrCreate(
            ['phone' => $request->phone],
            [
                'name' => $request->full_name,
                'address' => $request->address,
                'email' => $request->email ?? null,
            ]
        );

        // Calculate totals
        $subtotal = 0;
        $itemDetails = [];

        foreach ($cart as $cartItemId => $item) {
            $itemSubtotal = $item['price'] * $item['quantity'];
            $subtotal += $itemSubtotal;
            
            $itemDetails[] = [
                'cart_item_id' => $cartItemId,
                'product_id' => $item['product_id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'order_for_date' => $item['order_for_date'],
                'meal_type' => $item['meal_type'],
                'expected_delivery_date' => $item['expected_delivery_date'],
                'image' => $item['image'],
                'subtotal' => $itemSubtotal
            ];
        }

        $shipping = 0;
        $tax = 0;
        $total = $subtotal + $shipping + $tax;

        // Generate order number
        $orderNumber = 'ORD' . date('Ymd') . strtoupper(uniqid());

        // Create Order
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'customer_id' => $customer->id,
            'customer_name' => $request->full_name,
            'customer_phone' => $request->phone,
            'customer_address' => $request->address,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total_amount' => $total,
            'order_notes' => $request->notes,
            'order_date' => Carbon::now(),
        ]);

        // Create Order Items
        foreach ($itemDetails as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total_price' => $item['subtotal'],
                'order_for_date' => $item['order_for_date'],
                'meal_type' => $item['meal_type'],
                'expected_delivery_date' => $item['expected_delivery_date'],
                'product_image' => $item['image'],
                'item_data' => json_encode([ // Store all frontend data exactly as is
                    'cart_item_id' => $item['cart_item_id'],
                    'original_cart_data' => $item
                ]),
            ]);
        }

        // Clear Cart
        Session::forget('cart');

        // Update cart count in session
        Session::put('cart_count', 0);

        return view('themes.xylo.payment.success', [
            'order' => $order,
            'message' => 'Order placed successfully! We will deliver your order as per the scheduled dates.'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderTrackingController extends Controller
{
    public function showTrackingPage()
    {
        return view('themes.xylo.order-tracking');
    }

    public function trackOrder(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|digits:10',
            'order_id' => 'nullable|string'
        ]);

        $mobileNumber = $request->mobile_number;
        $orderId = $request->order_id;

        // Find orders by mobile number
        $orders = Order::where('customer_phone', $mobileNumber)
            ->when($orderId, function($query, $orderId) {
                return $query->where('order_number', 'like', "%{$orderId}%");
            })
            ->with(['orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'orders' => $orders,
                'html' => view('themes.xylo.partials.order-tracking-results', compact('orders'))->render()
            ]);
        }

        return view('themes.xylo.order-tracking', compact('orders', 'mobileNumber'));
    }

    public function getOrderDetails($orderId)
    {
        $order = Order::with(['orderItems.product'])
            ->where('order_number', $orderId)
            ->orWhere('id', $orderId)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'order' => $order,
            'html' => view('themes.xylo.partials.order-details-modal', compact('order'))->render()
        ]);
    }
}
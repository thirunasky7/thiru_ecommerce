<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;

class OrderHistoryController extends Controller
{

   public function showForm()
    {
        return view('orders.history-form');
    }

    public function fetchOrders(Request $request)
    {
        $request->validate(['unique_id' => 'required|string']);

        $customer = Customer::where('unique_id', $request->unique_id)->first();

        if (!$customer) {
            return back()->withErrors(['unique_id' => 'Invalid Customer ID.']);
        }

        $orders = Order::where('customer_id', $customer->id)
                       ->latest()
                       ->get();

        return view('orders.history-list', compact('orders', 'customer'));
    }
}
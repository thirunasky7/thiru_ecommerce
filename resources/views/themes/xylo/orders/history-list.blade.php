@extends('themes.xylo.partials.app')

@section('title', 'Thaiyur Shop - Online Shopping')
<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;     /* Firefox */
}
.slide {
            display: none;
            opacity: 0;
          
        }
        .active-slide {
            display: block;
            opacity: 1;
        }
        
        /* Food menu availability styles */
        .product-unavailable {
            position: relative;
            
            opacity: 1.5;
            pointer-events: none;
        }
        
        .unavailable-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            z-index: 10;
            text-align: center;
            font-size: 14px;
        }
        
        .product-card-container {
            position: relative;
        }
</style>
@section('content')
@php $currency = activeCurrency(); @endphp
 
<div class="max-w-4xl mx-auto mt-20 bg-white shadow-lg p-6 rounded-xl">
    <h2 class="text-2xl font-semibold mb-4 text-green-700">Order History for {{ $customer->name }}</h2>
    <p class="text-gray-600 mb-4">Customer ID: <strong>{{ $customer->unique_id }}</strong></p>

    @if($orders->isEmpty())
        <p class="text-gray-500">No orders found for this account.</p>
    @else
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead class="bg-green-100">
                <tr>
                    <th class="border p-2 text-left">Order ID</th>
                    <th class="border p-2 text-left">Date</th>
                    <th class="border p-2 text-left">Status</th>
                    <th class="border p-2 text-right">Total</th>
                </tr>
            </thead>
           <tbody>
@foreach($orders as $order)
    <tr class="bg-gray-50">
        <td class="border p-2 align-top">
            #{{ $order->id }}
        </td>
        <td class="border p-2 align-top">
            {{ $order->created_at->format('d M Y, h:i A') }}
        </td>
        <td class="border p-2 align-top">
            {{ ucfirst($order->status) }}
        </td>
        <td class="border p-2 align-top text-right">
            ₹{{ number_format($order->total_amount, 2) }}
        </td>
    </tr>

    {{-- Order items list --}}
    <tr>
        <td colspan="4" class="border-t border-b p-3 bg-white">
            @php
                $items = $order->items ?? [];
            @endphp

            @if(count($items) > 0)
                <div class="text-gray-700">
                    <strong>Items:</strong>
                    <ul class="list-disc pl-6 mt-1">
                        @foreach($items as $item)
                            <li>{{ optional($item->product->translation)->name ??$item->product->name ?? 'Product' }}</li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-gray-500 italic">No items found for this order.</p>
            @endif
        </td>
    </tr>
@endforeach
</tbody>

        </table>
    @endif
</div>
@endsection

<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Your Orders</h2>
        <span class="text-gray-600">{{ $orders->count() }} order(s) found</span>
    </div>

    @foreach($orders as $order)
    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <!-- Order Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->order_number }}</h3>
                    <p class="text-sm text-gray-600">
                        Placed on {{ $order->created_at->format('M d, Y \\a\\t h:i A') }}
                        @if($order->delivery_date)
                        • Delivery: {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}
                        @endif
                    </p>
                </div>
                <div class="mt-2 md:mt-0 flex items-center space-x-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        @if($order->order_status == 'delivered') bg-green-100 text-green-800
                        @elseif($order->order_status == 'shipped') bg-blue-100 text-blue-800
                        @elseif($order->order_status == 'confirmed') bg-yellow-100 text-yellow-800
                        @elseif($order->order_status == 'pending') bg-gray-100 text-gray-800
                        @elseif($order->order_status == 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($order->order_status) }}
                    </span>
                    <span class="text-lg font-bold text-gray-900">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="p-6">
            <div class="space-y-4 mb-4">
                @foreach($order->orderItems as $item)
                    @php
                            $productImage = product_image($item->product);
                    @endphp 
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    @if($item->product && $productImage)
                   
                    <img src="{{$productImage}}" 
                         alt="{{ $item->product->name ?? 'Product' }}" 
                         class="w-16 h-16 object-cover rounded-lg">
                    @else
                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fa fa-image text-gray-400"></i>
                    </div>
                    @endif
                    
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">
                            {{ $item->product->translation->name ?? $item->product->name ?? 'Product' }}
                        </h4>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <span>Quantity: {{ $item->quantity }}</span>
                            <span>Price: ₹{{ number_format($item->unit_price, 2) }}</span>
                            @if($item->meal_type)
                            <span class="capitalize">Meal: {{ $item->meal_type }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">₹{{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="pt-4 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        @if($order->expected_delivery_date)
                        <p>Delivery Slot: <span class="font-medium">{{ $order->expected_delivery_date }}</span></p>
                        @endif
                        @if($order->delivery_address)
                        <p class="mt-1">Address: <span class="font-medium">{{ Str::limit($order->delivery_address, 50) }}</span></p>
                        @endif
                    </div>
                    <div class="space-x-3">
                        <button onclick="viewOrderDetails('{{ $order->order_number }}')" 
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm">
                            View Details
                        </button>
                        @if(in_array($order->order_status, ['pending', 'confirmed']))
                        <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                            Cancel Order
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
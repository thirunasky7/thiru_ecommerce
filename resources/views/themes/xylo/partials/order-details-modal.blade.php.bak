<div class="space-y-6">
    <!-- Order Summary -->
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-semibold text-gray-900 mb-3">Order Summary</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Order Number:</span>
                <p class="font-medium">{{ $order->order_number }}</p>
            </div>
            <div>
                <span class="text-gray-600">Order Date:</span>
                <p class="font-medium">{{ $order->created_at->format('M d, Y \\a\\t h:i A') }}</p>
            </div>
            <div>
                <span class="text-gray-600">Status:</span>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                    @if($order->order_status == 'delivered') bg-green-100 text-green-800
                    @elseif($order->order_status == 'shipped') bg-blue-100 text-blue-800
                    @elseif($order->order_status == 'confirmed') bg-yellow-100 text-yellow-800
                    @elseif($order->order_status == 'pending') bg-gray-100 text-gray-800
                    @elseif($order->order_status == 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($order->order_status) }}
                </span>
            </div>
            <div>
                <span class="text-gray-600">Total Amount:</span>
                <p class="font-medium">₹{{ number_format($order->total_amount, 2) }}</p>
            </div>
            @if($order->delivery_date)
            <div>
                <span class="text-gray-600">Delivery Date:</span>
                <p class="font-medium">{{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}</p>
            </div>
            @endif
            @if($order->delivery_time_slot)
            <div>
                <span class="text-gray-600">Delivery Slot:</span>
                <p class="font-medium">{{ $order->delivery_time_slot }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Customer Information -->
    <div>
        <h4 class="font-semibold text-gray-900 mb-3">Customer Information</h4>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="font-medium">{{ $order->customer_name ?? 'Customer' }}</p>
            <p class="text-gray-600">Phone: {{ $order->customer_phone }}</p>
            <p class="text-gray-600">Email: {{ $order->customer_email ?? 'Not provided' }}</p>
        </div>
    </div>

    <!-- Delivery Address -->
    @if($order->delivery_address)
    <div>
        <h4 class="font-semibold text-gray-900 mb-3">Delivery Address</h4>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-600 whitespace-pre-line">{{ $order->delivery_address }}</p>
        </div>
    </div>
    @endif

    <!-- Order Items -->
    <div>
        <h4 class="font-semibold text-gray-900 mb-3">Order Items</h4>
        <div class="space-y-3">
            @foreach($order->orderItems as $item)
                        @php
                            $productImage = product_image($item->product);
                    @endphp 
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    @if($item->product && $productImage)
                  
                    <img src="{{$productImage}}" 
                         alt="{{ $item->product->translation->name ?? 'Product' }}" 
                         class="w-12 h-12 object-cover rounded">
                    @else
                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                        <i class="fa fa-image text-gray-400"></i>
                    </div>
                    @endif
                    <div>
                        <p class="font-medium text-gray-900">
                            {{ $item->product->translation->name ?? $item->product->name ?? 'Product' }}
                        </p>
                        <div class="text-sm text-gray-600">
                            <span>Qty: {{ $item->quantity }}</span>
                            @if($item->meal_type)
                            <span class="ml-2 capitalize">• {{ $item->meal_type }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-medium text-gray-900">₹{{ number_format($item->total_price * $item->quantity, 2) }}</p>
                    <p class="text-sm text-gray-600">₹{{ number_format($item->unit_price, 2) }} each</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Price Breakdown -->
    <div class="border-t pt-4">
        <h4 class="font-semibold text-gray-900 mb-3">Price Details</h4>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Items Total</span>
                <span>₹{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Delivery Charge</span>
                <span>₹{{ number_format($order->delivery_charge ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Tax & Charges</span>
                <span>₹{{ number_format($order->tax_amount ?? 0, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="flex justify-between">
                <span class="text-gray-600">Discount</span>
                <span>-₹{{ number_format($order->discount_amount ?? 0, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between border-t pt-2 font-semibold text-lg">
                <span>Total Amount</span>
                <span>₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="border-t pt-4">
        <h4 class="font-semibold text-gray-900 mb-3">Payment Information</h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Payment Method:</span>
                <p class="font-medium">{{ ucfirst($order->payment_method ?? 'Not specified') }}</p>
            </div>
            <div>
                <span class="text-gray-600">Payment Status:</span>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                    @if($order->payment_status == 'paid') bg-green-100 text-green-800
                    @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($order->payment_status ?? 'pending') }}
                </span>
            </div>
        </div>
    </div>
</div>
@extends('themes.xylo.partials.app')

@section('title', 'Checkout - MyStore')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"> 
<style>
    /* --- GENERAL --- */
    .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }

    /* --- PAYMENT CARDS --- */
    .payment-method {
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        background-color: #fff;
        cursor: pointer;
    }
    .payment-method:hover {
        border-color: #2563eb;
        background-color: #f9fafb;
    }
    .payment-method.selected {
        border-color: #2563eb;
        background-color: #f8fafc;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }
    .payment-icon {
        width: 2.5rem; height: 2.5rem; margin-right: 0.75rem;
    }
    .payment-details {
        display: none;
        margin-top: 0.75rem;
        padding: 0.75rem 1rem;
        background-color: #f9fafb;
        border-radius: 0.5rem;
    }
    .payment-details.active { display: block; }
    .radio-visible {
        width: 1.2rem; height: 1.2rem; accent-color: #2563eb; margin-right: 0.75rem;
    }

    /* --- ORDER SUMMARY --- */
    .order-summary {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        padding: 1.5rem;
    }
    .summary-item {
        border-bottom: 1px solid #e5e7eb;
        padding: 0.75rem 0;
    }
    .summary-item:last-child { border-bottom: none; }
</style>
@endsection

@section('content')

@php $currency = activeCurrency(); @endphp

<section class="bg-white py-4 border-b">
    <div class="container mx-auto px-4">
        <div class="breadcrumbs text-sm text-gray-600">
            <a href="{{ route('xylo.home') }}" class="hover:text-blue-600">Home</a>
            <i class="fa fa-angle-right mx-2"></i>
            <span class="text-gray-900 font-medium">Checkout</span>
        </div>
    </div>
</section>

<div class="py-6">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-6">

            <!-- Checkout Form -->
            <div class="w-full lg:w-7/12">
                <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                    @csrf

                    <!-- Shipping Info -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Shipping Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="full_name" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="First Name" value="{{ old('first_name') }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="phone" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="Phone" value="{{ old('phone') }}" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" name="address" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="Full Address" value="{{ old('address') }}">
                        </div>

                        <div class="mt-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="use_as_billing" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 text-sm">Use as billing address</span>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Payment Method</h3>

                        <!-- COD -->
                        <div class="payment-method selected" data-method="cod">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="cod" checked class="radio-visible">
                                <i class="fas fa-money-bill-wave payment-icon text-green-600"></i>
                                <div>
                                    <strong class="text-gray-900">Cash on Delivery</strong>
                                    <p class="text-gray-600 text-sm">Pay when your order arrives.</p>
                                </div>
                            </label>
                            <div class="payment-details active" id="cod-details">
                                <p class="text-gray-600 text-sm">Pay with cash once the package is delivered to you.</p>
                            </div>
                        </div>

                        <!-- UPI -->
                        <div class="payment-method" data-method="upi">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="upi" class="radio-visible">
                                <i class="fas fa-mobile-alt payment-icon text-purple-600"></i>
                                <div>
                                    <strong class="text-gray-900">UPI (Google Pay / PhonePe / Paytm)</strong>
                                    <p class="text-gray-600 text-sm">Instant secure UPI payment.</p>
                                </div>
                            </label>
                            <div class="payment-details" id="upi-details">
                                <input type="text" name="upi_id" placeholder="Enter your UPI ID" 
                                       class="w-full mt-2 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Example: yourname@okaxis</p>
                            </div>
                        </div>

                        <!-- CARD -->
                        <div class="payment-method" data-method="card">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="card" class="radio-visible">
                                <i class="fas fa-credit-card payment-icon text-blue-600"></i>
                                <div>
                                    <strong class="text-gray-900">Credit / Debit Card</strong>
                                    <p class="text-gray-600 text-sm">Pay securely with your card.</p>
                                </div>
                            </label>
                            <div class="payment-details" id="card-details">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                                    <input type="text" placeholder="Card Number" class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <input type="text" placeholder="Name on Card" class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <input type="text" placeholder="MM/YY" class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <input type="text" placeholder="CVV" class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="submitBtn"
                        class="w-full bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition flex items-center justify-center">
                        <span id="submitText">Place Order (Cash on Delivery)</span>
                        <div id="loadingSpinner" class="hidden ml-2">
                            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                        </div>
                    </button>
                </form>
            </div>

            <!-- Order Summary (on same page) -->
            <div class="w-full lg:w-5/12">
                <div class="order-summary sticky top-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h3>

                    @php $subtotal = 0; @endphp
                    @foreach($cart as $key => $item)
                        @php
                            $product = \App\Models\Product::with(['translations','thumbnail'])->find($item['product_id']);
                            $variant = \App\Models\ProductVariant::with('images')->find($item['variant_id'] ?? null);
                            $itemSubtotal = $item['price'] * $item['quantity'];
                            $subtotal += $itemSubtotal;
                        @endphp
                        <div class="summary-item flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $variant && $variant->images && $variant->images->first() ? Storage::url($variant->images->first()->image_url) : ($product && $product->thumbnail ? Storage::url($product->thumbnail->image_url) : 'https://via.placeholder.com/60x60') }}" 
                                    class="w-14 h-14 rounded object-cover">
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">{{ $product->translation->name ?? 'Product' }}</p>
                                    <p class="text-gray-600 text-xs">{{ $currency->symbol }}{{ number_format($item['price'],2) }} × {{ $item['quantity'] }}</p>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $currency->symbol }}{{ number_format($itemSubtotal, 2) }}</p>
                        </div>
                    @endforeach

                    @php
                        $coupon = session('cart_coupon');
                        $discountAmount = 0;
                        if ($coupon) {
                            $discountAmount = $coupon['type'] === 'percentage'
                                ? $subtotal * ($coupon['discount'] / 100)
                                : $coupon['discount'];
                        }
                        $total = max(0, $subtotal - $discountAmount);
                    @endphp

                    <div class="mt-4 text-sm">
                        <div class="flex justify-between mb-2"><span class="text-gray-600">Subtotal</span> <span>{{ $currency->symbol }}{{ number_format($subtotal, 2) }}</span></div>
                        @if($coupon)
                        <div class="flex justify-between mb-2"><span class="text-green-600">Discount ({{ $coupon['code'] }})</span> <span>-{{ $currency->symbol }}{{ number_format($discountAmount, 2) }}</span></div>
                        @endif
                        <div class="flex justify-between mb-2"><span class="text-gray-600">Shipping</span> <span>Calculated at checkout</span></div>
                        <hr class="my-2">
                        <div class="flex justify-between font-bold text-gray-900 text-base">
                            <span>Total</span>
                            <span>{{ $currency->symbol }}{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Coupon Apply -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <form id="applyCouponForm" class="flex gap-2">
                            @csrf
                            <input type="text" name="code" id="coupon_code" placeholder="Enter coupon code"
                                class="flex-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                            <button type="submit" class="bg-gray-800 text-white px-4 rounded-lg text-sm hover:bg-gray-900">Apply</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(document).ready(function(){
    // Payment selection
    $('.payment-method').click(function(){
        $('.payment-method').removeClass('selected');
        $(this).addClass('selected');
        $(this).find('input[type="radio"]').prop('checked', true);
        $('.payment-details').removeClass('active');
        const method = $(this).data('method');
        $(`#${method}-details`).addClass('active');
        updateButtonText(method);
    });

    function updateButtonText(method) {
        const map = { cod: 'Place Order (Cash on Delivery)', upi: 'Pay Now (UPI)', card: 'Pay Securely (Card)' };
        $('#submitText').text(map[method] || 'Place Order');
    }

    // Apply coupon
    $('#applyCouponForm').on('submit', function(e){
        e.preventDefault();
        const code = $('#coupon_code').val().trim();
        if (!code) return toastr.error('Enter coupon code');
        $.post("{{ route('cart.applyCoupon') }}", { _token: "{{ csrf_token() }}", code }, function(res){
            if (res.success) { toastr.success(res.message); setTimeout(()=>location.reload(), 800); }
            else toastr.error(res.message);
        }).fail(()=>toastr.error('Error applying coupon'));
    });
});
</script>
@endsection

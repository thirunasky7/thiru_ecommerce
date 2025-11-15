@extends('themes.xylo.partials.app')

@section('title', 'Checkout - MyStore')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"> 
<style>
    .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
    .delivery-badge {
        background: #e0f2fe;
        color: #0369a1;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .meal-type-badge {
        background: #f0fdf4;
        color: #166534;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        margin-left: 0.5rem;
    }
</style>
@endsection

@section('content')

@php $currency = activeCurrency(); @endphp

<section class="bg-white py-4 border-b">
    <div class="container mx-auto px-4">
        <div class="breadcrumbs text-sm text-gray-600">
            <a href="{{ route('xylo.home') }}" class="hover:text-blue-600">Home</a>
            <i class="fa fa-angle-right mx-2"></i>
            <a href="{{ route('cart.page') }}" class="hover:text-blue-600">Cart</a>
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

                    <!-- Customer Information -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Customer Information</h3>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="full_name" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter your full name" value="{{ old('full_name', Auth::user()->name ?? '') }}" required>
                            @error('full_name')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="tel" name="phone" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter your phone number" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                            @error('phone')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Address *</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Enter your complete delivery address" required>{{ old('address', Auth::user()->address ?? '') }}</textarea>
                            @error('address')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order Notes (Optional)</label>
                            <textarea name="notes" rows="2" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Any special instructions for delivery...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <!-- Payment Section - Only COD -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Payment Method</h3>

                        <div class="border-2 border-blue-500 rounded-lg p-4 bg-blue-50">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="cod" checked class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                                <i class="fas fa-money-bill-wave text-2xl text-green-600 ml-3 mr-4"></i>
                                <div>
                                    <strong class="text-gray-900 text-lg">Cash on Delivery</strong>
                                    <p class="text-gray-600">Pay when your order arrives. No online payment required.</p>
                                </div>
                            </label>
                            <div class="mt-3 p-3 bg-white rounded border">
                                <p class="text-sm text-gray-600">💵 Pay with cash once the package is delivered to you</p>
                                <p class="text-sm text-gray-600 mt-1">✅ No additional charges for COD</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn"
                        class="w-full bg-green-600 text-white py-4 rounded-lg font-semibold hover:bg-green-700 transition flex items-center justify-center text-lg">
                        <span id="submitText">Place Order - Cash on Delivery</span>
                        <div id="loadingSpinner" class="hidden ml-2">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-white"></div>
                        </div>
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-5/12">
                <div class="order-summary sticky top-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h3>

                    <!-- Group items by delivery date -->
                    @php
                        $groupedItems = [];
                        foreach ($cartItems as $item) {
                            $date = $item['display_order_date'];
                            if (!isset($groupedItems[$date])) {
                                $groupedItems[$date] = [];
                            }
                            $groupedItems[$date][] = $item;
                        }
                    @endphp

                    @foreach($groupedItems as $deliveryDate => $items)
                    <div class="mb-4 pb-3 border-b">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-semibold text-gray-800">Delivery: {{ $deliveryDate }}</h4>
                        </div>
                        
                        @foreach($items as $item)
                        <div class="summary-item flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3 flex-1">
                                <img src="{{ $item['image'] ?: 'https://via.placeholder.com/60x60' }}" 
                                    class="w-14 h-14 rounded object-cover border">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 text-sm">{{ $item['name'] }}</p>
                                    <p class="text-gray-600 text-xs">{{ $currency->symbol }}{{ number_format($item['price'], 2) }} × {{ $item['quantity'] }}</p>
                                    <div class="flex items-center mt-1">
                                        <span class="delivery-badge">
                                            {{ $item['meal_type'] !== 'regular' ? 'Pre-order' : 'Regular' }}
                                        </span>
                                        @if($item['meal_type'] !== 'regular')
                                        <span class="meal-type-badge capitalize">
                                            {{ $item['meal_type'] }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-900 text-sm">
                                {{ $currency->symbol }}{{ number_format($item['price'] * $item['quantity'], 2) }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                    @endforeach

                    <!-- Order Totals -->
                    <div class="mt-4 text-sm">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Subtotal</span> 
                            <span>{{ $currency->symbol }}{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Delivery Charge</span> 
                            <span class="text-green-600">FREE</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Tax</span> 
                            <span>{{ $currency->symbol }}{{ number_format($tax, 2) }}</span>
                        </div>
                        <hr class="my-2 border-gray-300">
                        <div class="flex justify-between font-bold text-gray-900 text-lg">
                            <span>Total Amount</span>
                            <span>{{ $currency->symbol }}{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <h5 class="font-semibold text-blue-900 mb-2">📦 Delivery Information</h5>
                        <p class="text-sm text-blue-800">
                            Your order contains items with different delivery dates. Each item will be delivered on its scheduled date.
                        </p>
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
    // Form submission handling
    $('#checkoutForm').on('submit', function(e){
        const submitBtn = $('#submitBtn');
        const submitText = $('#submitText');
        const spinner = $('#loadingSpinner');
        
        // Show loading state
        submitBtn.prop('disabled', true);
        submitText.text('Placing Order...');
        spinner.removeClass('hidden');
    });

    // Auto-save form data to localStorage in case of page refresh
    function saveFormData() {
        const formData = {
            full_name: $('input[name="full_name"]').val(),
            phone: $('input[name="phone"]').val(),
            address: $('textarea[name="address"]').val(),
            notes: $('textarea[name="notes"]').val()
        };
        localStorage.setItem('checkoutFormData', JSON.stringify(formData));
    }

    // Load saved form data
    function loadFormData() {
        const savedData = localStorage.getItem('checkoutFormData');
        if (savedData) {
            const formData = JSON.parse(savedData);
            $('input[name="full_name"]').val(formData.full_name || '');
            $('input[name="phone"]').val(formData.phone || '');
            $('textarea[name="address"]').val(formData.address || '');
            $('textarea[name="notes"]').val(formData.notes || '');
        }
    }

    // Auto-save on input change
    $('input, textarea').on('input', saveFormData);
    
    // Load saved data on page load
    loadFormData();

    // Clear saved data on successful form submission
    $('#checkoutForm').on('submit', function() {
        localStorage.removeItem('checkoutFormData');
    });
});
</script>
@endsection
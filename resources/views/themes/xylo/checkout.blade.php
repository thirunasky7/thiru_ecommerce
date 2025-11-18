@extends('themes.xylo.partials.app')

@section('title', 'Checkout - MyStore')

@section('content')

@php $currency = activeCurrency(); @endphp

<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"> 
<style>
    .error { 
        color: #dc2626; 
        font-size: 0.875rem; 
        margin-top: 0.25rem; 
        font-weight: 500;
    }
    
    /* Enhanced Design System */
    .checkout-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: 100vh;
    }
    
    .checkout-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .checkout-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    
    .form-input {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #f8fafc;
        width: 100%;
    }
    
    .form-input:focus {
        border-color: #3b82f6;
        background: white;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    
    .section-header {
        color: #1e293b;
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f1f5f9;
    }
    
    .section-header i {
        color: #3b82f6;
    }
    
    .delivery-badge {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .meal-type-badge {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 8px;
    }
    
    .payment-card {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border: 2px solid #0ea5e9;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
    }
    
    .payment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(14, 165, 233, 0.15);
    }
    
    .submit-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 18px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.125rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        border: none;
        width: 100%;
        cursor: pointer;
    }
    
    .submit-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, #059669, #047857);
    }
    
    .submit-btn:disabled {
        opacity: 0.7;
        transform: none;
        box-shadow: none;
        cursor: not-allowed;
    }
    
    .order-summary-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        position: sticky;
        top: 20px;
    }
    
    .product-image {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .delivery-group {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        border: 1px solid #e2e8f0;
    }
    
    .total-section {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        color: white;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .breadcrumb-item {
        color: #64748b;
        transition: color 0.3s ease;
        text-decoration: none;
    }
    
    .breadcrumb-item:hover {
        color: #3b82f6;
    }
    
    .breadcrumb-active {
        color: #1e293b;
        font-weight: 600;
    }
    
    /* Loading animation */
    .loading-spinner {
        animation: spin 1s linear infinite;
        border: 2px solid transparent;
        border-top: 2px solid white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Mobile optimizations */
    @media (max-width: 768px) {
        .checkout-container {
            padding: 0;
        }
        
        .checkout-card {
            border-radius: 0;
            margin: 0 -16px;
            box-shadow: none;
            border: none;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .order-summary-card {
            border-radius: 0;
            margin: 0 -16px;
            box-shadow: none;
            border: none;
            border-top: 1px solid #e2e8f0;
            position: static;
        }
        
        .section-header {
            font-size: 1.125rem;
            padding: 0 16px 12px 16px;
        }
        
        .form-input {
            padding: 16px;
            font-size: 16px;
            margin: 0 16px;
            width: calc(100% - 32px);
        }
        
        .payment-card {
            margin: 0 16px;
        }
        
        .submit-btn {
            margin: 0 16px;
            border-radius: 0;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
        }
        
        .delivery-group {
            margin: 0 16px 16px 16px;
        }
        
        .total-section {
            margin: 20px 16px 0 16px;
        }
    }
    
    @media (max-width: 480px) {
        .section-header {
            font-size: 1rem;
        }
        
        .submit-btn {
            padding: 16px;
            font-size: 1rem;
        }
        
        .product-image {
            width: 45px;
            height: 45px;
        }
    }
    
    /* Enhanced focus states for accessibility */
    .form-input:focus-visible {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }
    
    /* Animation for form elements */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slide-in {
        animation: slideIn 0.5s ease-out;
    }
    
    /* Success checkmark */
    .success-checkmark {
        color: #10b981;
    }
    
    /* Breadcrumb styles */
    .breadcrumbs {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .breadcrumbs i {
        font-size: 12px;
    }
</style>

<div class="checkout-container">

    <div class="container mx-auto px-4 py-20">
        <div class="flex flex-col lg:flex-row gap-6 max-w-7xl mx-auto">
            <!-- Checkout Form -->
            <div class="w-full lg:w-7/12">
                <div class="checkout-card p-6 animate-slide-in">
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                        @csrf

                        <!-- Customer Information -->
                        <div class="mb-8">
                            <div class="section-header">
                                <i class="fa fa-user-circle"></i>
                                Customer Information
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" name="full_name" 
                                           class="form-input"
                                           placeholder="Enter your full name" 
                                           value="{{ old('full_name', Auth::user()->name ?? '') }}" 
                                           required>
                                    @error('full_name')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                                    <input type="tel" name="phone" 
                                           class="form-input"
                                           placeholder="Enter your phone number" 
                                           value="{{ old('phone', Auth::user()->phone ?? '') }}" 
                                           required>
                                    @error('phone')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Delivery Address *</label>
                                    <textarea name="address" rows="3" 
                                              class="form-input"
                                              placeholder="Enter your complete delivery address with landmarks..." 
                                              required>{{ old('address', Auth::user()->address ?? '') }}</textarea>
                                    @error('address')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i class="fa fa-sticky-note text-gray-400 mr-2"></i>
                                        Order Notes (Optional)
                                    </label>
                                    <textarea name="notes" rows="2" 
                                              class="form-input"
                                              placeholder="Any special instructions for delivery, dietary preferences, or cooking instructions...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        <div class="mb-8">
                            <div class="section-header">
                                <i class="fa fa-credit-card"></i>
                                Payment Method
                            </div>

                            <div class="payment-card">
                                <label class="flex items-start cursor-pointer">
                                    <input type="radio" name="payment_method" value="cod" checked 
                                           class="w-5 h-5 text-blue-600 focus:ring-blue-500 mt-1">
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <i class="fas fa-money-bill-wave text-2xl text-green-500"></i>
                                            <div>
                                                <strong class="text-gray-900 text-lg block">Cash on Delivery</strong>
                                                <p class="text-gray-600 text-sm">Pay when your order arrives</p>
                                            </div>
                                        </div>
                                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-1">
                                                <i class="fa fa-check-circle text-green-500"></i>
                                                <span>No online payment required</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <i class="fa fa-check-circle text-green-500"></i>
                                                <span>No additional charges for COD</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submitBtn"
                                class="submit-btn flex items-center justify-center gap-3">
                            <span id="submitText">
                                <i class="fa fa-shopping-bag mr-2"></i>
                                Place Order - Cash on Delivery
                            </span>
                            <div id="loadingSpinner" class="hidden">
                                <div class="loading-spinner"></div>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-5/12">
                <div class="order-summary-card p-6 animate-slide-in" style="animation-delay: 0.1s;">
                    <div class="section-header">
                        <i class="fa fa-receipt"></i>
                        Order Summary
                    </div>

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

                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        @foreach($groupedItems as $deliveryDate => $items)
                        <div class="delivery-group">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                    <i class="fa fa-calendar-check text-blue-500"></i>
                                    {{ $deliveryDate }}
                                </h4>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ count($items) }} item{{ count($items) !== 1 ? 's' : '' }}
                                </span>
                            </div>
                            
                            @foreach($items as $item)
                            <div class="summary-item flex items-center justify-between mb-3 pb-3 border-b border-gray-200 last:border-b-0 last:mb-0 last:pb-0">
                                <div class="flex items-center space-x-3 flex-1">
                                    <img src="{{ $item['image'] ?: 'https://via.placeholder.com/60x60?text=🍕' }}" 
                                        class="product-image">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-800 text-sm leading-tight">{{ $item['name'] }}</p>
                                        <p class="text-gray-600 text-xs mt-1">{{ $currency->symbol }}{{ number_format($item['price'], 2) }} × {{ $item['quantity'] }}</p>
                                        <div class="flex items-center mt-2">
                                            <span class="delivery-badge text-xs">
                                                {{ $item['meal_type'] !== 'regular' ? 'Pre-order' : 'Regular' }}
                                            </span>
                                            @if($item['meal_type'] !== 'regular')
                                            <span class="meal-type-badge text-xs capitalize">
                                                {{ $item['meal_type'] }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <p class="font-bold text-gray-900 text-sm">
                                    {{ $currency->symbol }}{{ number_format($item['price'] * $item['quantity'], 2) }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>

                    <!-- Order Totals -->
                    <div class="total-section">
                        <h5 class="font-bold text-white text-base mb-3 flex items-center gap-2">
                            <i class="fa fa-calculator"></i>
                            Order Total
                        </h5>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-white/80 text-sm">Subtotal</span> 
                                <span class="text-white font-semibold">{{ $currency->symbol }}{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white/80 text-sm">Delivery Charge</span> 
                                <span class="text-green-300 font-semibold">FREE</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-white/80 text-sm">Tax & Charges</span> 
                                <span class="text-white font-semibold">{{ $currency->symbol }}{{ number_format($tax, 2) }}</span>
                            </div>
                            <hr class="my-3 border-white/20">
                            <div class="flex justify-between items-center">
                                <span class="text-white font-bold">Total Amount</span>
                                <span class="text-white font-bold text-lg">{{ $currency->symbol }}{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-start gap-3">
                            <i class="fa fa-info-circle text-blue-500 text-lg mt-0.5"></i>
                            <div>
                                <h5 class="font-semibold text-blue-900 mb-1 text-sm">Delivery Information</h5>
                                <p class="text-xs text-blue-800 leading-relaxed">
                                    Your order contains items with different delivery dates. Each item will be delivered fresh on its scheduled date.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="mt-4 text-center">
                        <div class="flex items-center justify-center gap-2 text-gray-500 text-xs">
                            <i class="fa fa-lock text-green-500"></i>
                            <span>Secure & Encrypted Checkout</span>
                            <i class="fa fa-shield-alt text-green-500"></i>
                        </div>
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
    // Enhanced form submission handling
    $('#checkoutForm').on('submit', function(e){
        const submitBtn = $('#submitBtn');
        const submitText = $('#submitText');
        const spinner = $('#loadingSpinner');
        
        // Show loading state with enhanced animation
        submitBtn.prop('disabled', true);
        submitText.html('<i class="fa fa-spinner fa-spin mr-2"></i>Processing Your Order...');
        spinner.removeClass('hidden');
        
        // Add loading class to form
        $(this).addClass('opacity-75');
    });

    // Enhanced auto-save form data to localStorage
    function saveFormData() {
        const formData = {
            full_name: $('input[name="full_name"]').val(),
            phone: $('input[name="phone"]').val(),
            address: $('textarea[name="address"]').val(),
            notes: $('textarea[name="notes"]').val()
        };
        localStorage.setItem('checkoutFormData', JSON.stringify(formData));
    }

    // Enhanced load saved form data
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

    // Enhanced auto-save with debounce
    let saveTimeout;
    $('input, textarea').on('input', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(saveFormData, 1000);
    });
    
    // Load saved data on page load
    loadFormData();

    // Clear saved data on successful form submission
    $('#checkoutForm').on('submit', function() {
        localStorage.removeItem('checkoutFormData');
    });

    // Add input validation with visual feedback
    $('.form-input').on('blur', function() {
        if ($(this).val().trim() === '') {
            $(this).addClass('border-red-300 bg-red-50');
        } else {
            $(this).removeClass('border-red-300 bg-red-50');
            $(this).addClass('border-green-300 bg-green-50');
            setTimeout(() => {
                $(this).removeClass('border-green-300 bg-green-50');
            }, 2000);
        }
    });

    // Enhanced phone number formatting
    $('input[name="phone"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        $(this).val(value);
    });

    // Add character counter for notes
    $('textarea[name="notes"]').on('input', function() {
        const length = $(this).val().length;
        let counter = $('#notesCounter');
        if (!counter.length) {
            counter = $('<div id="notesCounter" class="text-xs text-gray-500 mt-1 text-right"></div>');
            $(this).after(counter);
        }
        counter.text(length + '/500 characters');
        
        if (length > 400) {
            counter.addClass('text-orange-500');
        } else {
            counter.removeClass('text-orange-500');
        }
    });

    // Smooth scroll to top on mobile when focusing on inputs
    $('.form-input').on('focus', function() {
        if (window.innerWidth < 768) {
            $('html, body').animate({
                scrollTop: $(this).offset().top - 100
            }, 300);
        }
    });
});
</script>
@endsection
@extends('themes.xylo.partials.app')

@section('title', 'MyStore - Online Shopping')

@section('content')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"> 
<style>
    .error {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    .payment-method {
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .payment-method:hover {
        border-color: #3b82f6;
    }
    .payment-method.selected {
        border-color: #3b82f6;
        background-color: #f8fafc;
    }
    .payment-icon {
        width: 2.5rem;
        height: 2.5rem;
        margin-right: 0.75rem;
    }
    .payment-details {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background-color: #f8fafc;
        border-radius: 0.5rem;
    }
    .payment-details.active {
        display: block;
    }
</style>
@endsection

@php $currency = activeCurrency(); @endphp

<!-- Breadcrumb Section -->
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

                    <!-- Shipping Information -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Shipping Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror" 
                                       name="first_name" 
                                       placeholder="First Name" 
                                       value="{{ old('first_name') }}">
                                @error('first_name')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror" 
                                       name="last_name" 
                                       placeholder="Last Name" 
                                       value="{{ old('last_name') }}">
                                @error('last_name')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror" 
                                   name="address" 
                                   placeholder="Full Address" 
                                   value="{{ old('address') }}">
                            @error('address')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Suite/Floor</label>
                                <input type="text" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('suite_floor') border-red-500 @enderror" 
                                       name="suite_floor" 
                                       placeholder="Suit/Floor" 
                                       value="{{ old('suite_floor') }}">
                                @error('suite_floor')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('city') border-red-500 @enderror" 
                                       name="city" 
                                       placeholder="City" 
                                       value="{{ old('city') }}">
                                @error('city')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="use_as_billing" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700 text-sm">Use as billing address</span>
                            </label>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 mb-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror" 
                                       name="email" 
                                       placeholder="Email" 
                                       value="{{ old('email', auth()->user()->email ?? '') }}">
                                @error('email')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" 
                                       class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror" 
                                       name="phone" 
                                       placeholder="Phone" 
                                       value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Payment Method</h3>
                        
                        <!-- Cash on Delivery -->
                        <div class="payment-method selected" data-method="cod">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_method" value="cod" checked 
                                       class="hidden" {{ old('payment_method') == 'cod' ? 'checked' : '' }}>
                                <i class="fas fa-money-bill-wave payment-icon text-green-600"></i>
                                <div>
                                    <strong class="text-gray-900 text-sm sm:text-base">Pay on Delivery</strong>
                                    <p class="text-gray-600 text-xs sm:text-sm mt-1">Pay when you receive your order</p>
                                </div>
                            </label>
                            <div class="payment-details active" id="cod-details">
                                <p class="text-gray-600 text-sm">Pay with cash when your order is delivered.</p>
                                <div class="bg-blue-50 p-3 rounded-lg mt-2">
                                    <small class="text-blue-700 text-xs">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Additional cash handling fee may apply.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" 
                                class="w-full bg-red-600 text-white py-3 sm:py-4 px-6 rounded-lg font-semibold hover:bg-red-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:bg-gray-400 disabled:cursor-not-allowed flex items-center justify-center"
                                id="submitBtn">
                            <span id="submitText" class="text-sm sm:text-base">Place Order (Cash on Delivery)</span>
                            <div id="loadingSpinner" class="hidden ml-2">
                                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                            </div>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-5/12">
                <div class="bg-white rounded-lg shadow-sm p-4 sm:p-6 sticky top-4">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Order Summary</h3>

                    <!-- Cart Items -->
                    <div class="space-y-3 sm:space-y-4 mb-4 sm:mb-6" id="cart-items-container">
                        @php $subtotal = 0; @endphp
                        @foreach($cart as $key => $item)
                            @php
                                $product = \App\Models\Product::with(['translations', 'thumbnail'])->find($item['product_id']);
                                $variant = \App\Models\ProductVariant::with('images')->find($item['variant_id'] ?? null);
                                
                                $itemSubtotal = $item['price'] * $item['quantity'];
                                $subtotal += $itemSubtotal;
                            @endphp
                            <div class="flex items-center justify-between py-2 sm:py-3 border-b border-gray-200" data-cart-key="{{ $key }}">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <img src="{{ $variant && $variant->images && $variant->images->first() ? Storage::url($variant->images->first()->image_url) : ($product && $product->thumbnail ? Storage::url($product->thumbnail->image_url) : 'https://via.placeholder.com/60x60') }}" 
                                         alt="Product" 
                                         class="w-12 h-12 sm:w-16 sm:h-16 object-cover rounded-lg">
                                    <div>
                                        <h4 class="font-medium text-gray-900 text-sm sm:text-base">{{ $product->translation->name ?? 'Product' }}</h4>
                                        <p class="text-gray-600 text-xs sm:text-sm">{{ $currency->symbol }}{{ number_format($item['price'], 2) }} × {{ $item['quantity'] }}</p>
                                        <p class="text-gray-900 text-sm font-medium">Subtotal: {{ $currency->symbol }}{{ number_format($itemSubtotal, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Totals -->
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600 text-sm sm:text-base">Subtotal</span>
                            <span class="font-medium text-gray-900 text-sm sm:text-base" id="cart-subtotal">
                                {{ $currency->symbol }}{{ number_format($subtotal, 2) }}
                            </span>
                        </div>
                        
                        @php
                            $coupon = session('cart_coupon');
                            $discountAmount = 0;
                            if ($coupon) {
                                if ($coupon['type'] === 'percentage') {
                                    $discountAmount = $subtotal * ($coupon['discount'] / 100);
                                } else {
                                    $discountAmount = $coupon['discount'];
                                }
                            }
                            $total = max(0, $subtotal - $discountAmount);
                        @endphp

                        @if($coupon)
                            <div class="flex justify-between items-center py-2">
                                <div class="flex items-center">
                                    <span class="text-gray-600 text-sm sm:text-base">Discount</span>
                                    <span class="discount-badge ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">{{ $coupon['code'] }}</span>
                                </div>
                                <span class="text-green-600 font-medium text-sm sm:text-base">-{{ $currency->symbol }}{{ number_format($discountAmount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600 text-sm sm:text-base">Shipping</span>
                            <span class="text-gray-500 text-xs sm:text-sm">Calculated at checkout</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-2 border-t border-gray-200 pt-3">
                            <span class="text-base sm:text-lg font-bold text-gray-900">Total</span>
                            <span class="text-base sm:text-lg font-bold text-gray-900" id="cart-total">
                                {{ $currency->symbol }}{{ number_format($total, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Coupon Section -->
                    <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-gray-200">
                        <h5 class="font-semibold text-gray-900 mb-2 sm:mb-3 text-sm sm:text-base">Apply Coupon</h5>
                        <form id="applyCouponForm" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            @csrf
                            <input type="text" 
                                   name="code" 
                                   id="coupon_code" 
                                   placeholder="Enter coupon code" 
                                   class="flex-1 px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            <button type="submit" 
                                    class="px-4 py-2 bg-gray-800 text-white font-medium rounded-lg hover:bg-gray-900 transition-colors text-sm">
                                Apply
                            </button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        // Toastr configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Payment method selection
        $('.payment-method').click(function() {
            $('.payment-method').removeClass('selected');
            $(this).addClass('selected');
            
            $(this).find('input[type="radio"]').prop('checked', true);
            
            $('.payment-details').removeClass('active');
            const method = $(this).data('method');
            $(`#${method}-details`).addClass('active');
            
            updateSubmitButtonText(method);
        });

        function updateSubmitButtonText(method) {
            const buttonText = $('#submitText');
            switch(method) {
                case 'cod':
                    buttonText.text('Place Order (Cash on Delivery)');
                    break;
                default:
                    buttonText.text('Place Order');
            }
        }

        // Apply coupon
        $('#applyCouponForm').submit(function(e) {
            e.preventDefault();
            const code = $('#coupon_code').val().trim();
            
            if (!code) {
                toastr.error('Please enter a coupon code');
                return;
            }

            $.ajax({
                url: "{{ route('cart.applyCoupon') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    code: code
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Error applying coupon');
                }
            });
        });

        // Form validation
        $('#checkoutForm').validate({
            rules: {
                first_name: { required: true, minlength: 2 },
                last_name: { required: true, minlength: 2 },
                address: { required: true },
                email: { required: true, email: true },
                phone: { required: true, digits: true },
                payment_method: { required: true }
            },
            messages: {
                first_name: {
                    required: "Please enter your first name",
                    minlength: "First name must be at least 2 characters long"
                },
                last_name: {
                    required: "Please enter your last name",
                    minlength: "Last name must be at least 2 characters long"
                },
                email: {
                    required: "Please enter your email address",
                    email: "Please enter a valid email address"
                },
                phone: {
                    required: "Please enter your phone number",
                    digits: "Please enter only digits"
                },
                payment_method: {
                    required: "Please select a payment method"
                }
            },
            errorElement: 'div',
            errorClass: 'text-red-500 text-sm mt-1',
            highlight: function(element) {
                $(element).addClass('border-red-500').removeClass('border-gray-300');
            },
            unhighlight: function(element) {
                $(element).removeClass('border-red-500').addClass('border-gray-300');
            },
            submitHandler: function(form) {
                $('#submitBtn').prop('disabled', true);
                $('#loadingSpinner').removeClass('hidden');
                form.submit();
            }
        });

        // Show server-side validation errors
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error('{{ $error }}', 'Validation Error');
            @endforeach
        @endif

        @if(session('success'))
            toastr.success('{{ session('success') }}', 'Success');
        @endif

        @if(session('error'))
            toastr.error('{{ session('error') }}', 'Error');
        @endif
    });
</script>
@endsection
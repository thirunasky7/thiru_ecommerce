@extends('themes.xylo.partials.app')

@section('title', 'MyStore - Online Shopping')

@section('content')
@section('css')
    <style>
        .color-circle {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 5px;
            border: 1px solid #e5e7eb;
        }
        
        .color-circle.red { background-color: #ef4444; }
        .color-circle.blue { background-color: #3b82f6; }
        .color-circle.green { background-color: #10b981; }
        .color-circle.black { background-color: #000000; }
        .color-circle.white { background-color: #ffffff; }
        .color-circle.yellow { background-color: #f59e0b; }
        .color-circle.purple { background-color: #8b5cf6; }
        .color-circle.pink { background-color: #ec4899; }
        
        .size-box {
            display: inline-block;
            padding: 2px 8px;
            background-color: #f3f4f6;
            border-radius: 4px;
            font-size: 0.75rem;
            margin-right: 5px;
        }
        
        .toast-success {
            background-color: #10b981;
        }
        
        .toast-error {
            background-color: #ef4444;
        }
    </style>
    @endsection
    @php $currency = activeCurrency(); @endphp
<body class="bg-gray-50">
    <!-- Breadcrumb Section -->
    <section class="bg-white py-4 border-b">
        <div class="container mx-auto px-4">
            <div class="breadcrumbs text-sm text-gray-600">
                <a href="#" class="hover:text-blue-600">Home</a> 
                <i class="fa fa-angle-right mx-2"></i> 
                <span class="text-gray-900 font-medium">Shopping Cart</span>
            </div>
        </div>
    </section>

    <!-- Cart Section -->
    <div class="py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Cart Items Section -->
                <div class="lg:w-8/12">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2 sm:mb-0">Your Shopping Cart</h2>
                        <span class="text-gray-500 bg-gray-100 px-3 py-1 rounded-full text-sm">{{ count($cart ?? []) }} items</span>
                    </div>

                    @if(empty($cart))
                        <!-- Empty Cart State -->
                        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-700 mb-3">Your cart is empty</h3>
                            <p class="text-gray-500 mb-6">Looks like you haven't added any items to your cart yet.</p>
                            <a href="#" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                                <i class="fas fa-shopping-bag mr-2"></i>Start Shopping
                            </a>
                        </div>
                    @else
                        <!-- Cart Items -->
                        <div class="space-y-4">
                            @php $total = 0; @endphp
                            @foreach ($cart as $key => $item)
                                @php
                                    $product = \App\Models\Product::with(['translations', 'thumbnail'])->find($item['product_id']);
                                    
                                    // Safely get variant with null checks
                                    $variant = null;
                                    $variantImage = null;
                                    $variantName = null;
                                    
                                    if (isset($item['variant_id'])) {
                                        $variant = \App\Models\ProductVariant::with('images')->find($item['variant_id']);
                                    } else {
                                        $variant = \App\Models\ProductVariant::where('product_id', $item['product_id'])->where('is_primary', true)->first();
                                    }
                                    
                                    // Get image - prioritize variant image, fallback to product thumbnail
                                    if ($variant && $variant->images && $variant->images->first()) {
                                        $variantImage = Storage::url($variant->images->first()->image_url);
                                    } elseif ($product && $product->thumbnail) {
                                        $variantImage = Storage::url($product->thumbnail->image_url);
                                    } else {
                                        $variantImage = 'https://via.placeholder.com/80x80/f8f9fa/6c757d?text=Product';
                                    }
                                    
                                    // Get name - prioritize variant name, fallback to product name
                                    if ($variant && $variant->name) {
                                        $variantName = $variant->name;
                                    } elseif ($product && $product->translation) {
                                        $variantName = $product->translation->name;
                                    } else {
                                        $variantName = 'Product Not Found';
                                    }
                                    
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                @endphp

                                <div class="bg-white rounded-lg shadow-sm p-4" id="cart-row-{{ $key }}">
                                    <div class="flex flex-col md:flex-row md:items-center">
                                        <!-- Product Image & Info -->
                                        <div class="flex-1 mb-4 md:mb-0">
                                            <div class="flex items-start">
                                                <img src="{{ $variantImage }}" 
                                                     alt="{{ $variantName }}"
                                                     class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg mr-4">
                                                <div class="flex-1">
                                                    <h5 class="font-medium text-gray-900 mb-1">{{ $variantName }}</h5>
                                                    
                                                    <!-- Product Attributes -->
                                                    @if (!empty($item['attributes']))
                                                        <div class="mt-2">
                                                            @php
                                                                $sizes = [];
                                                                $colors = [];
                                                            @endphp

                                                            @foreach ($item['attributes'] as $attributeValueId)
                                                                @php
                                                                    $attributeValue = \App\Models\AttributeValue::with('attribute')->find($attributeValueId);
                                                                @endphp
                                                                @if ($attributeValue && $attributeValue->attribute)
                                                                    @php
                                                                        $attributeName = strtolower($attributeValue->attribute->name);
                                                                        if ($attributeName === 'size') {
                                                                            $sizes[] = $attributeValue->translated_value;
                                                                        } elseif ($attributeName === 'color') {
                                                                            $colors[] = $attributeValue->translated_value;
                                                                        }
                                                                    @endphp
                                                                @endif
                                                            @endforeach

                                                            @if (!empty($sizes))
                                                                <div class="mb-2">
                                                                    @foreach ($sizes as $size)
                                                                        <span class="size-box">{{ $size }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            @if (!empty($colors))
                                                                <div class="flex items-center">
                                                                    @foreach ($colors as $color)
                                                                        <span class="color-circle {{ strtolower($color) }}" 
                                                                              title="{{ $color }}"></span>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Price, Quantity and Actions -->
                                        <div class="flex items-center justify-between md:justify-end md:w-2/3">
                                            <!-- Price -->
                                            <div class="text-center md:text-left md:w-1/4">
                                                <div class="font-medium text-gray-900" data-price="{{ $item['price'] }}">
                                                    {{ $currency->symbol }}{{ number_format($item['price'], 2) }}
                                                </div>
                                            </div>

                                            <!-- Quantity Control -->
                                            <div class="mx-4 md:w-1/4">
                                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                                        <button class="quantity-btn decrease bg-gray-100 hover:bg-gray-200 px-3 py-2 transition duration-200" data-id="{{ $key }}">
                                                            <i class="fas fa-minus text-gray-600"></i>
                                                        </button>
                                                        <input type="number" 
                                                            value="{{ $item['quantity'] }}" 
                                                            min="1" 
                                                            data-id="{{ $key }}" 
                                                            data-price="{{ $item['price'] }}"
                                                            class="quantity-input w-12 text-center border-0 focus:ring-0 focus:outline-none">
                                                        <button class="quantity-btn increase bg-gray-100 hover:bg-gray-200 px-3 py-2 transition duration-200" data-id="{{ $key }}">
                                                            <i class="fas fa-plus text-gray-600"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            <!-- Subtotal & Remove -->
                                            <div class="flex items-center justify-end md:w-1/4">
                                                <div class="font-medium text-gray-900 mr-4" id="subtotal-{{ $key }}">
                                                    {{ $currency->symbol }}{{ number_format($subtotal, 2) }}
                                                </div>
                                                <button class="remove-btn remove-from-cart text-red-500 hover:text-red-700 transition duration-200" data-id="{{ $key }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Continue Shopping -->
                        <div class="mt-6">
                            <a href="{{ url('/products')}}" class="inline-flex items-center text-red-600 hover:text-red-800 font-medium transition duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                            </a>
                        </div>
                    @endif
                </div>
            @php
            if(!isset($total)){
                $total = 0;
            }
            @endphp

                <!-- Cart Summary -->
                <div class="lg:w-4/12">
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Order Summary</h4>
                        
                        <div class="flex justify-between py-3 border-b">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium" id="cart-subtotal">{{ $currency->symbol }}{{ number_format($total, 2) }}</span>
                        </div>

                        @php
                            $coupon = session('cart_coupon');
                            $discountAmount = 0;
                            if ($coupon) {
                                if ($coupon['type'] === 'percentage') {
                                    $discountAmount = $total * ($coupon['discount'] / 100);
                                } else {
                                    $discountAmount = $coupon['discount'];
                                }
                            }
                            $finalTotal = max(0, $total - $discountAmount);
                        @endphp

                        @if($coupon)
                            <div class="flex justify-between items-center py-3 border-b">
                                <div class="flex items-center">
                                    <span class="text-gray-600 mr-2">Discount</span>
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">{{ $coupon['code'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-green-600 font-medium">-{{ $currency->symbol }}{{ number_format($discountAmount, 2) }}</span>
                                    <form id="removeCouponForm" class="ml-2">
                                        @csrf
                                        <button type="submit" class="text-gray-400 hover:text-gray-600 transition duration-200">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-between py-3 border-b">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-gray-500 text-sm">Calculated at checkout</span>
                        </div>

                        <div class="flex justify-between py-3">
                            <span class="text-gray-900 font-semibold">Total</span>
                            <span class="text-gray-900 font-bold text-lg" id="cart-total">{{ $currency->symbol }}{{ number_format($finalTotal, 2) }}</span>
                        </div>

                        <div class="mt-6">
                            <a href="{{ url('/checkout')}}" class="block w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-lg text-center transition duration-200">
                                <i class="fas fa-lock mr-2"></i>Proceed to Checkout
                            </a>
                        </div>
                    </div>

                    <!-- Coupon Section -->
                    <!-- <div class="bg-white rounded-lg shadow-sm p-6">
                        <h5 class="text-lg font-semibold text-gray-900 mb-4">Apply Coupon</h5>
                        <form id="applyCouponForm">
                            @csrf
                            <div class="mb-4">
                                <input type="text" 
                                       name="code" 
                                       id="coupon_code" 
                                       placeholder="Enter coupon code" 
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200">
                            </div>
                            <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-4 rounded-lg transition duration-200">
                                <i class="fas fa-tag mr-2"></i>Apply Coupon
                            </button>
                        </form>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Quantity controls with plus/minus buttons
            $('.quantity-btn').on('click', function() {
                const itemId = $(this).data('id');
                const input = $(`.quantity-input[data-id="${itemId}"]`);
                let quantity = parseInt(input.val());
                
                if ($(this).hasClass('increase')) {
                    quantity++;
                } else if ($(this).hasClass('decrease') && quantity > 1) {
                    quantity--;
                }
                
                input.val(quantity);
                updateCartItem(itemId, quantity);
            });

            // Auto-update cart when quantity changes via input
            $('.quantity-input').on('change', function() {
                let itemId = $(this).data('id');
                let quantity = $(this).val();
                
                if (quantity < 1) {
                    quantity = 1;
                    $(this).val(1);
                }
                
                let price = parseFloat($(this).closest('.bg-white').find('.font-medium.text-gray-900').first().text().replace('{{ $currency->symbol }}', ''));
                
                // Update subtotal immediately for better UX
                let subtotal = price * quantity;
                $('#subtotal-' + itemId).text('{{ $currency->symbol }}' + subtotal.toFixed(2));
                
                // Update cart via AJAX
                updateCartItem(itemId, quantity);
            });

            function updateCartItem(itemId, quantity) {
                $.ajax({
                    url: "{{ route('cart.update') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        cart: [{
                            product_id: itemId,
                            quantity: parseInt(quantity)
                        }]
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log(response);
                            // Update totals
                             $('#cart-count').text(response.cart_count);
                            updateCartTotals(response.cart);
                            toastr.success('Cart updated successfully');
                        } else {
                            toastr.error('Failed to update cart');
                        }
                    },
                    error: function() {
                        toastr.error('Error updating cart');
                    }
                });
            }

            function updateCartTotals(cartData) {
    let subtotal = 0;
    
    // Update each row's subtotal and calculate total
    $('.quantity-input').each(function() {
        let itemId = $(this).data('id');
        let quantity = parseInt($(this).val());
        
        // Find the price element - look for the price in the same row
        let priceElement = $(this).closest('.bg-white').find('.font-medium.text-gray-900').first();
        
        // Get the price text and clean it up
        let priceText = priceElement.text().trim();
        // Extract the numeric value from the price string
        let price = parseFloat(priceText.replace('{{ $currency->symbol }}', '').replace(/,/g, ''));
      
        // If price is NaN, try to get it from the data attribute or cartData
        if (isNaN(price)) {
            // Try to get price from cartData if available
            if (cartData && cartData[itemId]) {
                price = parseFloat(cartData[itemId].price);
            } else {
                // Try to get from data attribute on the input
                price = parseFloat($(this).data('price'));
                if (isNaN(price)) {
                    price = 0;
                }
            }
        }
        
        let rowSubtotal = price * quantity;
        
        subtotal += rowSubtotal;
        
        // Update the individual item subtotal display
        $('#subtotal-' + itemId).text('{{ $currency->symbol }}' + rowSubtotal.toFixed(2));
    });
    
        
        // Update displayed totals
        $('#cart-subtotal').text('{{ $currency->symbol }}' + subtotal.toFixed(2));
        
        // Calculate discount if coupon exists
        let discountAmount = 0;
        @if($coupon)
            @if($coupon['type'] === 'percentage')
                discountAmount = subtotal * ({{ $coupon['discount'] }} / 100);
            @else
                discountAmount = {{ $coupon['discount'] }};
            @endif
        @endif
        
        let finalTotal = Math.max(0, subtotal - discountAmount);
        $('#cart-total').text('{{ $currency->symbol }}' + finalTotal.toFixed(2));
    }

            // Remove item from cart
            $('.remove-from-cart').on('click', function(e) {
                e.preventDefault();
                let itemId = $(this).data('id');
                
                // Show confirmation dialog
                if (!confirm('Are you sure you want to remove this item from your cart?')) {
                    return;
                }
                
                $.ajax({
                    url: "{{ route('cart.remove') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: itemId
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#cart-row-' + itemId).remove();
                            updateCartTotals(response.cart);
                            toastr.success(response.message);
                           
                            // Update item count in header
                            updateCartCount(response.cart);
                          
                            // Reload if cart is empty
                            // if ($('.bg-white.rounded-lg.shadow-sm.p-4').length === 0) {
                            //     setTimeout(() => {
                            //         location.reload();
                            //     }, 1500);
                            // }
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Error removing item from cart');
                    }
                });
            });

            // Update cart count in header
            function updateCartCount(cart) {
                let totalCount = Object.values(cart).reduce((sum, item) => sum + (item.quantity || 0), 0);
                const cartCountElement = $('.cart-count');
                if (cartCountElement.length) {
                    cartCountElement.text(totalCount);
                }
                
                // Update item count in cart header
                const itemCountElement = $('.cart-items-count');
                if (itemCountElement.length) {
                    itemCountElement.text(Object.keys(cart).length + ' items');
                }
            }

            // Initialize cart totals on page load
            updateCartTotals({!! json_encode($cart) !!});
        });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Apply Coupon Form
        document.getElementById("applyCouponForm")?.addEventListener("submit", function(e) {
            e.preventDefault();
            let code = document.getElementById("coupon_code").value.trim();
            
            if (!code) {
                toastr.error('Please enter a coupon code', 'Error', {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000
                });
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Applying...';
            submitBtn.disabled = true;

            fetch("{{ route('cart.applyCoupon') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(data.message, "Coupon Applied", {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 5000
                    });
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(data.message, "Invalid Coupon", {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        timeOut: 5000
                    });
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                toastr.error('Network error. Please try again.', "Error", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000
                });
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        // Remove Coupon Form
        document.getElementById("removeCouponForm")?.addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            submitBtn.disabled = true;

            fetch("{{ route('cart.removeCoupon') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                toastr.success(data.message, "Coupon Removed", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000
                });
                
                setTimeout(() => {
                    if (data.success) location.reload();
                }, 1000);
            })
            .catch(error => {
                toastr.error('Error removing coupon', "Error", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000
                });
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

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
    });
    </script>
</body>
</html>
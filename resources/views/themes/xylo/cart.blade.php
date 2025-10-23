@extends('themes.xylo.partials.app')

@section('title', 'MyStore - Online Shopping')

@section('content')
@php $currency = activeCurrency(); @endphp
    
    <!-- Breadcrumb Section -->
    <section class="banner-area inner-banner pt-5">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
                <div class="w-full">
                    <div class="breadcrumbs flex items-center space-x-2 text-sm text-gray-600">
                        <a href="{{ route('xylo.home') }}" class="hover:text-gray-900 transition-colors">Home</a> 
                        <i class="fa fa-angle-right text-xs"></i> 
                        <span class="text-gray-900">Shopping Cart</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cart Section -->
    <div class="cart-page pb-12 pt-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                <div class="w-full lg:w-8/12">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Your Shopping Cart</h2>
                        <span class="text-gray-500">{{ count($cart ?? []) }} items</span>
                    </div>

                    @if(empty($cart))
                        <!-- Empty Cart State -->
                        <div class="empty-cart text-center py-16 bg-white rounded-lg shadow-sm border border-gray-100">
                            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-700 mb-3">Your cart is empty</h3>
                            <p class="text-gray-500 mb-6 max-w-md mx-auto">Looks like you haven't added any items to your cart yet.</p>
                            <a href="{{ route('xylo.home') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-shopping-bag mr-2"></i>Start Shopping
                            </a>
                        </div>
                    @else
                        <!-- Cart Items -->
                        <div class="cart-items space-y-4">
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

                                <div class="cart-item bg-white rounded-lg shadow-sm border border-gray-100 p-4" id="cart-row-{{ $key }}">
                                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                                        <!-- Product Image & Info -->
                                        <div class="md:w-6/12">
                                            <div class="flex items-start space-x-4">
                                                <img src="{{ $variantImage }}" 
                                                     alt="{{ $variantName }}"
                                                     class="w-20 h-20 object-cover rounded-md flex-shrink-0">
                                                <div class="product-info">
                                                    <h5 class="font-medium text-gray-900 mb-1">{{ $variantName }}</h5>
                                                    
                                                    <!-- Product Attributes -->
                                                    @if (!empty($item['attributes']))
                                                        <div class="product-attributes">
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
                                                                <div class="mb-1 flex flex-wrap gap-1">
                                                                    @foreach ($sizes as $size)
                                                                        <span class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">{{ $size }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            @if (!empty($colors))
                                                                <div class="flex items-center space-x-1">
                                                                    @foreach ($colors as $color)
                                                                        <span class="inline-block w-4 h-4 rounded-full border border-gray-300 bg-{{ strtolower($color) }}-500" 
                                                                              title="{{ $color }}"></span>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Price -->
                                        <div class="md:w-2/12 text-center md:text-left">
                                            <div class="price-amount font-medium text-gray-900">
                                                {{ $currency->symbol }}{{ number_format($item['price'], 2) }}
                                            </div>
                                        </div>

                                        <!-- Quantity Control -->
                                        <div class="md:w-2/12">
                                            <div class="quantity-control flex items-center justify-center md:justify-start">
                                                <button class="quantity-btn decrease w-8 h-8 flex items-center justify-center bg-gray-100 rounded-l-md hover:bg-gray-200 transition-colors" data-id="{{ $key }}">
                                                    <i class="fas fa-minus text-xs"></i>
                                                </button>
                                                <input type="number" 
                                                       value="{{ $item['quantity'] }}" 
                                                       min="1" 
                                                       data-id="{{ $key }}" 
                                                       class="quantity-input w-12 h-8 text-center border-y border-gray-200 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                <button class="quantity-btn increase w-8 h-8 flex items-center justify-center bg-gray-100 rounded-r-md hover:bg-gray-200 transition-colors" data-id="{{ $key }}">
                                                    <i class="fas fa-plus text-xs"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Subtotal & Remove -->
                                        <div class="md:w-2/12">
                                            <div class="flex items-center justify-between">
                                                <div class="price-amount font-medium text-gray-900" id="subtotal-{{ $key }}">
                                                    {{ $currency->symbol }}{{ number_format($subtotal, 2) }}
                                                </div>
                                                <button class="remove-btn remove-from-cart w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-md transition-colors" data-id="{{ $key }}">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Continue Shopping -->
                        <div class="flex justify-between items-center mt-8">
                            <a href="{{ route('xylo.home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Cart Summary -->
                <div class="w-full lg:w-4/12 mt-6 lg:mt-0">
                    <div class="cart-summary bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h4>
                        
                        <div class="summary-row flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600">Subtotal</span>
                            <span id="cart-subtotal" class="font-medium text-gray-900">
                                @if(isset($total))
                                {{ $currency->symbol }}{{ number_format($total, 2) }}
                                @else
                                {{ $currency->symbol }} 0.00
                                @endif
                            </span>
                        </div>

                        @php
                          if(!isset($total)){
                                $total = 0;
                          }
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
                            <div class="summary-row flex justify-between items-center py-3 border-b border-gray-100">
                                <div class="flex items-center">
                                    <span class="text-gray-600">Discount</span>
                                    <span class="discount-badge ml-2 px-2 py-1 bg-green-100 text-green-800 text-xs rounded">{{ $coupon['code'] }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-green-600 font-medium">-{{ $currency->symbol }}{{ number_format($discountAmount, 2) }}</span>
                                    <form id="removeCouponForm" class="ml-2">
                                        @csrf
                                        <button type="submit" class="remove-coupon w-6 h-6 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <div class="summary-row flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600">Shipping</span>
                            <span class="text-gray-500 text-sm">Calculated at checkout</span>
                        </div>

                        <div class="summary-row flex justify-between items-center py-3">
                            <span class="text-gray-600 font-medium">Total</span>
                            <span id="cart-total" class="text-lg font-bold text-gray-900">{{ $currency->symbol }}{{ number_format($finalTotal, 2) }}</span>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('checkout.index') }}" class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-lock mr-2"></i>Proceed to Checkout
                            </a>
                        </div>
                    </div>

                    <!-- Coupon Section -->
                    <div class="coupon-section mt-6 bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h5 class="text-lg font-semibold text-gray-900 mb-3">Apply Coupon</h5>
                        <form id="applyCouponForm">
                            @csrf
                            <div class="mb-4">
                                <input type="text" 
                                       name="code" 
                                       id="coupon_code" 
                                       placeholder="Enter coupon code" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-gray-800 text-white font-medium rounded-md hover:bg-gray-900 transition-colors">
                                <i class="fas fa-tag mr-2"></i>Apply Coupon
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
            
            let price = parseFloat($(this).closest('.cart-item').find('.price-amount').first().text().replace('{{ $currency->symbol }}', ''));
            
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
                        // Update totals
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
                let quantity = $(this).val();
                let price = parseFloat($(this).closest('.cart-item').find('.price-amount').first().text().replace('{{ $currency->symbol }}', ''));
                let rowSubtotal = price * quantity;
                
                subtotal += rowSubtotal;
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
                        if ($('.cart-item').length === 0) {
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        }
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
@endsection
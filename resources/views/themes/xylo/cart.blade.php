@extends('themes.xylo.layouts.master')
@section('css')

@endsection
@section('content')
 @php $currency = activeCurrency(); @endphp
    
    <!-- Breadcrumb Section -->
    <section class="banner-area inner-banner pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumbs">
                        <a href="{{ route('xylo.home') }}">Home</a> 
                        <i class="fa fa-angle-right"></i> 
                        <span>Shopping Cart</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cart Section -->
    <div class="cart-page pb-5 pt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">Your Shopping Cart</h2>
                        <span class="text-muted">{{ count($cart ?? []) }} items</span>
                    </div>

                    @if(empty($cart))
                        <!-- Empty Cart State -->
                        <div class="empty-cart">
                            <i class="fas fa-shopping-cart"></i>
                            <h3 class="mb-3">Your cart is empty</h3>
                            <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                            <a href="{{ route('xylo.home') }}" class="btn-proceed-checkout" style="display: inline-block; width: auto;">
                                <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                            </a>
                        </div>
                    @else
                        <!-- Cart Items -->
                        <div class="cart-items">
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

                                <div class="cart-item" id="cart-row-{{ $key }}">
                                    <div class="row align-items-center">
                                        <!-- Product Image & Info -->
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $variantImage }}" 
                                                     alt="{{ $variantName }}"
                                                     class="product-image me-3">
                                                <div class="product-info">
                                                    <h5 class="mb-1">{{ $variantName }}</h5>
                                                    
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
                                                                <div class="mb-1">
                                                                    @foreach ($sizes as $size)
                                                                        <span class="size-box">{{ $size }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            @if (!empty($colors))
                                                                <div>
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

                                        <!-- Price -->
                                        <div class="col-md-2 text-center text-md-start">
                                            <div class="price-amount">
                                                {{ $currency->symbol }}{{ number_format($item['price'], 2) }}
                                            </div>
                                        </div>

                                        <!-- Quantity Control -->
                                        <div class="col-md-2">
                                            <div class="quantity-control">
                                                <button class="quantity-btn decrease" data-id="{{ $key }}">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" 
                                                       value="{{ $item['quantity'] }}" 
                                                       min="1" 
                                                       data-id="{{ $key }}" 
                                                       class="quantity-input">
                                                <button class="quantity-btn increase" data-id="{{ $key }}">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Subtotal & Remove -->
                                        <div class="col-md-2">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="price-amount" id="subtotal-{{ $key }}">
                                                    {{ $currency->symbol }}{{ number_format($subtotal, 2) }}
                                                </div>
                                                <button class="remove-btn remove-from-cart" data-id="{{ $key }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Continue Shopping -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('xylo.home') }}" class="btn-continue-shopping">
                                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="cart-summary">
                        <h4 class="summary-title">Order Summary</h4>
                        
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span id="cart-subtotal">{{ $currency->symbol }}{{ number_format($total, 2) }}</span>
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
                            <div class="summary-row">
                                <div class="d-flex align-items-center">
                                    <span>Discount</span>
                                    <span class="discount-badge ms-2">{{ $coupon['code'] }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="text-success">-{{ $currency->symbol }}{{ number_format($discountAmount, 2) }}</span>
                                    <form id="removeCouponForm" class="ms-2">
                                        @csrf
                                        <button type="submit" class="remove-coupon">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <div class="summary-row">
                            <span>Shipping</span>
                            <span class="text-muted">Calculated at checkout</span>
                        </div>

                        <div class="summary-row">
                            <span>Total</span>
                            <span id="cart-total">{{ $currency->symbol }}{{ number_format($finalTotal, 2) }}</span>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('checkout.index') }}" class="btn-proceed-checkout">
                                <i class="fas fa-lock me-2"></i>Proceed to Checkout
                            </a>
                        </div>
                    </div>

                    <!-- Coupon Section -->
                    <div class="coupon-section">
                        <h5 class="summary-title mb-3">Apply Coupon</h5>
                        <form id="applyCouponForm">
                            @csrf
                            <div class="mb-3">
                                <input type="text" 
                                       name="code" 
                                       id="coupon_code" 
                                       placeholder="Enter coupon code" 
                                       class="form-control">
                            </div>
                            <button type="submit" class="btn-apply-coupon">
                                <i class="fas fa-tag me-2"></i>Apply Coupon
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
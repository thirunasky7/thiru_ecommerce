@extends('themes.xylo.partials.app')

@section('title', 'My Cart')

@section('content')
<style>
    /* Custom styles for enhanced cart UI */
    .cart-item-card {
        transition: all 0.3s ease;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .cart-item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .quantity-btn {
        transition: all 0.2s ease;
        border: 2px solid #e5e7eb;
    }
    
    .quantity-btn:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }
    
    .quantity-btn:active {
        transform: scale(0.95);
    }
    
    .remove-btn {
        transition: all 0.3s ease;
        opacity: 0.7;
    }
    
    .remove-btn:hover {
        opacity: 1;
        transform: scale(1.1);
    }
    
    .meal-badge {
        font-size: 0.7rem;
        padding: 4px 8px;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .delivery-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 16px 20px;
        border-radius: 12px 12px 0 0;
    }
    
    .checkout-btn {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        transition: all 0.3s ease;
        border-radius: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    
    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 87, 108, 0.3);
    }
    
    .checkout-btn:active {
        transform: translateY(0);
    }
    
    .empty-cart-icon {
        font-size: 4rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
    }
    
    .product-image {
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid #f8fafc;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .sticky-header {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }
    
    .total-section {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.98);
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Loading animation */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .loading {
        animation: pulse 1.5s ease-in-out infinite;
    }
    
    /* Mobile optimizations */
    @media (max-width: 640px) {
        .cart-item-card {
            margin: 8px 0;
            border-radius: 12px;
        }
        
        .product-image {
            width: 80px;
            height: 80px;
        }
        
        .quantity-btn {
            width: 32px;
            height: 32px;
        }
        
        .qty-input {
            width: 40px;
        }
        
        .delivery-header {
            padding: 12px 16px;
            border-radius: 8px 8px 0 0;
        }
    }
    
    @media (max-width: 480px) {
        .cart-item-card {
            padding: 12px;
        }
        
        .product-image {
            width: 70px;
            height: 70px;
        }
        
        .meal-badge {
            font-size: 0.65rem;
            padding: 3px 6px;
        }
    }
    
    /* Smooth transitions for quantity changes */
    .quantity-change {
        transition: all 0.3s ease;
    }
    
    /* Custom scrollbar for cart items */
    .cart-items-container {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e0 #f7fafc;
    }
    
    .cart-items-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .cart-items-container::-webkit-scrollbar-track {
        background: #f7fafc;
        border-radius: 3px;
    }
    
    .cart-items-container::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }
    
    .cart-items-container::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }
    
    /* Price highlight */
    .price-highlight {
        color: #059669;
        font-weight: 700;
    }
    
    .subtotal-highlight {
        color: #dc2626;
        font-weight: 700;
    }
    
    /* Delivery date highlight */
    .delivery-date {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 pb-32">
    <!-- Enhanced Header -->
    <div class="sticky top-0 sticky-header shadow-sm z-40 border-b border-gray-200">
        <div class="px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-900 transition-colors p-2 rounded-full hover:bg-gray-100">
                    <i class="fa fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">My Cart</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ count($cartItems) }} item{{ count($cartItems) !== 1 ? 's' : '' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-semibold">
                    <i class="fa fa-shopping-cart mr-1"></i>
                    <span id="cart-count-header">{{ count($cartItems) }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(count($cartItems) > 0)
    <!-- Enhanced Cart Items Container -->
    <div class="px-4 sm:px-6 mt-6 space-y-6 pb-6" id="cart-items-container">
        @php $grandTotal = 0; @endphp

        @foreach($groupedCartItems as $deliveryDate => $items)
        <div class="cart-item-card bg-white shadow-lg border-0 overflow-hidden">
            <!-- Enhanced Delivery Date Header -->
            <div class="delivery-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa fa-calendar-check text-white text-lg"></i>
                        <div>
                            <h3 class="font-bold text-white text-sm sm:text-base">
                                Delivery Schedule
                            </h3>
                            @php
                                $carbonDate = \Carbon\Carbon::parse($deliveryDate);
                                $today = \Carbon\Carbon::today();
                                if ($carbonDate->isToday()) {
                                    $displayDate = 'Today';
                                    $dateClass = 'text-green-300';
                                } elseif ($carbonDate->isTomorrow()) {
                                    $displayDate = 'Tomorrow';
                                    $dateClass = 'text-blue-300';
                                } else {
                                    $displayDate = $carbonDate->format('D, M j');
                                    $dateClass = 'text-yellow-300';
                                }
                            @endphp
                            <p class="text-white/90 text-sm mt-1">
                                <span class="{{ $dateClass }} font-semibold">{{ $displayDate }}</span>
                                • {{ $carbonDate->format('F j, Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="bg-white/20 px-3 py-1 rounded-full">
                        <span class="text-white text-sm font-semibold">{{ count($items) }} item{{ count($items) !== 1 ? 's' : '' }}</span>
                    </div>
                </div>
            </div>

            <!-- Enhanced Items List -->
            <div class="divide-y divide-gray-100">
                @foreach($items as $item)
                @php 
                    $itemSubtotal = $item['price'] * $item['quantity'];
                    $grandTotal += $itemSubtotal;
                @endphp

                <div id="cart-item-{{ $item['cart_item_id'] }}" class="p-4 sm:p-6 flex gap-4 items-start quantity-change">
                    <!-- Enhanced Product Image -->
                    <div class="flex-shrink-0 relative">
                        @if($item['image'])
                            <img src="{{ $item['image'] }}" 
                                 class="product-image w-20 h-20 sm:w-24 sm:h-24">
                        @else
                            <div class="product-image w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                <i class="fa fa-image text-gray-500 text-xl"></i>
                            </div>
                        @endif
                        <!-- Quantity Badge -->
                        <div class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold shadow-lg">
                            {{ $item['quantity'] }}
                        </div>
                    </div>

                    <!-- Enhanced Product Details -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-gray-900 text-base sm:text-lg truncate">{{ $item['name'] }}</h3>
                                
                                <!-- Enhanced Meal Type Badge -->
                                @if($item['meal_type'] !== 'regular')
                                <span class="inline-block meal-badge bg-blue-100 text-blue-800 mt-2">
                                    <i class="fa fa-utensils mr-1"></i>
                                    {{ ucfirst($item['meal_type']) }}
                                </span>
                                @else
                                <span class="inline-block meal-badge bg-green-100 text-green-800 mt-2">
                                    <i class="fa fa-bolt mr-1"></i>
                                    Regular
                                </span>
                                @endif
                            </div>
                            
                            <!-- Enhanced Remove Button -->
                            <button onclick="removeItem('{{ $item['cart_item_id'] }}')" 
                                    class="remove-btn text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50 transition-all">
                                <i class="fa fa-trash-alt text-sm"></i>
                            </button>
                        </div>

                        <!-- Price -->
                        <p class="text-sm text-gray-600 mt-3">
                            Unit Price: <span class="price-highlight">₹{{ number_format($item['price'], 2) }}</span>
                        </p>

                        <!-- Enhanced Quantity Controls -->
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600 font-medium">Qty:</span>
                                <div class="flex items-center gap-1 bg-gray-50 rounded-xl p-1">
                                    <button class="qty-minus quantity-btn w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full font-bold text-gray-600 hover:text-gray-900" 
                                            data-cart-item-id="{{ $item['cart_item_id'] }}">
                                        <i class="fa fa-minus text-xs"></i>
                                    </button>

                                    <input type="number" 
                                           class="qty-input w-12 text-center border-0 bg-transparent font-semibold text-gray-900"
                                           id="qty-{{ $item['cart_item_id'] }}"
                                           value="{{ $item['quantity'] }}"
                                           min="1"
                                           readonly>

                                    <button class="qty-plus quantity-btn w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center rounded-full font-bold text-gray-600 hover:text-gray-900" 
                                            data-cart-item-id="{{ $item['cart_item_id'] }}">
                                        <i class="fa fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Enhanced Subtotal -->
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Subtotal:</p>
                                <p class="text-lg font-bold subtotal-highlight">
                                    ₹<span id="subtotal-{{ $item['cart_item_id'] }}">{{ number_format($itemSubtotal, 2) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    @else
    <!-- Enhanced Empty Cart -->
    <div class="flex flex-col items-center justify-center min-h-[60vh] px-4 text-center">
        <div class="empty-cart-icon">
            <i class="fa fa-shopping-cart"></i>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
        <p class="text-gray-600 text-lg mb-8 max-w-md">
            Looks like you haven't added anything to your cart yet. Let's find something delicious!
        </p>
        <a href="{{ url('/') }}" 
           class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
            <i class="fa fa-utensils"></i>
            Start Shopping
        </a>
    </div>
    @endif

    @if(count($cartItems) > 0)
    <!-- Enhanced TOTAL Section Fixed Bottom -->
    <div class="fixed bottom-0 left-0 right-0 total-section shadow-2xl border-t p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Total Amount -->
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-gray-600 text-sm">Total Amount</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">
                        ₹<span id="cart-total">{{ number_format($grandTotal, 2) }}</span>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600 text-sm">{{ count($cartItems) }} item{{ count($cartItems) !== 1 ? 's' : '' }}</p>
                    <p class="text-sm text-green-600 font-semibold">Free Delivery</p>
                </div>
            </div>

            <!-- Checkout Button -->
            <a href="{{ url('/checkout')}}" 
               class="block checkout-btn text-white text-center py-4 rounded-xl font-bold text-lg shadow-lg w-full">
                <div class="flex items-center justify-center gap-2">
                    <i class="fa fa-lock"></i>
                    Proceed to Checkout
                    <i class="fa fa-arrow-right ml-1"></i>
                </div>
            </a>
            
            <!-- Continue Shopping Link -->
            <div class="text-center mt-3">
                <a href="{{ url('/pre-order') }}" class="text-gray-600 hover:text-gray-900 text-sm transition-colors">
                    <i class="fa fa-arrow-left mr-1"></i>
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- jQuery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
// -----------------------------------------
// Enhanced Quantity Increase
// -----------------------------------------
$(document).on("click", ".qty-plus", function () {
    let cartItemId = $(this).data("cart-item-id");
    let input = $("#qty-" + cartItemId);
    let itemElement = $("#cart-item-" + cartItemId);

    // Add loading effect
    itemElement.addClass('loading');
    
    let qty = parseInt(input.val()) + 1;
    input.val(qty);

    // Update quantity badge
    updateQuantityBadge(cartItemId, qty);
    
    updateQuantity(cartItemId, qty);
    
    // Remove loading effect after animation
    setTimeout(() => {
        itemElement.removeClass('loading');
    }, 500);
});

// -----------------------------------------
// Enhanced Quantity Decrease
// -----------------------------------------
$(document).on("click", ".qty-minus", function () {
    let cartItemId = $(this).data("cart-item-id");
    let input = $("#qty-" + cartItemId);
    let itemElement = $("#cart-item-" + cartItemId);

    // Add loading effect
    itemElement.addClass('loading');
    
    let qty = parseInt(input.val()) - 1;
    
    if(qty == 0){
        // Show confirmation before removing
        if(confirm('Remove this item from cart?')) {
            removeItem(cartItemId);
        }
        itemElement.removeClass('loading');
        return;
    }

    input.val(qty);

    // Update quantity badge
    updateQuantityBadge(cartItemId, qty);
    
    updateQuantity(cartItemId, qty);
    
    // Remove loading effect after animation
    setTimeout(() => {
        itemElement.removeClass('loading');
    }, 500);
});

// -----------------------------------------
// Update quantity badge
// -----------------------------------------
function updateQuantityBadge(cartItemId, qty) {
    const badge = $(`#cart-item-${cartItemId} .absolute.bg-red-500`);
    if (badge.length) {
        badge.text(qty);
        
        // Add bounce animation
        badge.addClass('animate-bounce');
        setTimeout(() => {
            badge.removeClass('animate-bounce');
        }, 300);
    }
}

// -----------------------------------------
// Enhanced Ajax Update Quantity
// -----------------------------------------
function updateQuantity(cartItemId, qty) {
    $.ajax({
        url: "{{ route('cart.update-quantity') }}",
        method: "POST",
        data: {
            cart_item_id: cartItemId,
            quantity: qty,
            _token: "{{ csrf_token() }}"
        },
        success: function (res) {
            if (res.status) {
                // Update Subtotal with animation
                const subtotalElement = $("#subtotal-" + cartItemId);
                subtotalElement.addClass('text-green-600');
                subtotalElement.text(res.item_subtotal.toFixed(2));
                
                setTimeout(() => {
                    subtotalElement.removeClass('text-green-600');
                }, 1000);

                // Update Grand Total with animation
                const totalElement = $("#cart-total");
                totalElement.addClass('text-green-600');
                totalElement.text(res.cart_total.toFixed(2));
                
                setTimeout(() => {
                    totalElement.removeClass('text-green-600');
                }, 1000);

                // Update cart count
                cartupdateCartCount(res.cart_count);
                
                // Update header count
                $("#cart-count-header").text(res.cart_count);
            }
        },
        error: function(xhr) {
            console.error('Error updating quantity:', xhr);
            showNotification('Error updating quantity. Please try again.', 'error');
        }
    });
}

// -----------------------------------------
// Enhanced Remove Item
// -----------------------------------------
function removeItem(cartItemId) {
    const itemElement = $("#cart-item-" + cartItemId);
    
    // Add removal animation
    itemElement.addClass('opacity-50 scale-95');

    $.ajax({
        url: "{{ route('cart.remove', '') }}/" + cartItemId,
        method: "DELETE",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function (res) {
            if (res.status) {
                // Enhanced removal animation
                itemElement.slideUp(300, function() {
                    $(this).remove();
                    
                    // Update Grand Total
                    $("#cart-total").text(res.cart_total.toFixed(2));

                    // Update Cart Count
                    cartupdateCartCount(res.cart_count);
                    $("#cart-count-header").text(res.cart_count);

                    // If cart is empty, reload page to show empty state
                    if (res.cart_count == 0) {
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        // Remove empty delivery date groups
                        checkEmptyDeliveryGroups();
                    }
                });
            }
        },
        error: function(xhr) {
            console.error('Error removing item:', xhr);
            itemElement.removeClass('opacity-50 scale-95');
            showNotification('Error removing item. Please try again.', 'error');
        }
    });
}

// -----------------------------------------
// Enhanced Cart Count Update
// -----------------------------------------
function cartupdateCartCount(count) {
    // Update all cart count elements with animation
    $('.cart-count, #cart-count').text(count);
    
    // Add bounce animation
    $('#cart-count-header').addClass('animate-bounce');
    setTimeout(() => {
        $('#cart-count-header').removeClass('animate-bounce');
    }, 300);
    
    // Show/hide badge based on count
    if (count > 0) {
        $('.cart-count, #cart-count').show();
    } else {
        $('.cart-count, #cart-count').hide();
    }
}

// -----------------------------------------
// Check for empty delivery groups and remove them
// -----------------------------------------
function checkEmptyDeliveryGroups() {
    $('.cart-item-card').each(function() {
        const $group = $(this);
        const itemsCount = $group.find('.p-4.sm\\:p-6.flex.gap-4').length;
        
        if (itemsCount === 0) {
            $group.slideUp(300, function() {
                $(this).remove();
            });
        }
    });
}

// -----------------------------------------
// Enhanced Notification System
// -----------------------------------------
function showNotification(message, type = 'info') {
    const notification = $(`
        <div class="fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full max-w-sm ${
            type === 'error' ? 'bg-red-500 text-white' : 'bg-green-500 text-white'
        }">
            <div class="flex items-center gap-3">
                <i class="fa ${type === 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle'}"></i>
                <span>${message}</span>
            </div>
        </div>
    `);
    
    $('body').append(notification);
    
    // Animate in
    setTimeout(() => {
        notification.removeClass('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.addClass('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// -----------------------------------------
// Initialize page with enhanced features
// -----------------------------------------
$(document).ready(function() {
    // Add smooth animations to cart items
    $('.cart-item-card').hide().fadeIn(500);
    
    // Update header cart count
    const initialCount = {{ count($cartItems) }};
    $("#cart-count-header").text(initialCount);
    
    // Add hover effects
    $('.cart-item-card').hover(
        function() {
            $(this).css('transform', 'translateY(-2px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );
});
</script>

@endsection
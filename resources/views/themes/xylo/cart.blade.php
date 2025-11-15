@extends('themes.xylo.partials.app')

@section('title', 'My Cart')

@section('content')

<div class="min-h-screen bg-gray-50 pb-28">

    <!-- Header -->
    <div class="sticky top-0 bg-white shadow-sm z-40">
        <div class="px-4 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold text-gray-900">My Cart</h1>
            <a href="{{ url()->previous() }}" class="text-gray-500">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>
    </div>

    @if(count($cartItems) > 0)
    <!-- Grouped by Delivery Date -->
    <div class="px-4 mt-4 space-y-6" id="cart-items-container">
        @php $grandTotal = 0; @endphp

        @foreach($groupedCartItems as $deliveryDate => $items)
        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <!-- Delivery Date Header -->
            <div class="bg-gray-50 px-4 py-3 border-b">
                <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fa fa-calendar text-indigo-600"></i>
                    Delivery: 
                    @php
                        $carbonDate = \Carbon\Carbon::parse($deliveryDate);
                        $today = \Carbon\Carbon::today();
                        if ($carbonDate->isToday()) {
                            $displayDate = 'Today';
                        } elseif ($carbonDate->isTomorrow()) {
                            $displayDate = 'Tomorrow';
                        } else {
                            $displayDate = $carbonDate->format('D, M j');
                        }
                    @endphp
                    <span class="text-indigo-600">{{ $displayDate }}</span>
                </h3>
            </div>

            <!-- Items for this delivery date -->
            <div class="divide-y">
                @foreach($items as $item)
                @php 
                    $itemSubtotal = $item['price'] * $item['quantity'];
                    $grandTotal += $itemSubtotal;
                @endphp

                <div id="cart-item-{{ $item['cart_item_id'] }}" class="p-4 flex gap-3">
                    <!-- Product Image -->
                    <div class="flex-shrink-0">
                        @if($item['image'])
                            <img src="{{ $item['image'] }}" 
                                 class="w-20 h-20 rounded-xl object-cover border">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-gray-200 flex items-center justify-center border">
                                <i class="fa fa-image text-gray-400"></i>
                            </div>
                        @endif
                       
                    </div>

                    <!-- Product Details -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $item['name'] }}</h3>
                        
                        <!-- Meal Type Badge -->
                        @if($item['meal_type'] !== 'regular')
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mt-1">
                            {{ ucfirst($item['meal_type']) }}
                        </span>
                        @else
                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full mt-1">
                            Regular
                        </span>
                        @endif

                        <p class="text-sm text-gray-600 mt-1">₹{{ $item['price'] }}</p>

                        <!-- Quantity Controls -->
                        <div class="quantity-box flex items-center gap-2 mt-2">
                            <button class="qty-minus bg-gray-200 w-8 h-8 flex items-center justify-center rounded-full" 
                                    data-cart-item-id="{{ $item['cart_item_id'] }}">−</button>

                            <input type="number" 
                                   class="qty-input w-12 text-center border rounded"
                                   id="qty-{{ $item['cart_item_id'] }}"
                                   value="{{ $item['quantity'] }}"
                                   min="1"
                                   readonly>

                            <button class="qty-plus bg-gray-200 w-8 h-8 flex items-center justify-center rounded-full" 
                                    data-cart-item-id="{{ $item['cart_item_id'] }}">+</button>
                        </div>

                        <!-- Subtotal -->
                        <p class="text-sm font-semibold text-gray-700 mt-2">
                            Subtotal: ₹<span id="subtotal-{{ $item['cart_item_id'] }}">{{ $itemSubtotal }}</span>
                        </p>
                    </div>

                    <!-- Remove Button -->
                    <button onclick="removeItem('{{ $item['cart_item_id'] }}')" 
                            class="text-red-600 text-lg self-start">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    @else
    <!-- Empty Cart -->
    <div class="text-center py-16 px-4">
        <i class="fa fa-shopping-cart text-gray-400 text-5xl"></i>
        <p class="text-gray-600 mt-2 text-lg">Your cart is empty</p>
        <a href="{{ route('menu') }}" class="inline-block mt-4 bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold">
            Start Shopping
        </a>
    </div>
    @endif

    @if(count($cartItems) > 0)
    <!-- TOTAL Section Fixed Bottom -->
    <div class="fixed bottom-0 left-0 right-0 bg-white shadow-lg border-t p-4">

        <div class="flex justify-between mb-2">
            <p class="font-semibold text-gray-700">Total:</p>
            <p class="text-xl font-bold text-gray-900">
                ₹<span id="cart-total">{{ $grandTotal }}</span>
            </p>
        </div>

        <a href="{{ url('/checkout')}}" class="block bg-gradient-to-r from-orange-500 to-red-500 text-white text-center py-3 rounded-xl font-bold text-lg shadow">
            Proceed to Checkout
        </a>
    </div>
    @endif

</div>

<!-- jQuery CDN -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
// -----------------------------------------
// Increase Quantity
// -----------------------------------------
$(document).on("click", ".qty-plus", function () {
    let cartItemId = $(this).data("cart-item-id");
    let input = $("#qty-" + cartItemId);

    let qty = parseInt(input.val()) + 1;
    input.val(qty);

    updateQuantity(cartItemId, qty);
});

// -----------------------------------------
// Decrease Quantity
// -----------------------------------------
$(document).on("click", ".qty-minus", function () {
    let cartItemId = $(this).data("cart-item-id");
    let input = $("#qty-" + cartItemId);

    let qty = parseInt(input.val()) - 1;
    //if (qty < 1) qty = 1;

    input.val(qty);

    updateQuantity(cartItemId, qty);
    if(qty ==0){
        removeItem(cartItemId);
    }
});

// -----------------------------------------
// Ajax Update Quantity
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
                // Update Subtotal
                $("#subtotal-" + cartItemId).text(res.item_subtotal);

                // Update Grand Total
                $("#cart-total").text(res.cart_total);

                cartupdateCartCount(res.cart_count);
            }
        },
        error: function(xhr) {
            console.error('Error updating quantity:', xhr);
            alert('Error updating quantity. Please try again.');
        }
    });
}

// -----------------------------------------
// Remove Item
// -----------------------------------------
function removeItem(cartItemId) {
    

    $.ajax({
        url: "{{ route('cart.remove', '') }}/" + cartItemId,
        method: "DELETE",
        data: {
            _token: "{{ csrf_token() }}"
        },
        success: function (res) {
            if (res.status) {
                // Remove item from UI
                $("#cart-item-" + cartItemId).remove();

                // Update Grand Total
                $("#cart-total").text(res.cart_total);

                // Update Cart Count Badge
                cartupdateCartCount(res.cart_count);

                // If cart is empty, reload page to show empty state
                if (res.cart_count == 0) {
                    location.reload();
                } else {
                    // Remove empty delivery date groups
                    checkEmptyDeliveryGroups();
                }
            }
        },
        error: function(xhr) {
            console.error('Error removing item:', xhr);
            alert('Error removing item. Please try again.');
        }
    });
}

// -----------------------------------------
// Update Cart Count in Header Badge
// -----------------------------------------
function cartupdateCartCount(count) {
    // Update all cart count elements
    $('.cart-count, #cart-count').text(count);
    
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
    $('.bg-white.rounded-xl.shadow-sm').each(function() {
        const $group = $(this);
        const itemsCount = $group.find('.p-4.flex.gap-3').length;
        
        if (itemsCount === 0) {
            $group.remove();
        }
    });
}

// -----------------------------------------
// Initialize page
// -----------------------------------------
$(document).ready(function() {
    // Set initial cart count from server session
    const initialCount = {{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }};
        updateCartCount(cartCount);

    alert(initialCount);
});
</script>

@endsection
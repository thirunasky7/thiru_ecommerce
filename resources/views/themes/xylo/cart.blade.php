@extends('themes.xylo.layouts.master')
@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"> 
@endsection
@section('content')
    @php $currency = activeCurrency(); @endphp
    <section class="banner-area inner-banner pt-5 animate__animated animate__fadeIn productinnerbanner">
        <div class="container h-100">
            <div class="row">
                <div class="col-md-4">
                    <div class="breadcrumbs">
                        <a href="#">Home Page</a> <i class="fa fa-angle-right"></i> <a href="#">Headphone</a> <i
                            class="fa fa-angle-right"></i> Espresso decaffeinato
                    </div>
                </div>
            </div>
        </div>
    </section>
    @php $total = 0; @endphp
    <div class="cart-page pb-5 pt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                @if(empty($cart))
                    <p class="alert alert-warning">Your cart is empty.</p>
                @else
                <div class="table-responsive">
                    <table class="w-100 table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        
                      
                        <tbody>
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
                $variantImage = 'default.jpg';
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
        @endphp
        <tr id="cart-row-{{ $key }}">
            <td>
                <button class="btn btn-link p-0 bnlink remove-from-cart" data-id="{{ $key }}">
                    <i class="fa-regular fa-circle-xmark"></i>
                </button>
            </td>
            <td>
                <div class="pr-imghead">
                    <img src="{{ $variantImage }}" 
                         alt="{{ $variantName }}">
                    <p>{{ $variantName }}</p>
                </div>
                
                <div id="size-color-wrapper">
                    @php
                        $sizes = [];
                        $colors = [];
                    @endphp

                    @if (!empty($item['attributes']))
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
                    @endif

                    @if (!empty($sizes))
                        <span id="product-size">
                            @foreach ($sizes as $size)
                                <span class="size-box">{{ $size }}</span>
                            @endforeach
                        </span>
                    @endif

                    @if (!empty($colors))
                        <span id="product-color">
                            @foreach ($colors as $color)
                                <span class="color-circle {{ strtolower($color) }}" ></span>
                            @endforeach
                        </span>
                    @endif
                </div>
            </td>
            <td>
                <strong>{{ $currency->symbol }}{{ number_format($item['price'], 2) }}</strong>
            </td>
            <td>
                <input type="number" value="{{ $item['quantity'] }}" min="1" data-id="{{ $key }}" class="quantity-input">
            </td>
            <td>
                <strong class="subtotal" id="subtotal-{{ $key }}">{{ $currency->symbol }}{{ number_format($subtotal, 2) }}</strong>
            </td>
        </tr>
        @php $total += $subtotal; @endphp
    @endforeach
</tbody>


                    </table>
                </div>
                @endif
                <div class="btn-group mt-4">
                    <a href="{{ route('xylo.home') }}" class="btn-light">Continue Shopping</a>
                    <a href="#" class="read-more update-cart" style="display: none;">Update cart</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="cart-box">
                    <h3 class="cart-heading">Cart totals</h3>

                    <div class="row border-bottom pb-2 mb-2 mt-4">
                        <div class="col-6 col-md-4">Subtotal</div>
                        <div class="col-6 col-md-8 text-end" id="cart-subtotal">{{ $currency->symbol }}{{ number_format($total, 2) }}</div>
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
                        <div class="row border-bottom pb-2 mb-2 d-flex align-items-center">
                            <div class="col-8 d-flex align-items-center">Discount ({{ $coupon['code'] }})</div>
                            <div class="col-4 d-flex justify-content-end align-items-center">
                                    -{{ $currency->symbol }}{{ number_format($discountAmount, 2) }}
                                <form id="removeCouponForm" class="ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm p-1 remove-coupon"
                                        style="border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center;">
                                        x
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="row border-bottom pb-2 mb-2">
                        <div class="col-6 col-md-4">Total</div>
                        <div class="col-6 col-md-8 text-end"><span id="cart-total">{{ $currency->symbol }}{{ number_format($finalTotal, 2) }}</span></div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('checkout.index') }}" class="proceed-to-checkout d-block text-center">Proceed to checkout</a>
                    </div>
                </div>

                <div class="coupon-box mt-4">
                    <h3 class="cart-heading mb-4">Coupon</h3>

                    <form id="applyCouponForm">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="code" id="coupon_code" placeholder="Coupon code" class="form-control">
                        </div>
                        <button type="submit" class="btn-light d-block text-center w-100">Apply Coupon</button>
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
        // Auto-update cart when quantity changes
        $('.quantity-input').on('change', function() {
            let productId = $(this).data('id');
            let quantity = $(this).val();
            let price = parseFloat($(this).closest('tr').find('td:nth-child(3) strong').text().replace('{{ $currency->symbol }}', ''));
            
            // Update subtotal immediately for better UX
            let subtotal = price * quantity;
            $('#subtotal-' + productId).text('{{ $currency->symbol }}' + subtotal.toFixed(2));
            
            // Update cart via AJAX
            updateCartItem(productId, quantity);
        });

        function updateCartItem(productId, quantity) {
            $.ajax({
                url: "{{ route('cart.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    cart: [{
                        product_id: productId,
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
                let productId = $(this).data('id');
                let quantity = $(this).val();
                let price = parseFloat($(this).closest('tr').find('td:nth-child(3) strong').text().replace('{{ $currency->symbol }}', ''));
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
            let productId = $(this).data('id');
            
            $.ajax({
                url: "{{ route('cart.remove') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        $('#cart-row-' + productId).remove();
                        updateCartTotals(response.cart);
                        toastr.success(response.message);
                        
                        // Reload if cart is empty
                        if ($('tbody tr').length === 0) {
                            location.reload();
                        }
                    }
                }
            });
        });
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("applyCouponForm")?.addEventListener("submit", function(e) {
        e.preventDefault();
        let code = document.getElementById("coupon_code").value;
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
                toastr.success(data.message, "Applied", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000
                });
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                toastr.error(data.message, "Error", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000
                });
            }
        });
    });

    document.getElementById("removeCouponForm")?.addEventListener("submit", function(e) {
        e.preventDefault();
        fetch("{{ route('cart.removeCoupon') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            toastr.success(data.message, "Removed", {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 5000
            });
            
            setTimeout(() => {
                if (data.success) location.reload();
            }, 1000);
        });
    });
});
</script>

@endsection
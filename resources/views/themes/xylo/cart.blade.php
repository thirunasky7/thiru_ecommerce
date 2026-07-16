@extends('themes.xylo.layouts.master')

@section('title', 'Cart — ThaiYur')

@section('content')
<section class="ty-page-hero">
    <div class="container">
        <p class="ty-eyebrow">Checkout</p>
        <h1>Your cart</h1>
        <p>Review monthly packages and products before placing your order.</p>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        @if(empty($cartItems))
            <div class="ty-surface-panel text-center py-5">
                <h2>Your cart is empty</h2>
                <p class="text-muted">Browse monthly food packages or marketplace products.</p>
                <div class="d-flex gap-2 justify-content-center flex-wrap mt-3">
                    <a href="{{ route('packages.index') }}" class="ty-btn ty-btn--solid">Monthly packages</a>
                    <a href="{{ route('service.show', 'grocery') }}" class="ty-btn ty-btn--ghost">Grocery</a>
                </div>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    @foreach($groupedCartItems as $group => $items)
                        <div class="ty-surface-panel mb-3">
                            <h3 class="mb-3">{{ $group === 'package' ? 'Monthly food packages' : 'Products & meals' }}</h3>
                            @foreach($items as $key => $item)
                                <div class="ty-cart-row" data-cart-id="{{ $item['cart_item_id'] ?? $key }}">
                                    <div>
                                        <strong>{{ $item['name'] }}</strong>
                                        <div class="small text-muted">
                                            @if(($item['item_type'] ?? '') === 'package')
                                                {{ $item['display_order_date'] ?? '' }}
                                                · {{ $item['duration_days'] ?? 30 }} days
                                            @else
                                                {{ ucfirst($item['meal_type'] ?? 'regular') }}
                                                · {{ $item['display_order_date'] ?? $item['order_for_date'] ?? '' }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ty-cart-row__actions">
                                        <div class="ty-qty">
                                            <button type="button" class="qty-btn" data-delta="-1">−</button>
                                            <span class="qty-val">{{ $item['quantity'] }}</span>
                                            <button type="button" class="qty-btn" data-delta="1">+</button>
                                        </div>
                                        <div class="ty-price"><strong>₹{{ number_format(($item['price'] * $item['quantity']), 0) }}</strong></div>
                                        <button type="button" class="ty-icon-btn remove-item" title="Remove"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-4">
                    <div class="ty-surface-panel ty-order-panel">
                        @php $total = collect($cartItems)->sum(fn($i) => $i['price'] * $i['quantity']); @endphp
                        <h3>Order summary</h3>
                        <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><strong>₹{{ number_format($total, 0) }}</strong></div>
                        <div class="d-flex justify-content-between mb-3 text-muted"><span>Delivery</span><span>Calculated at checkout</span></div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3"><span>Total</span><strong>₹{{ number_format($total, 0) }}</strong></div>
                        <a href="{{ route('checkout.index') }}" class="ty-btn ty-btn--solid w-100">Proceed to checkout</a>
                        <a href="{{ route('packages.index') }}" class="ty-link d-block text-center mt-3">Continue shopping</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('js')
<script>
$('.qty-btn').on('click', function () {
    const row = $(this).closest('.ty-cart-row');
    const id = row.data('cart-id');
    let qty = parseInt(row.find('.qty-val').text(), 10) + parseInt($(this).data('delta'), 10);
    if (qty < 0) qty = 0;
    $.post("{{ route('cart.update-quantity') }}", {
        _token: "{{ csrf_token() }}",
        cart_item_id: id,
        quantity: qty
    }).done(function () { location.reload(); });
});
$('.remove-item').on('click', function () {
    const id = $(this).closest('.ty-cart-row').data('cart-id');
    $.ajax({
        url: "{{ url('/cart/remove') }}/" + id,
        method: 'DELETE',
        data: { _token: "{{ csrf_token() }}" }
    }).done(function () { location.reload(); });
});
</script>
@endsection

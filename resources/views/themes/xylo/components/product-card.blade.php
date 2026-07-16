@php
    $name = optional($product->translation)->name ?? $product->slug;
    $price = optional($product->primaryVariant)->price ?? $product->price ?? 0;
    $discount = optional($product->primaryVariant)->discount_price ?? $product->discount_price;
    $display = ($discount && $discount < $price) ? $discount : $price;
    $serviceName = optional($product->serviceType)->name ?? null;
@endphp
<div class="ty-product-card" data-reveal>
    <a href="{{ route('product.show', $product->slug) }}" class="ty-product-card__link">
        <div class="ty-product-card__media">
            @if($serviceName)
                <span class="ty-chip ty-chip--sm">{{ $serviceName }}</span>
            @endif
            <div class="ty-product-card__placeholder"><i class="fas fa-box-open"></i></div>
        </div>
    </a>
    <div class="ty-product-card__body">
        @if(optional($product->shop)->name)
            <small class="text-muted">{{ $product->shop->name }}</small>
        @endif
        <a href="{{ route('product.show', $product->slug) }}"><h3>{{ $name }}</h3></a>
        <div class="ty-product-card__footer">
            <div class="ty-price">
                <strong>₹{{ number_format($display, 0) }}</strong>
                @if($discount && $discount < $price)
                    <s>₹{{ number_format($price, 0) }}</s>
                @endif
            </div>
            <button type="button"
                class="ty-cart-icon-btn"
                title="Add to cart"
                aria-label="Add to cart"
                onclick="event.preventDefault(); window.tyAddToCart({{ $product->id }}, 1);">
                <i class="fas fa-bag-shopping"></i>
            </button>
        </div>
    </div>
</div>

@extends('themes.xylo.layouts.master')

@section('title', (optional($product->translation)->name ?? $product->slug) . ' — ThaiYur')

@section('content')
@php
    $name = optional($product->translation)->name ?? $product->slug;
    $description = optional($product->translation)->description ?? '';
    $short = optional($product->translation)->short_description ?? \Illuminate\Support\Str::limit(strip_tags($description), 140);
    $primaryVariant = $product->primaryVariant;
    $price = optional($primaryVariant)->price ?? $product->price ?? 0;
    $discount = optional($primaryVariant)->discount_price ?? $product->discount_price;
    $displayPrice = ($discount && $discount < $price) ? $discount : $price;
    $images = $product->images ?? collect();
    $thumb = optional($product->thumbnail)->image_url;
@endphp

<section class="ty-page-hero">
    <div class="container">
        <p class="ty-eyebrow">{{ optional($product->serviceType)->name ?? optional($product->category->translation ?? null)->name ?? 'Product' }}</p>
        <h1>{{ $name }}</h1>
        <p>{{ $short }}</p>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="ty-surface-panel ty-product-gallery">
                    @if($images->count())
                        <img src="{{ media_url($images->first()->image_url) }}" alt="{{ $name }}" class="ty-product-gallery__main" id="mainProductImage"
                             onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=60'">
                        <div class="ty-product-gallery__thumbs">
                            @foreach($images->take(5) as $image)
                                <button type="button" class="ty-thumb-btn" data-src="{{ media_url($image->image_url) }}">
                                    <img src="{{ media_url($image->image_url) }}" alt=""
                                         onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=200&q=60'">
                                </button>
                            @endforeach
                        </div>
                    @elseif($thumb)
                        <img src="{{ media_url($thumb) }}" alt="{{ $name }}" class="ty-product-gallery__main">
                    @else
                        <img src="{{ product_image($product) }}" alt="{{ $name }}" class="ty-product-gallery__main">
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ty-surface-panel ty-order-panel">
                    <div class="d-flex gap-2 flex-wrap mb-3">
                        @if(!empty($inStock))
                            <span class="ty-chip" style="background:#e8f7ef;color:#0f766e">In stock</span>
                        @else
                            <span class="ty-chip" style="background:#fde8e8;color:#b42318">Check availability</span>
                        @endif
                        @if(optional($product->shop)->name)
                            <span class="ty-chip">{{ $product->shop->name }}</span>
                        @endif
                    </div>

                    <div class="ty-package-card__price mb-3">
                        <strong>₹{{ number_format($displayPrice, 0) }}</strong>
                        @if($discount && $discount < $price)
                            <s>₹{{ number_format($price, 0) }}</s>
                        @endif
                    </div>

                    <div class="mb-3">
                        @php $avg = round($product->averageRating(), 1); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa{{ $i <= $avg ? 's' : 'r' }} fa-star" style="color:#d4a017"></i>
                        @endfor
                        <span class="text-muted small ms-1">({{ $product->reviews_count ?? 0 }} reviews)</span>
                    </div>

                    <label class="form-label">Quantity</label>
                    <div class="ty-qty mb-3" style="width:fit-content;padding:.4rem .75rem">
                        <button type="button" id="qtyMinus">−</button>
                        <span id="qtyVal" class="qty-val">1</span>
                        <button type="button" id="qtyPlus">+</button>
                    </div>

                    <button type="button" class="ty-btn ty-btn--solid w-100 mb-2" id="detailAddCart"
                        data-id="{{ $product->id }}">
                        <i class="fas fa-bag-shopping me-2"></i> Add to cart
                    </button>
                    <a href="{{ route('cart.page') }}" class="ty-btn ty-btn--ghost w-100">View cart</a>
                </div>
            </div>
        </div>

        @if($description)
            <div class="ty-surface-panel mt-4">
                <h2 class="mb-3">About this product</h2>
                <div class="text-muted">{!! nl2br(e(strip_tags($description))) !!}</div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('js')
<script>
$('.ty-thumb-btn').on('click', function () {
    $('#mainProductImage').attr('src', $(this).data('src'));
});
let qty = 1;
$('#qtyPlus').on('click', () => { qty = Math.min(20, qty + 1); $('#qtyVal').text(qty); });
$('#qtyMinus').on('click', () => { qty = Math.max(1, qty - 1); $('#qtyVal').text(qty); });
$('#detailAddCart').on('click', function () {
    window.tyAddToCart($(this).data('id'), qty);
});
</script>
@endsection

@extends('themes.xylo.layouts.master')

@section('title', $shop->name . ' — ThaiYur')

@section('content')
<section class="ty-page-hero" style="--accent: {{ optional($shop->serviceType)->color ?? '#0F766E' }}">
    <div class="container">
        <p class="ty-eyebrow">{{ optional($shop->serviceType)->name ?? 'Shop' }}</p>
        <h1>{{ $shop->name }}</h1>
        <p>{{ $shop->description }}</p>
        <div class="d-flex gap-3 flex-wrap mt-2">
            <span class="ty-chip"><i class="fas fa-star"></i> {{ number_format($shop->rating, 1) }}</span>
            <span class="ty-chip">{{ $shop->delivery_time }}</span>
            <span class="ty-chip">Min ₹{{ number_format($shop->min_order_amount, 0) }}</span>
        </div>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        <div class="row g-3">
            @forelse($products as $product)
                <div class="col-6 col-md-3">
                    @include('themes.xylo.components.product-card', ['product' => $product])
                </div>
            @empty
                <div class="col-12"><p class="text-muted">No products in this shop yet.</p></div>
            @endforelse
        </div>
        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</section>
@endsection

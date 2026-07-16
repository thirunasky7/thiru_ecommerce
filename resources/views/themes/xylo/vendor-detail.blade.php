@extends('themes.xylo.layouts.master')

@section('title', ($vendor->business_name ?? $vendor->name) . ' — ThaiYur')

@section('content')
<section class="ty-page-hero">
    <div class="container">
        <p class="ty-eyebrow">Vendor</p>
        <h1>{{ $vendor->business_name ?? $vendor->name }}</h1>
        <p>{{ $vendor->description }}</p>
        <div class="d-flex gap-3 flex-wrap mt-2">
            <span class="ty-chip">{{ $vendor->city }}</span>
            <span class="ty-chip"><i class="fas fa-star"></i> {{ number_format($vendor->rating, 1) }}</span>
        </div>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        @if($vendor->shops->count())
            <div class="ty-section__head"><h2>Shops</h2></div>
            <div class="row g-3 mb-5">
                @foreach($vendor->shops as $shop)
                    <div class="col-md-4">
                        <a href="{{ route('store.show', $shop->slug) }}" class="ty-service-card">
                            <h3>{{ $shop->name }}</h3>
                            <p>{{ optional($shop->serviceType)->name }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="ty-section__head"><h2>Products</h2></div>
        <div class="row g-3">
            @foreach($products as $product)
                <div class="col-6 col-md-3">
                    @include('themes.xylo.components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</section>
@endsection

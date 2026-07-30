@extends('themes.xylo.layouts.master')

@section('title', 'ThaiYur — Food · Grocery · Marketplace')

@section('content')
<section class="ty-hero">
    <div class="ty-hero__bg" aria-hidden="true"></div>
    <div class="container ty-hero__inner">
        <p class="ty-eyebrow" data-reveal>ThaiYur Marketplace</p>
        <h1 class="ty-hero__title" data-reveal>One place for <em>food</em>, <em>grocery</em> &amp; local products</h1>
        <p class="ty-hero__sub" data-reveal>Order from trusted vendors near you — meals, essentials, and lifestyle goods with a premium delivery experience.</p>
        <div class="ty-hero__cta" data-reveal>
            <a href="{{ route('packages.index') }}" class="ty-btn ty-btn--solid">Monthly food packages</a>
            <a href="{{ route('service.show', 'grocery') }}" class="ty-btn ty-btn--ghost-light">Shop grocery</a>
        </div>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        <div class="ty-section__head">
            <h2>Choose a service</h2>
            <p>Pick what you need — each service connects you to dedicated vendors.</p>
        </div>
        <div class="row g-4">
            @forelse($services as $service)
                <div class="col-md-4" data-reveal>
                    <a href="{{ route('service.show', $service->slug) }}" class="ty-service-card" style="--service-color: {{ $service->color }}">
                        <div class="ty-service-card__icon"><i class="fas {{ $service->icon }}"></i></div>
                        <h3>{{ $service->name }}</h3>
                        <p>{{ $service->description }}</p>
                        <span class="ty-link">Browse <i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
            @empty
                <div class="col-12"><p class="text-muted">Services will appear after seeding.</p></div>
            @endforelse
        </div>
    </div>
</section>

<section class="ty-section ty-section--muted">
    <div class="container">
        <div class="ty-section__head d-flex justify-content-between align-items-end flex-wrap gap-3">
            <div>
                <h2>Featured vendors</h2>
                <p>Top-rated partners across food, grocery, and products.</p>
            </div>
            <a href="{{ route('vendors.index') }}" class="ty-link">View all vendors</a>
        </div>
        <div class="row g-4">
            @foreach($featuredShops as $shop)
                <div class="col-md-4 col-lg-4" data-reveal>
                    <a href="{{ route('store.show', $shop->slug) }}" class="ty-shop-card">
                        <div class="ty-shop-card__media">
                            <img src="{{ shop_image($shop, 'cover') }}" alt="{{ $shop->name }}" loading="lazy">
                            <span class="ty-chip">{{ optional($shop->serviceType)->name ?? 'Shop' }}</span>
                        </div>
                        <div class="ty-shop-card__body">
                            <h3>{{ $shop->name }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($shop->description, 80) }}</p>
                            <div class="ty-shop-card__meta">
                                <span><i class="fas fa-star"></i> {{ number_format($shop->rating, 1) }}</span>
                                <span>{{ $shop->delivery_time ?? 'Fast delivery' }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        <div class="ty-section__head">
            <h2>Popular picks</h2>
            <p>Handpicked items from our multivendor catalog.</p>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-6 col-md-3">
                    @include('themes.xylo.components.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="ty-cta-band">
    <div class="container ty-cta-band__inner" data-reveal>
        <h2>Become a ThaiYur vendor</h2>
        <p>List your restaurant, grocery store, or product shop and reach more customers.</p>
        <div class="d-flex gap-2 flex-wrap justify-content-center">
            <a href="{{ route('vendor.register') }}" class="ty-btn ty-btn--solid">Apply as partner</a>
            <a href="{{ route('vendor.login') }}" class="ty-btn ty-btn--ghost">Partner login</a>
        </div>
    </div>
</section>
@endsection

@extends('themes.xylo.layouts.master')

@section('title', $service->name . ' — ThaiYur')

@section('content')
<section class="ty-page-hero" style="--accent: {{ $service->color }}">
    <div class="container">
        <p class="ty-eyebrow">{{ $service->name }}</p>
        <h1>{{ $service->name }} near you</h1>
        <p>{{ $service->description }}</p>
        @if($service->slug === 'food')
            <div class="mt-3 d-flex gap-2 flex-wrap">
                <a href="{{ route('packages.index') }}" class="ty-btn ty-btn--solid">Monthly packages</a>
                <a href="{{ route('regular-order') }}" class="ty-btn ty-btn--ghost">Single meals</a>
            </div>
        @endif
    </div>
</section>

<section class="ty-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <aside class="ty-filter">
                    <h5>Categories</h5>
                    <ul>
                        <li><a href="{{ route('service.show', $service->slug) }}" class="{{ !request('category') ? 'active' : '' }}">All</a></li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('service.show', $service->slug) }}?category={{ $cat->id }}"
                                   class="{{ request('category') == $cat->id ? 'active' : '' }}">
                                    {{ optional($cat->translation)->name ?? $cat->slug }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <h5 class="mt-4">Shops</h5>
                    <ul>
                        @foreach($shops->take(8) as $shop)
                            <li><a href="{{ route('store.show', $shop->slug) }}">{{ $shop->name }}</a></li>
                        @endforeach
                    </ul>
                </aside>
            </div>
            <div class="col-lg-9">
                <div class="ty-section__head mb-4">
                    <h2>Available products</h2>
                    <p>{{ $products->total() }} items from {{ $shops->total() }} shops</p>
                </div>
                <div class="row g-3">
                    @forelse($products as $product)
                        <div class="col-6 col-md-4">
                            @include('themes.xylo.components.product-card', ['product' => $product])
                        </div>
                    @empty
                        <div class="col-12"><p class="text-muted">No products yet for this service.</p></div>
                    @endforelse
                </div>
                <div class="mt-4">{{ $products->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>
</section>
@endsection

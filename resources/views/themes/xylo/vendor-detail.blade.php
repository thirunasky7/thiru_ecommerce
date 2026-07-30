@extends('themes.xylo.layouts.master')

@section('title', ($vendor->business_name ?? $vendor->name) . ' — ThaiYur')

@section('content')
<section class="ty-section">
    <div class="container">
        <div class="ty-vendor-hero">
            <img class="ty-vendor-hero__cover" src="{{ vendor_image($vendor, 'cover') }}" alt="">
            <img class="ty-vendor-hero__logo" src="{{ vendor_image($vendor, 'logo') }}" alt="{{ $vendor->business_name ?? $vendor->name }}">
        </div>
        <p class="ty-eyebrow">Vendor</p>
        <h1>{{ $vendor->business_name ?? $vendor->name }}</h1>
        <p class="text-muted">{{ $vendor->description }}</p>
        <div class="d-flex gap-3 flex-wrap mt-2 mb-4">
            <span class="ty-chip">{{ $vendor->city }}</span>
            <span class="ty-chip"><i class="fas fa-star"></i> {{ number_format($vendor->rating, 1) }}</span>
        </div>

        @if($vendor->shops->count())
            <div class="ty-section__head"><h2>Shops</h2></div>
            <div class="row g-3 mb-5">
                @foreach($vendor->shops as $shop)
                    <div class="col-md-4">
                        <a href="{{ route('store.show', $shop->slug) }}" class="ty-shop-card">
                            <div class="ty-shop-card__media">
                                <img src="{{ shop_image($shop, 'cover') }}" alt="{{ $shop->name }}" loading="lazy">
                                <span class="ty-chip">{{ optional($shop->serviceType)->name }}</span>
                            </div>
                            <div class="ty-shop-card__body">
                                <h3>{{ $shop->name }}</h3>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="ty-section__head"><h2>Products</h2></div>
        <div class="row g-3" id="productGrid" data-page="{{ $products->currentPage() }}">
            @include('themes.xylo.partials.product-grid-items', ['products' => $products, 'cols' => 3])
        </div>
        <div class="ty-load-more-wrap" id="loadMoreWrap" style="{{ $products->hasMorePages() ? '' : 'display:none' }}">
            <button type="button" class="ty-btn ty-btn--ghost" id="loadMoreBtn">Load more products</button>
        </div>
    </div>
</section>
@endsection

@section('js')
<script>
(function () {
    const grid = document.getElementById('productGrid');
    const btn = document.getElementById('loadMoreBtn');
    const wrap = document.getElementById('loadMoreWrap');
    if (!grid || !btn) return;
    btn.addEventListener('click', async function () {
        const page = parseInt(grid.dataset.page || '1', 10) + 1;
        btn.disabled = true;
        const url = new URL(window.location.href);
        url.searchParams.set('page', page);
        url.searchParams.set('partial', '1');
        const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }});
        const data = await res.json();
        grid.insertAdjacentHTML('beforeend', data.html || '');
        grid.dataset.page = page;
        if (!data.hasMore) wrap.style.display = 'none';
        btn.disabled = false;
    });
})();
</script>
@endsection

@extends('themes.xylo.layouts.master')

@section('title', $shop->name . ' — ThaiYur')

@section('content')
<section class="ty-section">
    <div class="container">
        <div class="ty-vendor-hero">
            <img class="ty-vendor-hero__cover" src="{{ shop_image($shop, 'cover') }}" alt="">
            <img class="ty-vendor-hero__logo" src="{{ shop_image($shop, 'logo') }}" alt="{{ $shop->name }}">
        </div>
        <p class="ty-eyebrow">{{ optional($shop->serviceType)->name ?? 'Shop' }}</p>
        <h1>{{ $shop->name }}</h1>
        <p class="text-muted">{{ $shop->description }}</p>
        <div class="d-flex gap-3 flex-wrap mt-2 mb-4">
            <span class="ty-chip"><i class="fas fa-star"></i> {{ number_format($shop->rating, 1) }}</span>
            <span class="ty-chip">{{ $shop->delivery_time }}</span>
            <span class="ty-chip">Min ₹{{ number_format($shop->min_order_amount, 0) }}</span>
        </div>

        <form method="GET" class="row g-2 align-items-end mb-4">
            <div class="col-md-5">
                <label class="form-label">Search in shop</label>
                <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Find a product…">
            </div>
            <div class="col-md-4">
                <label class="form-label">Sort</label>
                <select name="sort" class="form-select">
                    <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
                    <option value="price_low" @selected(request('sort') === 'price_low')>Price: low to high</option>
                    <option value="price_high" @selected(request('sort') === 'price_high')>Price: high to low</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="ty-btn ty-btn--solid w-100" type="submit">Apply</button>
            </div>
        </form>

        <div class="row g-3" id="productGrid" data-page="{{ $products->currentPage() }}">
            @include('themes.xylo.partials.product-grid-items', ['products' => $products, 'cols' => 3])
        </div>
        @if($products->isEmpty())
            <p class="text-muted">No products in this shop yet.</p>
        @endif
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

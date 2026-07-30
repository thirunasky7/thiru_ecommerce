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
                    <form method="GET" action="{{ route('service.show', $service->slug) }}">
                        <h5>Search</h5>
                        <input type="search" name="q" value="{{ request('q') }}" class="form-control mb-3" placeholder="Search products…">

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

                        <h5>Shops</h5>
                        <ul>
                            <li><a href="{{ route('service.show', $service->slug) }}" class="{{ !request('shop') ? 'active' : '' }}">All shops</a></li>
                            @foreach($shops as $shop)
                                <li>
                                    <a href="{{ route('service.show', $service->slug) }}?shop={{ $shop->id }}"
                                       class="{{ (string)request('shop') === (string)$shop->id ? 'active' : '' }}">
                                        {{ $shop->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <h5>Price</h5>
                        <div class="row g-2 mb-3">
                            <div class="col-6"><input type="number" name="price_min" value="{{ request('price_min') }}" class="form-control" placeholder="Min"></div>
                            <div class="col-6"><input type="number" name="price_max" value="{{ request('price_max') }}" class="form-control" placeholder="Max"></div>
                        </div>

                        <h5>Sort</h5>
                        <select name="sort" class="form-select mb-3">
                            <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
                            <option value="price_low" @selected(request('sort') === 'price_low')>Price: low to high</option>
                            <option value="price_high" @selected(request('sort') === 'price_high')>Price: high to low</option>
                        </select>

                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('shop'))
                            <input type="hidden" name="shop" value="{{ request('shop') }}">
                        @endif

                        <button class="ty-btn ty-btn--solid w-100" type="submit">Apply</button>
                    </form>
                </aside>
            </div>
            <div class="col-lg-9">
                <div class="ty-section__head mb-4">
                    <h2>Available products</h2>
                    <p><span id="productTotal">{{ $products->total() }}</span> items</p>
                </div>
                <div class="row g-3" id="productGrid" data-page="{{ $products->currentPage() }}">
                    @include('themes.xylo.partials.product-grid-items', ['products' => $products, 'cols' => 4])
                </div>
                <div class="ty-load-more-wrap" id="loadMoreWrap" style="{{ $products->hasMorePages() ? '' : 'display:none' }}">
                    <button type="button" class="ty-btn ty-btn--ghost" id="loadMoreBtn">Load more products</button>
                </div>
            </div>
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
        btn.textContent = 'Loading…';
        const url = new URL(window.location.href);
        url.searchParams.set('page', page);
        url.searchParams.set('partial', '1');
        try {
            const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }});
            const data = await res.json();
            grid.insertAdjacentHTML('beforeend', data.html || '');
            grid.dataset.page = page;
            if (!data.hasMore) wrap.style.display = 'none';
        } finally {
            btn.disabled = false;
            btn.textContent = 'Load more products';
        }
    });
})();
</script>
@endsection

@extends('themes.xylo.layouts.master')

@section('title', 'Products — ThaiYur')

@section('content')
<section class="ty-page-hero">
    <div class="container">
        <p class="ty-eyebrow">Catalog</p>
        <h1>All products</h1>
        <p>Filter by service, shop, price, and more — then load additional results as you browse.</p>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <form method="GET" action="{{ route('products.index') }}" class="ty-filter" id="productFilterForm">
                    <h5>Search</h5>
                    <input type="search" name="q" value="{{ $currentFilters['q'] }}" class="form-control mb-3" placeholder="Product name…">

                    <h5>Service</h5>
                    <select name="service_type" class="form-select mb-3">
                        <option value="">All services</option>
                        @foreach($serviceTypes as $type)
                            <option value="{{ $type->id }}" @selected((string)$currentFilters['service_type'] === (string)$type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>

                    <h5>Categories</h5>
                    <ul class="mb-3" style="max-height:180px;overflow:auto;">
                        @foreach($categories as $cat)
                            @php $slug = $cat->slug; @endphp
                            <li>
                                <label class="form-check d-flex gap-2 align-items-center py-1">
                                    <input class="form-check-input" type="checkbox" name="category_slugs[]" value="{{ $slug }}"
                                           @checked(in_array($slug, $currentFilters['categories'], true) || $currentFilters['category'] === $slug)>
                                    <span>{{ optional($cat->translation)->name ?? $cat->slug }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>

                    <h5>Shop</h5>
                    <select name="shop" class="form-select mb-3">
                        <option value="">All shops</option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" @selected((string)$currentFilters['shop'] === (string)$shop->id)>{{ $shop->name }}</option>
                        @endforeach
                    </select>

                    <h5>Vendor</h5>
                    <select name="vendor" class="form-select mb-3">
                        <option value="">All vendors</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" @selected((string)$currentFilters['vendor'] === (string)$vendor->id)>
                                {{ $vendor->business_name ?: $vendor->name }}
                            </option>
                        @endforeach
                    </select>

                    <h5>Price</h5>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <input type="number" min="0" name="price_min" value="{{ $currentFilters['price_min'] }}" class="form-control" placeholder="Min">
                        </div>
                        <div class="col-6">
                            <input type="number" min="0" name="price_max" value="{{ $currentFilters['price_max'] }}" class="form-control" placeholder="Max">
                        </div>
                    </div>

                    <h5>Rating</h5>
                    <select name="rating" class="form-select mb-3">
                        <option value="">Any</option>
                        @foreach([4,3,2] as $r)
                            <option value="{{ $r }}" @selected((string)$currentFilters['rating'] === (string)$r)>{{ $r }}+ stars</option>
                        @endforeach
                    </select>

                    <h5>Sort</h5>
                    <select name="sort" class="form-select mb-3">
                        <option value="newest" @selected($currentFilters['sort'] === 'newest')>Newest</option>
                        <option value="price_low" @selected($currentFilters['sort'] === 'price_low')>Price: low to high</option>
                        <option value="price_high" @selected($currentFilters['sort'] === 'price_high')>Price: high to low</option>
                        <option value="rating" @selected($currentFilters['sort'] === 'rating')>Top rated</option>
                        <option value="name" @selected($currentFilters['sort'] === 'name')>Name A–Z</option>
                    </select>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="featured" value="1" id="featured" @checked($currentFilters['featured'])>
                        <label class="form-check-label" for="featured">Featured only</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="in_stock" @checked($currentFilters['in_stock'])>
                        <label class="form-check-label" for="in_stock">In stock</label>
                    </div>

                    <button type="submit" class="ty-btn ty-btn--solid w-100 mb-2">Apply filters</button>
                    <a href="{{ route('products.index') }}" class="ty-btn ty-btn--ghost w-100">Reset</a>
                </form>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <p class="mb-0 text-muted"><span id="productTotal">{{ $products->total() }}</span> products</p>
                </div>
                <div class="row g-3" id="productGrid"
                     data-page="{{ $products->currentPage() }}"
                     data-has-more="{{ $products->hasMorePages() ? '1' : '0' }}">
                    @include('themes.xylo.partials.product-grid-items', ['products' => $products, 'cols' => 3])
                </div>
                @if($products->isEmpty())
                    <p class="text-muted">No products match these filters.</p>
                @endif
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
    const form = document.getElementById('productFilterForm');
    if (form) {
        form.addEventListener('submit', function () {
            const boxes = Array.from(form.querySelectorAll('input[name="category_slugs[]"]:checked')).map(el => el.value);
            let hidden = form.querySelector('input[name="categories"]');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'categories';
                form.appendChild(hidden);
            }
            hidden.value = boxes.join(',');
            form.querySelectorAll('input[name="category_slugs[]"]').forEach(el => el.disabled = true);
        });
    }

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
            const res = await fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();
            grid.insertAdjacentHTML('beforeend', data.html || '');
            grid.dataset.page = page;
            if (data.total != null) {
                const totalEl = document.getElementById('productTotal');
                if (totalEl) totalEl.textContent = data.total;
            }
            if (!data.hasMore) {
                wrap.style.display = 'none';
            }
        } catch (e) {
            console.error(e);
        } finally {
            btn.disabled = false;
            btn.textContent = 'Load more products';
        }
    });
})();
</script>
@endsection

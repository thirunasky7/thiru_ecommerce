@extends('themes.xylo.layouts.master')

@section('title', 'Products — ThaiYur')

@section('content')
<section class="ty-page-hero">
    <div class="container">
        <p class="ty-eyebrow">Catalog</p>
        <h1>All products</h1>
        <p>Shop food, grocery, and marketplace items from ThaiYur vendors.</p>
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
                <div class="col-12"><p class="text-muted">No products found.</p></div>
            @endforelse
        </div>
        @if(method_exists($products, 'links'))
            <div class="mt-4">{{ $products->withQueryString()->links() }}</div>
        @endif
    </div>
</section>
@endsection

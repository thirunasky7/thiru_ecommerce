@extends('themes.xylo.layouts.master')

@section('title', 'Single meals — ThaiYur')

@section('content')
<section class="ty-page-hero" style="--accent:#C45C26">
    <div class="container">
        <p class="ty-eyebrow">Food · à la carte</p>
        <h1>Order single meals</h1>
        <p>Prefer a monthly plan? <a href="{{ route('packages.index') }}" class="ty-link">Browse packages</a></p>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        @if(isset($categories) && $categories->count())
            <div class="d-flex gap-2 flex-wrap mb-4">
                <a href="{{ route('regular-order') }}" class="ty-chip {{ empty($category) ? '' : '' }}">All</a>
                @foreach($categories as $cat)
                    <a href="{{ url('/regular-order/'.$cat->slug) }}" class="ty-chip">
                        {{ optional($cat->translation)->name ?? $cat->slug }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="row g-3">
            @forelse($saleItems as $product)
                <div class="col-6 col-md-3">
                    @include('themes.xylo.components.product-card', ['product' => $product])
                </div>
            @empty
                <div class="col-12"><p class="text-muted">No single meals available. Try a monthly package instead.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection

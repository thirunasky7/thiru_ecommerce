@extends('themes.xylo.layouts.master')

@section('title', 'Vendors — ThaiYur')

@section('content')
<section class="ty-page-hero">
    <div class="container">
        <p class="ty-eyebrow">Partners</p>
        <h1>Meet our vendors</h1>
        <p>Independent kitchens, grocery marts, and product sellers on ThaiYur.</p>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        <div class="row g-4">
            @foreach($vendors as $vendor)
                <div class="col-md-6 col-lg-4" data-reveal>
                    <a href="{{ route('vendor.show', $vendor->id) }}" class="ty-shop-card">
                        <div class="ty-shop-card__media">
                            <img src="{{ vendor_image($vendor, 'cover') }}" alt="{{ $vendor->business_name ?? $vendor->name }}" loading="lazy">
                        </div>
                        <div class="ty-shop-card__body">
                            <h3>{{ $vendor->business_name ?? $vendor->name }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($vendor->description, 90) }}</p>
                            <div class="ty-shop-card__meta">
                                <span><i class="fas fa-star"></i> {{ number_format($vendor->rating, 1) }}</span>
                                <span>{{ $vendor->city }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $vendors->links() }}</div>
    </div>
</section>
@endsection

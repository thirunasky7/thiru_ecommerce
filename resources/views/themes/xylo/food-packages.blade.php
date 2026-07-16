@extends('themes.xylo.layouts.master')

@section('title', 'Monthly Food Packages — ThaiYur')

@section('content')
<section class="ty-page-hero" style="--accent:#C45C26">
    <div class="container">
        <p class="ty-eyebrow">Food service</p>
        <h1>Monthly meal packages</h1>
        <p>Skip daily ordering. Subscribe to a curated monthly food plan with door delivery from local kitchen partners.</p>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        <div class="row g-4">
            @forelse($packages as $package)
                <div class="col-md-6 col-lg-4" data-reveal>
                    <article class="ty-package-card">
                        <div class="ty-package-card__top" style="background: linear-gradient(145deg, #c45c26, #7c3a18);">
                            @if($package->badge)
                                <span class="ty-chip">{{ $package->badge }}</span>
                            @endif
                            <h3>{{ $package->name }}</h3>
                            <p>{{ $package->duration_days }}-day plan · {{ $package->meals_per_day }} meal{{ $package->meals_per_day > 1 ? 's' : '' }}/day</p>
                        </div>
                        <div class="ty-package-card__body">
                            <p>{{ \Illuminate\Support\Str::limit($package->description, 110) }}</p>
                            <ul class="ty-package-meta">
                                <li><i class="fas fa-utensils"></i> {{ $package->meal_types_label ?: 'Custom meals' }}</li>
                                <li><i class="fas fa-store"></i> {{ optional($package->shop)->name ?? optional($package->vendor)->business_name ?? 'ThaiYur Kitchen' }}</li>
                            </ul>
                            <div class="ty-package-card__price">
                                <strong>₹{{ number_format($package->price, 0) }}</strong>
                                @if($package->compare_price)
                                    <s>₹{{ number_format($package->compare_price, 0) }}</s>
                                @endif
                                <span>/ month</span>
                            </div>
                            <a href="{{ route('packages.show', $package->slug) }}" class="ty-btn ty-btn--solid w-100">View plan</a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12"><p class="text-muted">No monthly packages available yet.</p></div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@extends('themes.xylo.layouts.master')

@section('title', 'Our Services — ThaiYur')

@section('content')
<section class="ty-page-hero">
    <div class="container">
        <p class="ty-eyebrow">Services</p>
        <h1>Everything you need, from local vendors</h1>
        <p>Food delivery, grocery shopping, and a full product marketplace — unified in one premium experience.</p>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        <div class="row g-4">
            @foreach($services as $service)
                <div class="col-md-4" data-reveal>
                    <a href="{{ route('service.show', $service->slug) }}" class="ty-service-card ty-service-card--lg" style="--service-color: {{ $service->color }}">
                        <div class="ty-service-card__icon"><i class="fas {{ $service->icon }}"></i></div>
                        <h3>{{ $service->name }}</h3>
                        <p>{{ $service->description }}</p>
                        <div class="ty-service-card__meta">{{ $service->shops_count }} active shops</div>
                        <span class="ty-link">Explore <i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

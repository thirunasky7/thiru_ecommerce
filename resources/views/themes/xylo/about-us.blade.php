@extends('themes.xylo.layouts.master')

@section('title', 'About ThaiYur')

@section('content')
<section class="ty-page-hero">
    <div class="container">
        <p class="ty-eyebrow">About</p>
        <h1>ThaiYur marketplace</h1>
        <p>A multivendor platform for monthly meal packages, grocery, and everyday products — built around trusted local sellers.</p>
    </div>
</section>
<section class="ty-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4" data-reveal>
                <div class="ty-service-card" style="--service-color:#C45C26">
                    <h3>Monthly food</h3>
                    <p>Subscribe to curated meal plans with door delivery for the full month.</p>
                </div>
            </div>
            <div class="col-md-4" data-reveal>
                <div class="ty-service-card" style="--service-color:#2D6A4F">
                    <h3>Grocery</h3>
                    <p>Fresh produce and pantry essentials from neighborhood vendors.</p>
                </div>
            </div>
            <div class="col-md-4" data-reveal>
                <div class="ty-service-card" style="--service-color:#1B4F72">
                    <h3>Products</h3>
                    <p>Home and lifestyle goods from independent marketplace sellers.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

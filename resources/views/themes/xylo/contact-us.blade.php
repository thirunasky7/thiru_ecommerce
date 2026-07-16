@extends('themes.xylo.layouts.master')

@section('title', 'Contact — ThaiYur')

@section('content')
<section class="ty-page-hero">
    <div class="container">
        <p class="ty-eyebrow">Contact</p>
        <h1>Talk to ThaiYur</h1>
        <p>Questions about monthly packages, grocery delivery, or becoming a vendor? Reach out.</p>
    </div>
</section>
<section class="ty-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="ty-surface-panel">
                    <h3>Support</h3>
                    <p class="mb-2"><i class="fas fa-envelope me-2"></i> hello@thaiyur.com</p>
                    <p class="mb-2"><i class="fas fa-phone me-2"></i> +91 98765 43210</p>
                    <p class="mb-0"><i class="fas fa-location-dot me-2"></i> ThaiYur Hub, Chennai</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ty-surface-panel">
                    <h3>Vendors</h3>
                    <p>List your kitchen or shop on ThaiYur and reach more customers.</p>
                    <a href="{{ route('vendor.login') }}" class="ty-btn ty-btn--solid">Vendor login</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

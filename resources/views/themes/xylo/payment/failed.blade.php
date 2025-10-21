@extends('themes.xylo.layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="text-danger mb-4">
                <i class="fas fa-times-circle fa-5x"></i>
            </div>
            <h1>Payment Failed</h1>
            <p class="lead">We're sorry, but your payment could not be processed.</p>
            <div class="mt-4">
                <a href="{{ route('checkout.index') }}" class="btn btn-primary me-3">
                    Try Again
                </a>
                <a href="{{ route('xylo.home') }}" class="btn btn-outline-secondary">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
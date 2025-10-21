@extends('themes.xylo.layouts.master')

@section('content')
@php $currency = activeCurrency(); @endphp

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            
            <!-- Success Icon -->
            <div class="text-success mb-4">
                <i class="fas fa-check-circle" style="font-size: 4rem;"></i>
            </div>

            <!-- Main Message -->
            <h2 class="mb-3">Order Confirmed!</h2>
            <p class="text-muted mb-4">
                Thank you for your purchase. Your order #{{ $order->order_number }} has been received.
            </p>

        </div>
    </div>
</div>
@endsection
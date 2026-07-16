@extends('themes.xylo.layouts.master')

@section('title', 'Order confirmed — ThaiYur')

@section('content')
<section class="ty-section">
    <div class="container">
        <div class="ty-surface-panel text-center py-5 mx-auto" style="max-width:520px">
            <div class="mb-3" style="font-size:3rem;color:#0f766e"><i class="fas fa-circle-check"></i></div>
            <h1 class="mb-2">Order confirmed</h1>
            <p class="text-muted mb-3">{{ $message ?? 'Thank you for ordering with ThaiYur.' }}</p>
            <p class="mb-4">Order <strong>#{{ $order->order_number }}</strong></p>
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ route('order.tracking') }}" class="ty-btn ty-btn--ghost">Track order</a>
                <a href="{{ route('xylo.home') }}" class="ty-btn ty-btn--solid">Back home</a>
            </div>
        </div>
    </div>
</section>
@endsection

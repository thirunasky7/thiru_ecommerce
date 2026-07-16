@extends('themes.xylo.layouts.master')

@section('title', 'Track order — ThaiYur')

@section('content')
<section class="ty-page-hero">
    <div class="container">
        <p class="ty-eyebrow">Orders</p>
        <h1>Track your order</h1>
        <p>Enter your phone number to see status for packages and products.</p>
    </div>
</section>
<section class="ty-section">
    <div class="container">
        <div class="ty-surface-panel mx-auto" style="max-width:520px">
            <form method="POST" action="{{ route('order.track') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Phone number *</label>
                    <input type="text" name="mobile_number" class="form-control" placeholder="9876543210" maxlength="10" required value="{{ old('mobile_number', $mobileNumber ?? '') }}">
                    @error('mobile_number')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Order number (optional)</label>
                    <input type="text" name="order_id" class="form-control" placeholder="ORD..." value="{{ old('order_id') }}">
                </div>
                <button class="ty-btn ty-btn--solid w-100">Track</button>
            </form>
            @if(session('error'))
                <div class="alert alert-danger mt-3 mb-0">{{ session('error') }}</div>
            @endif
            @if(isset($orders))
                <div class="mt-4">
                    @forelse($orders as $order)
                        <div class="border rounded p-3 mb-2">
                            <strong>{{ $order->order_number }}</strong>
                            <span class="badge bg-secondary">{{ $order->status }}</span>
                            <div class="small text-muted">₹{{ number_format($order->total_amount, 0) }} · {{ optional($order->order_date)->format('M j, Y') }}</div>
                            @foreach($order->orderItems as $item)
                                <div class="small mt-1">
                                    {{ $item->product_name }}
                                    @if($item->item_type === 'package')
                                        <span class="badge bg-warning text-dark">Package</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p class="text-muted mt-3 mb-0">No orders found.</p>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

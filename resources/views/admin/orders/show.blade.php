@extends('admin.layouts.admin')

@section('content')
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h5>Order Details #{{ $order->id }}</h5>
    </div>
    <div class="card-body">
        <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
        <p><strong>Payment Type:</strong> {{ ucfirst($order->payment_method) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Total:</strong> ₹{{ number_format($order->total_amount, 2) }}</p>

        <h6 class="mt-4">Order Items:</h6>
        <ul>
            @foreach ($order->items as $item)
                <li>{{ optional($item->product->translation)->name ??$item->product->name ?? 'Product' }} × {{ $item->quantity }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection

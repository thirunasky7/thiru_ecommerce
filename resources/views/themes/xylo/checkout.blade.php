@extends('themes.xylo.layouts.master')

@section('title', 'Checkout — ThaiYur')

@section('content')
<section class="ty-page-hero">
    <div class="container">
        <p class="ty-eyebrow">Secure checkout</p>
        <h1>Complete your order</h1>
        <p>Confirm delivery details for packages and products.</p>
    </div>
</section>

<section class="ty-section">
    <div class="container">
        <form method="POST" action="{{ route('checkout.store') }}" class="row g-4">
            @csrf
            <div class="col-lg-7">
                <div class="ty-surface-panel">
                    <h3 class="mb-3">Delivery details</h3>
                    <div class="mb-3">
                        <label class="form-label">Full name *</label>
                        <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Delivery address *</label>
                        <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Payment</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" value="cod" id="cod" checked>
                            <label class="form-check-label" for="cod">Cash on delivery</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="ty-surface-panel ty-order-panel">
                    <h3 class="mb-3">Summary</h3>
                    @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2 small">
                            <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                            <strong>₹{{ number_format($item['price'] * $item['quantity'], 0) }}</strong>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total</span>
                        <strong>₹{{ number_format($total, 0) }}</strong>
                    </div>
                    <button type="submit" class="ty-btn ty-btn--solid w-100">Place order</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

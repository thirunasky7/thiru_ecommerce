@extends('themes.xylo.layouts.master')

@section('title', $package->name . ' — ThaiYur')

@section('content')
<section class="ty-page-hero" style="--accent:#C45C26">
    <div class="container">
        <p class="ty-eyebrow">Monthly package</p>
        <h1>{{ $package->name }}</h1>
        <p>{{ $package->description }}</p>
        @if($package->badge)
            <span class="ty-chip mt-2">{{ $package->badge }}</span>
        @endif
    </div>
</section>

<section class="ty-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="ty-surface-panel">
                    <h2>What's included</h2>
                    <p>{{ $package->includes ?: 'Freshly prepared meals delivered for the full package duration.' }}</p>

                    <div class="row g-3 mt-2">
                        <div class="col-sm-4">
                            <div class="ty-stat"><strong>{{ $package->duration_days }}</strong><span>Days</span></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="ty-stat"><strong>{{ $package->meals_per_day }}</strong><span>Meals / day</span></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="ty-stat"><strong>{{ $package->duration_days * $package->meals_per_day }}</strong><span>Total meals</span></div>
                        </div>
                    </div>

                    @if($package->sample_menu)
                        <h3 class="mt-4">Sample menu</h3>
                        <ul class="ty-menu-list">
                            @foreach($package->sample_menu as $line)
                                <li>{{ $line }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="col-lg-5">
                <div class="ty-surface-panel ty-order-panel">
                    <div class="ty-package-card__price mb-3">
                        <strong>₹{{ number_format($package->price, 0) }}</strong>
                        @if($package->compare_price)
                            <s>₹{{ number_format($package->compare_price, 0) }}</s>
                        @endif
                        <span>for {{ $package->duration_days }} days</span>
                    </div>
                    <p class="text-muted small">Meals: {{ $package->meal_types_label }}</p>
                    <p class="small mb-3">Kitchen: {{ optional($package->shop)->name ?? optional($package->vendor)->name ?? 'ThaiYur' }}</p>

                    <label class="form-label">Package start date</label>
                    <input type="date" id="package_start_date" class="form-control mb-3" min="{{ $minStart }}" value="{{ $minStart }}">

                    <label class="form-label">Quantity</label>
                    <input type="number" id="package_qty" class="form-control mb-3" min="1" max="5" value="1">

                    <button type="button" class="ty-btn ty-btn--solid w-100" id="addPackageBtn"
                        data-id="{{ $package->id }}">
                        Add package to cart
                    </button>
                    <a href="{{ route('packages.index') }}" class="ty-link d-block text-center mt-3">Browse other plans</a>
                </div>
            </div>
        </div>

        @if($related->count())
            <div class="ty-section__head mt-5"><h2>Other plans</h2></div>
            <div class="row g-3">
                @foreach($related as $item)
                    <div class="col-md-4">
                        <a href="{{ route('packages.show', $item->slug) }}" class="ty-service-card">
                            <h3>{{ $item->name }}</h3>
                            <p>₹{{ number_format($item->price, 0) }} · {{ $item->duration_days }} days</p>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

@section('js')
<script>
$('#addPackageBtn').on('click', function () {
    const btn = $(this);
    btn.prop('disabled', true).text('Adding...');
    $.ajax({
        url: "{{ route('packages.add-to-cart') }}",
        method: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            package_id: btn.data('id'),
            start_date: $('#package_start_date').val(),
            quantity: $('#package_qty').val()
        },
        success: function (res) {
            if (res.cart_count !== undefined) {
                $('#cart-count').text(res.cart_count);
            }
            if (typeof toastr !== 'undefined') {
                toastr.success(res.message || 'Added to cart');
            } else {
                alert(res.message || 'Added to cart');
            }
            btn.prop('disabled', false).text('Add package to cart');
        },
        error: function (xhr) {
            const msg = xhr.responseJSON?.message || 'Could not add package';
            alert(msg);
            btn.prop('disabled', false).text('Add package to cart');
        }
    });
});
</script>
@endsection

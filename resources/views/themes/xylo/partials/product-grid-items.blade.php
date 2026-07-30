@foreach($products as $product)
    <div class="col-6 col-md-{{ $cols ?? 3 }} ty-product-item">
        @include('themes.xylo.components.product-card', ['product' => $product])
    </div>
@endforeach

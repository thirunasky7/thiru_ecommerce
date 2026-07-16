<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ThaiYur — Food · Grocery · Marketplace')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('/css/front-style.css') }}">
    @yield('css')
</head>
<body class="ty-body">
    @include('themes.xylo.layouts.header')
    <main>
        @yield('content')
    </main>
    @include('themes.xylo.layouts.footer')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        window.tyAddToCart = function (productId, quantity, orderForDate, mealType) {
            quantity = quantity || 1;
            orderForDate = orderForDate || new Date(Date.now() + 86400000).toISOString().slice(0, 10);
            mealType = mealType || 'regular';

            return $.ajax({
                url: "{{ route('cart.add') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    quantity: quantity,
                    order_for_date: orderForDate,
                    meal_type: mealType
                }
            }).done(function (res) {
                if (res.cart_count !== undefined) {
                    $('#cart-count').text(res.cart_count);
                }
                if (typeof toastr !== 'undefined') {
                    toastr.success(res.message || 'Added to cart');
                } else {
                    alert(res.message || 'Added to cart');
                }
            }).fail(function (xhr) {
                const msg = (xhr.responseJSON && (xhr.responseJSON.message || Object.values(xhr.responseJSON.errors || {}).flat().join(', '))) || 'Could not add to cart';
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-reveal]').forEach(function (el, i) {
                el.style.setProperty('--reveal-delay', (i % 6) * 80 + 'ms');
                requestAnimationFrame(function () { el.classList.add('is-visible'); });
            });
        });
    </script>
    @yield('js')
</body>
</html>

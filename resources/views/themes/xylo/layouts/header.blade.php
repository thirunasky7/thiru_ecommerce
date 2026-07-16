<header class="ty-header">
    <div class="ty-header__top">
        <div class="container d-flex justify-content-between align-items-center">
            <span>Food · Grocery · Marketplace — delivered by local vendors</span>
            <div class="d-flex gap-3">
                <a href="{{ route('order.tracking') }}">Track order</a>
                <a href="{{ route('vendor.login') }}">Sell on ThaiYur</a>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg ty-nav">
        <div class="container">
            <a class="navbar-brand ty-brand" href="{{ route('xylo.home') }}">
                <span class="ty-brand__mark">TY</span>
                <span class="ty-brand__text">ThaiYur</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#tyNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="tyNav">
                <ul class="navbar-nav mx-auto ty-nav__links">
                    <li class="nav-item"><a class="nav-link" href="{{ route('xylo.home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('packages.index') }}">Monthly food</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('service.show', 'grocery') }}">Grocery</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('service.show', 'products') }}">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('vendors.index') }}">Vendors</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}">Services</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3 ty-nav__actions">
                    <a href="{{ url('/search') }}" class="ty-icon-btn" title="Search"><i class="fas fa-search"></i></a>
                    <a href="{{ route('cart.page') }}" class="ty-icon-btn position-relative" title="Cart">
                        <i class="fas fa-bag-shopping"></i>
                        <span id="cart-count" class="ty-cart-badge">{{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }}</span>
                    </a>
                    @auth('customer')
                        <a href="{{ route('customer.wishlist.index') }}" class="ty-icon-btn"><i class="fas fa-heart"></i></a>
                        <form action="{{ route('customer.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="ty-btn ty-btn--ghost">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('customer.login') }}" class="ty-btn ty-btn--ghost">Sign in</a>
                        <a href="{{ route('customer.register') }}" class="ty-btn ty-btn--solid">Join</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

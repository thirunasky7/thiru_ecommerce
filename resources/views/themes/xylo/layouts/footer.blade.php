<footer class="ty-footer">
    <div class="container">
        <div class="row g-4 ty-footer__grid">
            <div class="col-lg-4">
                <div class="ty-brand ty-brand--footer mb-3">
                    <span class="ty-brand__mark">TY</span>
                    <span class="ty-brand__text">ThaiYur</span>
                </div>
                <p class="ty-footer__lead">A premium multivendor marketplace for food delivery, grocery, and everyday products — powered by local sellers.</p>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Explore</h6>
                <ul>
                    <li><a href="{{ route('packages.index') }}">Monthly food</a></li>
                    <li><a href="{{ route('service.show', 'grocery') }}">Grocery</a></li>
                    <li><a href="{{ route('service.show', 'products') }}">Products</a></li>
                    <li><a href="{{ route('vendors.index') }}">Vendors</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Help</h6>
                <ul>
                    <li><a href="{{ route('order.tracking') }}">Track order</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('customer.login') }}">Account</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6>Vendor partners</h6>
                <p class="ty-footer__lead">Grow your kitchen, grocery store, or product shop with ThaiYur.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('vendor.register') }}" class="ty-btn ty-btn--solid">Become a partner</a>
                    <a href="{{ route('vendor.login') }}" class="ty-btn ty-btn--ghost">Login</a>
                </div>
            </div>
        </div>
        <div class="ty-footer__bottom">
            <span>© {{ date('Y') }} ThaiYur. All rights reserved.</span>
            <span>Made for multivendor commerce</span>
        </div>
    </div>
</footer>

<nav class="ty-mobile-nav d-lg-none">
    <a href="{{ route('xylo.home') }}"><i class="fas fa-house"></i><span>Home</span></a>
    <a href="{{ route('packages.index') }}"><i class="fas fa-utensils"></i><span>Food</span></a>
    <a href="{{ route('cart.page') }}" class="ty-mobile-nav__cart"><i class="fas fa-bag-shopping"></i><span>Cart</span></a>
    <a href="{{ route('service.show', 'grocery') }}"><i class="fas fa-basket-shopping"></i><span>Grocery</span></a>
    <a href="{{ route('customer.login') }}"><i class="fas fa-user"></i><span>Account</span></a>
</nav>

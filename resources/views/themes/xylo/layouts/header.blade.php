<header class="sticky-top bg-white shadow-sm">
    <!-- Top Bar (Optional) -->
    <!-- <div class="top-bar w-100 bg-light py-2 d-none d-md-block">
        <div class="container">
            <div class="text-center small">
                B313 Shantiniketan • Free Shipping on Orders Over $50
            </div>
        </div>
    </div> -->

    <!-- Main Header -->
    <div class="container-fluid">
        <!-- Mobile Top Row: Logo + Hamburger + Cart -->
        <div class="row align-items-center py-2 d-md-none">
            <div class="col-4">
                <button class="btn btn-link text-dark p-0 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
            </div>
            <div class="col-4 text-center">
                <a href="{{ route('xylo.home') }}" class="navbar-brand">
                    <img src="https://i.ibb.co/dHx2ZR3/velstore.png" width="70" alt="Logo" class="img-fluid" />
                </a>
            </div>
            <div class="col-4 text-end">
                <a href="{{ route('cart.view') }}" class="text-dark position-relative me-3">
                    <i class="fas fa-shopping-bag fa-lg"></i>
                    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                        {{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }}
                    </span>
                </a>
            </div>
        </div>

        <!-- Desktop Top Row: Logo + Search + Actions -->
        <div class="row align-items-center py-3 d-none d-md-flex">
            <div class="col-md-3">
                <a href="{{ route('xylo.home') }}" class="navbar-brand">
                    <img src="https://i.ibb.co/dHx2ZR3/velstore.png" width="90" alt="Logo" />
                </a>
            </div>
            <div class="col-md-6">
                <form class="d-flex" action="{{ url('/search') }}" method="GET">
                    <div class="input-group search-input-width w-100">
                        <input type="text" class="form-control border-end-0" id="search-input" name="q" placeholder="Search for products, brands and more">
                        <button type="submit" class="btn btn-outline-secondary border-start-0 search-style">
                            <i class="fas fa-search"></i>
                        </button>
                        <div id="search-suggestions" class="dropdown-menu show w-100 mt-1 d-none"></div>
                    </div>
                </form>
            </div>
            <div class="col-md-3 d-flex justify-content-end align-items-center gap-3">
                <!-- Language Selector -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-globe me-1"></i>
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <form action="{{ route('change.store.language') }}" method="POST" class="dropdown-item">
                            @csrf
                            <button type="submit" name="lang" value="en" class="btn btn-link p-0 text-decoration-none w-100 text-start">English</button>
                        </form>
                        <form action="{{ route('change.store.language') }}" method="POST" class="dropdown-item">
                            @csrf
                            <button type="submit" name="lang" value="fr" class="btn btn-link p-0 text-decoration-none w-100 text-start">Français</button>
                        </form>
                        <form action="{{ route('change.store.language') }}" method="POST" class="dropdown-item">
                            @csrf
                            <button type="submit" name="lang" value="es" class="btn btn-link p-0 text-decoration-none w-100 text-start">Español</button>
                        </form>
                    </ul>
                </div>

                <!-- Account -->
                <div class="dropdown">
                    <a href="#" class="text-dark dropdown-toggle d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>
                        <span class="d-none d-lg-inline">Account</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2">
                        @guest
                            <li><a class="dropdown-item" href="{{ route('customer.login') }}"><i class="fas fa-sign-in-alt me-2"></i>Sign In</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.register') }}"><i class="fas fa-user-plus me-2"></i>Sign Up</a></li>
                        @else
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-box me-2"></i>Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                   <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>

                <!-- Wishlist -->
                <a href="{{ auth()->check() ? route('customer.wishlist.index') : route('customer.login') }}" class="text-dark d-flex align-items-center text-decoration-none">
                    <i class="fas fa-heart me-1"></i>
                    <span class="d-none d-lg-inline"></span>
                </a>

                <!-- Cart -->
                <a href="{{ route('cart.view') }}" class="text-dark position-relative d-flex align-items-center text-decoration-none">
                    <i class="fas fa-shopping-bag me-1"></i>
                    <span class="d-none d-lg-inline"></span>
                    <span id="cart-count-desktop" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                        {{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }}
                    </span>
                </a>
            </div>
        </div>

        <!-- Mobile Search Bar -->
        <div class="row d-md-none py-2">
            <div class="col-12">
                <form action="{{ url('/search') }}" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" placeholder="Search products...">
                        <button type="submit" class="btn btn-outline-secondary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Desktop Navigation -->
        <div class="row align-items-center py-2 d-none d-md-flex border-top">
            <div class="col-md-8">
                <nav>
                    <ul class="nav">
                        @if ($headerMenu && $headerMenu->menuItems->count())
                            @foreach ($headerMenu->menuItems as $menuItem)
                                <li class="nav-item">
                                    <a class="nav-link menu-text-color fw-medium" href="{{ url($menuItem->slug) }}">
                                        {{ $menuItem->translation->title ?? 'No Translation' }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </nav>
            </div>
            <div class="col-md-4 d-flex justify-content-end">
                <!-- Currency Selector -->
                <form action="{{ route('change.currency') }}" method="POST" class="d-inline">
                    @csrf
                    <select name="currency_code" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                        @foreach (\App\Models\Currency::all() as $currency)
                            <option value="{{ $currency->code }}" {{ session('currency', 'USD') == $currency->code ? 'selected' : '' }}>
                                {{ strtoupper($currency->code) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Mobile Offcanvas Menu -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Navigation Menu -->
            <nav class="mb-4">
                <ul class="list-unstyled">
                    @if ($headerMenu && $headerMenu->menuItems->count())
                        @foreach ($headerMenu->menuItems as $menuItem)
                            <li class="mb-2">
                                <a class="text-dark text-decoration-none fw-medium d-block py-2" href="{{ url($menuItem->slug) }}">
                                    {{ $menuItem->translation->title ?? 'No Translation' }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </nav>

            <!-- User Section -->
            <div class="border-top pt-3">
                @guest
                    <div class="d-grid gap-2">
                        <a href="{{ route('customer.login') }}" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </a>
                        <a href="{{ route('customer.register') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i>Sign Up
                        </a>
                    </div>
                @else
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">My Account</h6>
                        <a href="#" class="text-dark text-decoration-none d-block py-1">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                        <a href="#" class="text-dark text-decoration-none d-block py-1">
                            <i class="fas fa-box me-2"></i>Orders
                        </a>
                        <a href="#" class="text-dark text-decoration-none d-block py-1">
                            <i class="fas fa-heart me-2"></i>Wishlist
                        </a>
                    </div>
                @endguest
            </div>

            <!-- Settings -->
            <div class="border-top pt-3">
                <h6 class="text-muted mb-2">Settings</h6>
                
                <!-- Language Selector -->
                <div class="mb-2">
                    <label class="form-label small text-muted">Language</label>
                    <form action="{{ route('change.store.language') }}" method="POST" class="d-grid">
                        @csrf
                        <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                            <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>Français</option>
                            <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>Español</option>
                        </select>
                    </form>
                </div>

                <!-- Currency Selector -->
                <div class="mb-2">
                    <label class="form-label small text-muted">Currency</label>
                    <form action="{{ route('change.currency') }}" method="POST" class="d-grid">
                        @csrf
                        <select name="currency_code" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach (\App\Models\Currency::all() as $currency)
                                <option value="{{ $currency->code }}" {{ session('currency', 'USD') == $currency->code ? 'selected' : '' }}>
                                    {{ strtoupper($currency->code) }} - {{ $currency->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @auth
                    <div class="mt-3">
                        <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update cart counts in real-time
    function updateCartCount() {
        const cartCount = {{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }};
        document.querySelectorAll('#cart-count, #cart-count-desktop').forEach(element => {
            element.textContent = cartCount;
        });
    }

    // Search functionality with debounce
    let searchTimeout;
    const searchInput = document.getElementById('search-input');
    const searchSuggestions = document.getElementById('search-suggestions');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = e.target.value.trim();
                if (query.length > 2) {
                    fetchSearchSuggestions(query);
                } else {
                    hideSearchSuggestions();
                }
            }, 300);
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                hideSearchSuggestions();
            }
        });
    }

    function fetchSearchSuggestions(query) {
        // Add your search API call here
        console.log('Searching for:', query);
        // showSearchSuggestions(results);
    }

    function showSearchSuggestions(results) {
        searchSuggestions.classList.remove('d-none');
        // Populate suggestions
    }

    function hideSearchSuggestions() {
        searchSuggestions.classList.add('d-none');
    }

    // Mobile menu enhancements
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenu) {
        mobileMenu.addEventListener('show.bs.offcanvas', function() {
            document.body.style.overflow = 'hidden';
        });

        mobileMenu.addEventListener('hidden.bs.offcanvas', function() {
            document.body.style.overflow = '';
        });
    }

    // Touch enhancements for mobile
    if ('ontouchstart' in window) {
        document.querySelectorAll('.nav-link, .dropdown-toggle').forEach(element => {
            element.style.cursor = 'pointer';
        });
    }
});
</script>
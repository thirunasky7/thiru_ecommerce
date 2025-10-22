<!-- Header -->
    <header class="sticky-top bg-white shadow-sm">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light py-3">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">TasteIt</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ url('/home')}}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/categories')}}">Categories</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/products')}}">Products</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('about-us')}}">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('services')}}">Our Services</a>
                            </li>
                        </ul>
                        <div class="d-flex align-items-center">
                            <a href="#" class="text-dark me-3 position-relative">
                                <i class="fas fa-heart fa-lg"></i>
                            </a>
                            <a href="{{ route('cart.view') }}" class="text-dark me-3 position-relative">
                                <i class="fas fa-shopping-cart fa-lg"></i>
                                <span id="cart-count" class="cart-badge"> {{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }}</span>
                            </a>
                            <div class="dropdown">
                                <a class="text-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user fa-lg"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">My Account</a></li>
                                    <li><a class="dropdown-item" href="#">Orders</a></li>
                                    <li><a class="dropdown-item" href="#">Wishlist</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
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
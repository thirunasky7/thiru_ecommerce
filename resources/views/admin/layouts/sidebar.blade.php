<nav id="sidebar" class="ad-sidebar d-flex flex-column">
    <div class="ad-brand">
        <span class="ad-brand__mark">TY</span>
        <div>
            <div class="ad-brand__text">ThaiYur</div>
            <div class="ad-brand__sub">Admin console</div>
        </div>
    </div>

    <div class="search-container position-relative mb-3 px-1">
        <input type="text" class="form-control form-control-sm" placeholder="Search menu..." id="searchInput" autocomplete="off">
    </div>

    <ul class="nav flex-column px-1">
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-gauge-high me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#ordersMenu">
                <span><i class="fas fa-bag-shopping me-2"></i> Orders</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse {{ str_starts_with(Route::currentRouteName() ?? '', 'admin.orders') ? 'show' : '' }}" id="ordersMenu">
                <ul class="nav flex-column ms-2">
                    <li><a class="nav-link {{ Route::currentRouteName() == 'admin.orders.index' ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">All orders</a></li>
                    <li><a class="nav-link {{ Route::currentRouteName() == 'admin.orders.kitchen' ? 'active' : '' }}" href="{{ route('admin.orders.kitchen') }}">Kitchen display</a></li>
                    <li><a class="nav-link {{ Route::currentRouteName() == 'admin.orders.delivery-schedule' ? 'active' : '' }}" href="{{ route('admin.orders.delivery-schedule') }}">Delivery schedule</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#packageMenu">
                <span><i class="fas fa-calendar-check me-2"></i> Food packages</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse {{ str_starts_with(Route::currentRouteName() ?? '', 'admin.food-packages') ? 'show' : '' }}" id="packageMenu">
                <ul class="nav flex-column ms-2">
                    <li><a class="nav-link {{ Route::currentRouteName() == 'admin.food-packages.create' ? 'active' : '' }}" href="{{ route('admin.food-packages.create') }}">Add package</a></li>
                    <li><a class="nav-link {{ Route::currentRouteName() == 'admin.food-packages.index' ? 'active' : '' }}" href="{{ route('admin.food-packages.index') }}">All packages</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#productMenu">
                <span><i class="fas fa-box me-2"></i> Products</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse {{ str_contains(Route::currentRouteName() ?? '', 'products') ? 'show' : '' }}" id="productMenu">
                <ul class="nav flex-column ms-2">
                    <li><a class="nav-link {{ Route::currentRouteName() == 'admin.products.create' ? 'active' : '' }}" href="{{ route('admin.products.create') }}">Add product</a></li>
                    <li><a class="nav-link {{ Route::currentRouteName() == 'admin.products.index' ? 'active' : '' }}" href="{{ route('admin.products.index') }}">All products</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#categoryMenu">
                <span><i class="fas fa-layer-group me-2"></i> Categories</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse {{ str_contains(Route::currentRouteName() ?? '', 'categories') ? 'show' : '' }}" id="categoryMenu">
                <ul class="nav flex-column ms-2">
                    <li><a class="nav-link {{ Route::currentRouteName() == 'admin.categories.create' ? 'active' : '' }}" href="{{ route('admin.categories.create') }}">Add category</a></li>
                    <li><a class="nav-link {{ Route::currentRouteName() == 'admin.categories.index' ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">All categories</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#sellerMenu">
                <span><i class="fas fa-store me-2"></i> Vendors</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse {{ str_contains(Route::currentRouteName() ?? '', 'sellers') ? 'show' : '' }}" id="sellerMenu">
                <ul class="nav flex-column ms-2">
                    <li>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.sellers.index' ? 'active' : '' }}" href="{{ route('admin.sellers.index') }}">
                            All vendors
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ route('admin.sellers.index', ['status' => 'pending']) }}">
                            Pending approval
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'admin.customers.index' ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                <i class="fas fa-users me-2"></i> Customers
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#bannerMenu">
                <span><i class="fas fa-image me-2"></i> Banners</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse {{ str_contains(Route::currentRouteName() ?? '', 'banners') ? 'show' : '' }}" id="bannerMenu">
                <ul class="nav flex-column ms-2">
                    <li><a class="nav-link {{ Route::currentRouteName() == 'admin.banners.create' ? 'active' : '' }}" href="{{ route('admin.banners.create') }}">Add banner</a></li>
                    <li><a class="nav-link {{ Route::currentRouteName() == 'admin.banners.index' ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">All banners</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#weeklyMenu">
                <span><i class="fas fa-utensils me-2"></i> Kitchen menu</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse {{ str_contains(Route::currentRouteName() ?? '', 'weeklymenu') ? 'show' : '' }}" id="weeklyMenu">
                <ul class="nav flex-column ms-2">
                    <li><a class="nav-link" href="{{ route('admin.weeklymenu.create') }}">Add day menu</a></li>
                    <li><a class="nav-link" href="{{ route('admin.weeklymenu.index') }}">Menu calendar</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#settingsMenu">
                <span><i class="fas fa-sliders me-2"></i> Settings</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="collapse {{ in_array(Route::currentRouteName(), ['site-settings.index', 'admin.social-media-links.index', 'admin.social-media-links.create']) ? 'show' : '' }}" id="settingsMenu">
                <ul class="nav flex-column ms-2">
                    <li><a class="nav-link" href="{{ route('site-settings.index') }}">Site settings</a></li>
                    <li><a class="nav-link" href="{{ route('admin.social-media-links.index') }}">Social links</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>

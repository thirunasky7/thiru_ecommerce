<nav id="sidebar" class="d-flex flex-column p-3">
    <div class="mb-4">
        <strong class="fs-5">ThaiYur Vendor</strong>
        <div class="small" style="opacity:.7">Seller panel</div>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'vendor.dashboard' ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_starts_with(Route::currentRouteName() ?? '', 'vendor.products') ? 'active' : '' }}" href="{{ route('vendor.products.index') }}">
                <i class="fas fa-box me-2"></i> Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('vendor.products.create') }}">
                <i class="fas fa-plus me-2"></i> Add product
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ str_starts_with(Route::currentRouteName() ?? '', 'vendor.social-media-links') ? 'active' : '' }}" href="{{ route('vendor.social-media-links.index') }}">
                <i class="fas fa-share-alt me-2"></i> Social links
            </a>
        </li>
        <li class="nav-item mt-3">
            <a class="nav-link" href="{{ url('/') }}" target="_blank">
                <i class="fas fa-store me-2"></i> View storefront
            </a>
        </li>
    </ul>
</nav>

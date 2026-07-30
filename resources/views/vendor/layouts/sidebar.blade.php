<nav id="sidebar" class="d-flex flex-column">
    <div class="mb-4">
        <strong style="font-size:1.25rem;">ThaiYur Vendor</strong>
        <div class="small text-white-50">Partner console</div>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'vendor.dashboard' ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">
                <i class="fas fa-gauge-high me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Route::currentRouteName() == 'vendor.profile.edit' ? 'active' : '' }}" href="{{ route('vendor.profile.edit') }}">
                <i class="fas fa-image me-2"></i> Brand images
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
                <i class="fas fa-share-nodes me-2"></i> Social links
            </a>
        </li>
        <li class="nav-item mt-3">
            <a class="nav-link" href="{{ url('/') }}" target="_blank">
                <i class="fas fa-external-link me-2"></i> Storefront
            </a>
        </li>
    </ul>
</nav>
